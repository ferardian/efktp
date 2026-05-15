<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session()->get('role') != 'admin') {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('content.master.user');
    }

    public function data(Request $request)
    {
        $users = User::select('*', 
            DB::raw("AES_DECRYPT(id_user, 'nur') as username"),
            DB::raw("AES_DECRYPT(password, 'windi') as passwd")
        )->get();

        foreach ($users as $user) {
            $pegawai = Pegawai::where('nik', $user->username)->select('nama', 'jbtn')->first();
            $user->nama = $pegawai ? $pegawai->nama : '-';
            $user->jabatan = $pegawai ? $pegawai->jbtn : '-';
        }

        return response()->json($users);
    }

    public function get(Request $request)
    {
        $user = User::where('id_user', DB::raw("AES_ENCRYPT('" . $request->username . "', 'nur')"))
            ->select('*', 
                DB::raw("AES_DECRYPT(id_user, 'nur') as username"),
                DB::raw("AES_DECRYPT(password, 'windi') as passwd")
            )
            ->first();
        
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $data = [];
            $data['id_user'] = DB::raw("AES_ENCRYPT('" . $request->username . "', 'nur')");
            $data['password'] = DB::raw("AES_ENCRYPT('" . $request->password . "', 'windi')");

            // Set all other columns to 'false' as requested
            $columns = \Schema::getColumnListing('user');
            foreach ($columns as $col) {
                if (!in_array($col, ['id_user', 'password'])) {
                    $data[$col] = 'false';
                }
            }

            DB::table('user')->insert($data);

            return response()->json(['message' => 'User berhasil ditambahkan dengan akses kosong']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $username)
    {
        try {
            $data = $request->except(['username', 'password', '_token', '_method']);
            
            if ($request->filled('password')) {
                $data['password'] = DB::raw("AES_ENCRYPT('" . $request->password . "', 'windi')");
            }

            $columns = \Schema::getColumnListing('user');
            foreach ($columns as $col) {
                if (!in_array($col, ['id_user', 'password'])) {
                    $data[$col] = $request->has($col) ? 'true' : 'false';
                }
            }

            DB::table('user')
                ->where('id_user', DB::raw("AES_ENCRYPT('" . $username . "', 'nur')"))
                ->update($data);

            return response()->json(['message' => 'User berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($username)
    {
        try {
            DB::table('user')
                ->where('id_user', DB::raw("AES_ENCRYPT('" . $username . "', 'nur')"))
                ->delete();

            return response()->json(['message' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
    }

    public function getColumns()
    {
        $columns = \Schema::getColumnListing('user');
        $filtered = array_filter($columns, function($col) {
            return !in_array($col, ['id_user', 'password']);
        });
        return response()->json(array_values($filtered));
    }
}
