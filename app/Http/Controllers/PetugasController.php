<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        return view('content.master.petugas', compact('jabatan'));
    }

    public function data(Request $request)
    {
        $petugas = Petugas::with('jabatan')->get();
        return response()->json($petugas);
    }

    public function get(Request $request)
    {
        $petugas = Petugas::where('nip', $request->nip)->first();
        return response()->json($petugas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:petugas,nip',
            'nama' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Ensure reference tables have default values (Khanza style)
            DB::table('jnj_jabatan')->insertOrIgnore(['kode' => '-', 'nama' => '-', 'tnj' => 0, 'indek' => 0]);
            DB::table('departemen')->insertOrIgnore(['dep_id' => '-', 'nama' => '-']);
            DB::table('bidang')->insertOrIgnore(['nama' => '-']);
            DB::table('stts_wp')->insertOrIgnore(['stts' => '-', 'ktg' => '-']);
            DB::table('stts_kerja')->insertOrIgnore(['stts' => '-', 'ktg' => '-', 'indek' => 0]);
            DB::table('pendidikan')->insertOrIgnore(['tingkat' => '-', 'indek' => 0, 'gapok1' => 0, 'kenaikan' => 0, 'maksimal' => 0]);
            DB::table('bank')->insertOrIgnore(['namabank' => 'T']);
            DB::table('kelompok_jabatan')->insertOrIgnore(['kode_kelompok' => '-', 'nama_kelompok' => '-', 'indek' => 0]);
            DB::table('resiko_kerja')->insertOrIgnore(['kode_resiko' => '-', 'nama_resiko' => '-', 'indek' => 0]);
            DB::table('emergency_index')->insertOrIgnore(['kode_emergency' => '-', 'nama_emergency' => '-', 'indek' => 0]);

            // 1. Upsert to Pegawai
            $jk_pegawai = ($request->jk == 'L') ? 'Pria' : 'Wanita';
            \App\Models\Pegawai::updateOrCreate(
                ['nik' => $request->nip],
                [
                    'nama'           => $request->nama,
                    'jk'             => $jk_pegawai,
                    'no_ktp'         => '-',
                    'jbtn'           => '-',
                    'jnj_jabatan'    => '-',
                    'kode_kelompok'  => '-',
                    'kode_resiko'    => '-',
                    'kode_emergency' => '-',
                    'departemen'     => '-',
                    'bidang'         => '-',
                    'stts_wp'     => '-',
                    'stts_kerja'  => '-',
                    'pendidikan'  => '-',
                    'bpd'         => 'T',
                    'indexins'    => '-',
                    'stts_aktif'  => 'AKTIF',
                    'mulai_kerja' => '1900-01-01',
                    'tgl_lahir'   => $request->tgl_lahir ?? '1900-01-01',
                    'tmp_lahir'   => $request->tmp_lahir ?? '-',
                    'kota'        => '-',
                ]
            );

            // 2. Insert to Petugas
            Petugas::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Data Petugas berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $nip)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $petugas = Petugas::where('nip', $nip)->firstOrFail();
            $petugas->update($request->all());

            $pegawai = \App\Models\Pegawai::where('nik', $nip)->first();
            if ($pegawai) {
                $jk_pegawai = ($request->jk == 'L') ? 'Pria' : 'Wanita';
                $pegawai->update([
                    'nama'      => $request->nama,
                    'jk'        => $jk_pegawai,
                    'tgl_lahir' => $request->tgl_lahir ?? '1900-01-01',
                    'tmp_lahir' => $request->tmp_lahir ?? '-',
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data Petugas berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($nip)
    {
        try {
            DB::beginTransaction();
            Petugas::where('nip', $nip)->delete();
            \App\Models\Pegawai::where('nik', $nip)->delete();
            DB::commit();
            return response()->json(['message' => 'Data Petugas berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $petugas = Petugas::where('nama', 'like', "%$keyword%")
            ->orWhere('nip', 'like', "%$keyword%")
            ->with('jabatan')
            ->limit(50)
            ->get();
        return response()->json($petugas);
    }
}
