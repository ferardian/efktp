<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PenilaianMedisRalan;

class PenilaianMedisRalanController extends Controller
{
    use Track;

    public function get(Request $request)
    {
        $penilaian = PenilaianMedisRalan::where('no_rawat', $request->no_rawat)
            ->with([
                'regPeriksa.pasien.bahasaPasien',
                'regPeriksa.pasien.cacatFisik',
                'dokter'
            ])->first();

        return response()->json($penilaian);
    }

    public function createPenilaian(Request $request)
    {
        $columns = Schema::getColumnListing('penilaian_medis_ralan');
        $data = array_intersect_key($request->all(), array_flip($columns));

        if (empty($data['no_rawat'])) {
            return response()->json([
                'message' => 'Gagal menyimpan: No. Rawat tidak ditemukan atau tidak boleh kosong.'
            ], 422);
        }

        if (empty($data['tanggal'])) {
            $data['tanggal'] = date('Y-m-d H:i:s');
        }

        $kd_dokter = !empty($data['kd_dokter']) ? $data['kd_dokter'] : (session()->get('pegawai')->nik ?? '-');
        $data['kd_dokter'] = $kd_dokter;
        $this->ensureDokterExists($kd_dokter);

        // Safe defaults matching Khanza RMPenilaianAwalMedisRalanDewasa
        $defaults = [
            'anamnesis' => 'Autoanamnesis',
            'hubungan' => '-',
            'keluhan_utama' => '-',
            'rps' => '-',
            'rpd' => '-',
            'rpk' => '-',
            'rpo' => '-',
            'alergi' => '-',
            'keadaan' => 'Sehat',
            'gcs' => '-',
            'kesadaran' => 'Compos Mentis',
            'td' => '-',
            'nadi' => '-',
            'rr' => '-',
            'suhu' => '-',
            'spo' => '-',
            'bb' => '-',
            'tb' => '-',
            'kepala' => 'Normal',
            'gigi' => 'Normal',
            'tht' => 'Normal',
            'thoraks' => 'Normal',
            'abdomen' => 'Normal',
            'genital' => 'Normal',
            'ekstremitas' => 'Normal',
            'kulit' => 'Normal',
            'ket_fisik' => '-',
            'ket_lokalis' => '-',
            'penunjang' => '-',
            'diagnosis' => '-',
            'tata' => '-',
            'konsulrujuk' => '-',
        ];

        foreach ($defaults as $key => $defaultVal) {
            if (!isset($data[$key]) || $data[$key] === null || $data[$key] === '') {
                $data[$key] = $defaultVal;
            }
        }

        DB::beginTransaction();
        try {
            $existing = PenilaianMedisRalan::where('no_rawat', $request->no_rawat)->first();
            if ($existing) {
                $existing->update($data);
                $penilaian = $existing;
                $this->updateSql(new PenilaianMedisRalan(), $data, ['no_rawat' => $request->no_rawat]);
            } else {
                $penilaian = PenilaianMedisRalan::create($data);
                $this->insertSql(new PenilaianMedisRalan(), $data);
            }

            DB::commit();
            return response()->json(['message' => 'SUKSES', 'data' => $penilaian], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function deletePenilaian(Request $request)
    {
        DB::beginTransaction();
        try {
            PenilaianMedisRalan::where('no_rawat', $request->no_rawat)->delete();
            DB::commit();
            return response()->json(['message' => 'Berhasil menghapus penilaian medis rawat jalan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function print(Request $request)
    {
        $data = PenilaianMedisRalan::with([
            'regPeriksa.pasien.bahasaPasien',
            'regPeriksa.pasien.cacatFisik',
            'dokter'
        ])->where('no_rawat', $request->no_rawat)->first();
        $setting = Setting::first();

        $pdf = PDF::loadView('content.print.penilaianMedisRalan', ['data' => $data, 'setting' => $setting])
            ->setPaper("a4")->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        return $pdf->stream('penilaian-medis-ralan.pdf');
    }

    /**
     * Automatic Dokter Syncing to satisfy DB foreign key constraints
     */
    private function ensureDokterExists($kd_dokter)
    {
        if (empty($kd_dokter) || $kd_dokter === '-') {
            return;
        }

        $exists = DB::table('dokter')->where('kd_dokter', $kd_dokter)->exists();
        if (!$exists) {
            $pegawai = DB::table('pegawai')->where('nik', $kd_dokter)->first();
            if (!$pegawai) {
                DB::table('pegawai')->insert([
                    'id' => DB::table('pegawai')->max('id') + 1,
                    'nik' => $kd_dokter,
                    'nama' => 'Dokter ' . $kd_dokter,
                    'jk' => 'L',
                    'jbtn' => 'Dokter',
                    'jnj_jabatan' => '-',
                    'departemen' => '-',
                    'bidang' => '-',
                    'stts_wp' => '-',
                    'stts_kerja' => 'FT',
                    'npwp' => '-',
                    'pendidikan' => '-',
                    'gapok' => 0,
                    'tmp_lahir' => '-',
                    'tgl_lahir' => date('Y-m-d'),
                    'alamat' => '-',
                    'kota' => '-',
                    'mulai_kerja' => date('Y-m-d'),
                    'ms_kerja' => '<1',
                    'indexins' => '-',
                    'bpd' => '-',
                    'rekening' => '-',
                    'stts_aktif' => 'AKTIF',
                    'wajibmasuk' => 0,
                    'pengurang' => 0,
                    'indek' => 0,
                    'mulai_kontrak' => date('Y-m-d'),
                    'cuti_diambil' => 0,
                    'dankes' => 0,
                    'photo' => null,
                    'no_ktp' => '-'
                ]);
                $pegawai = DB::table('pegawai')->where('nik', $kd_dokter)->first();
            }

            $spesialisExists = DB::table('spesialis')->where('kd_sps', '-')->exists();
            $kd_sps = $spesialisExists ? '-' : (DB::table('spesialis')->value('kd_sps') ?: '-');

            DB::table('dokter')->insert([
                'kd_dokter' => $kd_dokter,
                'nm_dokter' => $pegawai ? $pegawai->nama : 'Dokter ' . $kd_dokter,
                'jk' => $pegawai && in_array($pegawai->jk, ['L', 'P']) ? $pegawai->jk : 'L',
                'tmp_lahir' => $pegawai ? $pegawai->tmp_lahir : '-',
                'tgl_lahir' => $pegawai ? $pegawai->tgl_lahir : date('Y-m-d'),
                'gol_drh' => 'A',
                'agama' => 'ISLAM',
                'almt_tgl' => $pegawai ? $pegawai->alamat : '-',
                'no_telp' => '0',
                'email' => '',
                'stts_nikah' => 'MENIKAH',
                'kd_sps' => $kd_sps,
                'alumni' => '',
                'no_ijn_praktek' => '-',
                'status' => '1'
            ]);
        }
    }
}
