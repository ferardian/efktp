<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Traits\Track;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    use Track;

    public function index()
    {
        return view('content.master.dokter');
    }

    function get(Request $request)
    {
        $dokter = Dokter::with('pegawai');
        if ($request->kd_dokter) {
            $dokter = $dokter->where('kd_dokter', $request->kd_dokter)->first();
        } else if ($request->dokter) {
            $dokter = $dokter->where('nm_dokter', 'like', "%{$request->dokter}%")
                ->where('status', '1')
                ->get();
        } else {
            $dokter = $dokter->where('status', '1')->get();
        }
        return response()->json($dokter);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_dokter' => 'required|unique:dokter,kd_dokter',
            'nm_dokter' => 'required',
            'no_ktp'    => 'required',
            'jk'        => 'required',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Ensure reference tables have default values to avoid FK errors (Khanza style)
            \Illuminate\Support\Facades\DB::table('jnj_jabatan')->insertOrIgnore(['kode' => '-', 'nama' => '-', 'tnj' => 0, 'indek' => 0]);
            \Illuminate\Support\Facades\DB::table('departemen')->insertOrIgnore(['dep_id' => '-', 'nama' => '-']);
            \Illuminate\Support\Facades\DB::table('bidang')->insertOrIgnore(['nama' => '-']);
            \Illuminate\Support\Facades\DB::table('stts_wp')->insertOrIgnore(['stts' => '-', 'ktg' => '-']);
            \Illuminate\Support\Facades\DB::table('stts_kerja')->insertOrIgnore(['stts' => '-', 'ktg' => '-', 'indek' => 0]);
            \Illuminate\Support\Facades\DB::table('pendidikan')->insertOrIgnore(['tingkat' => '-', 'indek' => 0, 'gapok1' => 0, 'kenaikan' => 0, 'maksimal' => 0]);
            \Illuminate\Support\Facades\DB::table('bank')->insertOrIgnore(['namabank' => 'T']);
            \Illuminate\Support\Facades\DB::table('kelompok_jabatan')->insertOrIgnore(['kode_kelompok' => '-', 'nama_kelompok' => '-', 'indek' => 0]);
            \Illuminate\Support\Facades\DB::table('resiko_kerja')->insertOrIgnore(['kode_resiko' => '-', 'nama_resiko' => '-', 'indek' => 0]);
            \Illuminate\Support\Facades\DB::table('emergency_index')->insertOrIgnore(['kode_emergency' => '-', 'nama_emergency' => '-', 'indek' => 0]);

            // 1. Insert to Pegawai
            $jk_pegawai = ($request->jk == 'L') ? 'Pria' : 'Wanita';
            \App\Models\Pegawai::create([
                'nik'            => $request->kd_dokter,
                'nama'           => $request->nm_dokter,
                'jk'             => $jk_pegawai,
                'no_ktp'         => $request->no_ktp,
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
                'tgl_lahir'   => '1900-01-01',
                'tmp_lahir'   => '-',
                'kota'        => '-',
            ]);

            // 2. Insert to Dokter
            $dokterData = $request->only([
                'kd_dokter', 'nm_dokter', 'jk', 'tmp_lahir', 'tgl_lahir',
                'gol_drh', 'agama', 'almt_tgl', 'no_telp', 'email',
                'stts_nikah', 'kd_sps', 'alumni', 'no_ijn_praktek', 'status'
            ]);
            $dokter = Dokter::create($dokterData);

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['message' => 'Data Dokter berhasil disimpan', 'data' => $dokter], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $kd_dokter)
    {
        $request->validate([
            'nm_dokter' => 'required',
            'no_ktp'    => 'required',
            'jk'        => 'required',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $dokter = Dokter::where('kd_dokter', $kd_dokter)->firstOrFail();
            $dokterData = $request->only([
                'nm_dokter', 'jk', 'tmp_lahir', 'tgl_lahir',
                'gol_drh', 'agama', 'almt_tgl', 'no_telp', 'email',
                'stts_nikah', 'kd_sps', 'alumni', 'no_ijn_praktek', 'status'
            ]);
            $dokter->update($dokterData);

            $pegawai = \App\Models\Pegawai::where('nik', $kd_dokter)->first();
            if ($pegawai) {
                $jk_pegawai = ($request->jk == 'L') ? 'Pria' : 'Wanita';
                $pegawai->update([
                    'nama'   => $request->nm_dokter,
                    'jk'     => $jk_pegawai,
                    'no_ktp' => $request->no_ktp,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['message' => 'Data Dokter berhasil diperbarui']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kd_dokter)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            Dokter::where('kd_dokter', $kd_dokter)->delete();
            \App\Models\Pegawai::where('nik', $kd_dokter)->delete();

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['message' => 'Data Dokter berhasil dihapus']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
