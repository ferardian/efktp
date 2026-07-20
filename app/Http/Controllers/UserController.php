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
            if (!in_array(session()->get('role'), ['admin', 'owner'])) {
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
        $roles = DB::table('menu_role')->select('role')->distinct()->pluck('role')->toArray();
        if (empty($roles)) {
            $roles = ['admin', 'dokter', 'apoteker', 'petugas', 'owner'];
        } else if (!in_array('owner', $roles)) {
            $roles[] = 'owner';
        }
        return view('content.master.user', compact('roles'));
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
        
        if ($user && config('app.enable_menu_role')) {
            $user->role = DB::table('user_roles')->where('username', $request->username)->value('role') ?? '';
        }
        
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

            if (config('app.enable_menu_role') && $request->filled('role')) {
                DB::table('user_roles')->updateOrInsert(
                    ['username' => $request->username],
                    ['role' => $request->role, 'updated_at' => now()]
                );
            }

            return response()->json(['message' => 'User berhasil ditambahkan dengan akses kosong']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $username)
    {
        try {
            $data = $request->except(['username', 'password', 'role', '_token', '_method']);
            
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

            if (config('app.enable_menu_role')) {
                if ($request->filled('role')) {
                    DB::table('user_roles')->updateOrInsert(
                        ['username' => $username],
                        ['role' => $request->role, 'updated_at' => now()]
                    );
                } else {
                    DB::table('user_roles')->where('username', $username)->delete();
                }
            }

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

            if (config('app.enable_menu_role')) {
                DB::table('user_roles')->where('username', $username)->delete();
            }

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
