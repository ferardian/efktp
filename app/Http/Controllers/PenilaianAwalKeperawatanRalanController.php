<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PenilaianAwalKeperawatanRalan;
use App\Models\PenilaianAwalKeperawatanRalanMasalah;
use App\Models\PenilaianAwalKeperawatanRalanRencana;
use App\Models\MasterMasalahKeperawatan;
use App\Models\MasterRencanaKeperawatan;

class PenilaianAwalKeperawatanRalanController extends Controller
{
    use Track;

    public function getMasterMasalahRencana(Request $request)
    {
        $masalah = MasterMasalahKeperawatan::orderBy('kode_masalah')->get();
        $rencana = MasterRencanaKeperawatan::orderBy('kode_rencana')->get();
        return response()->json([
            'masalah' => $masalah,
            'rencana' => $rencana,
        ]);
    }

    public function get(Request $request)
    {
        $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)
            ->with([
                'regPeriksa.pasien.bahasaPasien',
                'regPeriksa.pasien.cacatFisik',
                'pegawai',
                'petugas',
                'masalah.masterMasalah',
                'rencanaKeperawatan.masterRencana'
            ])->first();
        return response()->json($penilaian);
    }

    public function createPenilaian(Request $request)
    {
        $columns = Schema::getColumnListing('penilaian_awal_keperawatan_ralan');
        $data = array_intersect_key($request->all(), array_flip($columns));

        if (empty($data['no_rawat'])) {
            return response()->json([
                'message' => 'Gagal menyimpan: No. Rawat tidak ditemukan atau tidak boleh kosong.'
            ], 422);
        }

        if (empty($data['tanggal'])) {
            $data['tanggal'] = date('Y-m-d H:i:s');
        }

        $nip = !empty($data['nip']) ? $data['nip'] : (session()->get('pegawai')->nik ?? '-');
        $data['nip'] = $nip;
        $this->ensurePetugasExists($nip);

        // Safe defaults matching Khanza database schema
        $defaults = [
            'informasi' => 'Autoanamnesis',
            'td' => '-', 'nadi' => '-', 'rr' => '-', 'suhu' => '-', 'gcs' => '-',
            'bb' => '-', 'tb' => '-', 'bmi' => '-',
            'keluhan_utama' => '-', 'rpd' => '-', 'rpk' => '-', 'rpo' => '-', 'alergi' => '-',
            'alat_bantu' => 'Tidak', 'ket_bantu' => '-',
            'prothesa' => 'Tidak', 'ket_pro' => '-',
            'adl' => 'Mandiri',
            'status_psiko' => 'Tenang', 'ket_psiko' => '-',
            'hub_keluarga' => 'Baik',
            'tinggal_dengan' => 'Sendiri', 'ket_tinggal' => '-',
            'ekonomi' => 'Baik',
            'budaya' => 'Tidak Ada', 'ket_budaya' => '-',
            'edukasi' => 'Pasien', 'ket_edukasi' => '-',
            'berjalan_a' => 'Tidak', 'berjalan_b' => 'Tidak', 'berjalan_c' => 'Tidak',
            'hasil' => 'Tidak beresiko (tidak ditemukan a dan b)',
            'lapor' => 'Tidak', 'ket_lapor' => '-',
            'sg1' => 'Tidak', 'nilai1' => '0',
            'sg2' => 'Tidak', 'nilai2' => '0',
            'total_hasil' => '0',
            'nyeri' => 'Tidak Ada Nyeri',
            'provokes' => 'Lain-lain', 'ket_provokes' => '-',
            'quality' => 'Lain-lain', 'ket_quality' => '-',
            'lokasi' => '-', 'menyebar' => 'Tidak', 'skala_nyeri' => '0', 'durasi' => '-',
            'nyeri_hilang' => 'Istirahat', 'ket_nyeri' => '-',
            'pada_dokter' => 'Tidak', 'ket_dokter' => '-',
            'rencana' => '-',
        ];

        foreach ($defaults as $key => $defaultVal) {
            if (!isset($data[$key]) || $data[$key] === null || $data[$key] === '') {
                $data[$key] = $defaultVal;
            }
        }

        DB::beginTransaction();
        try {
            $existing = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)->first();
            if ($existing) {
                $existing->update($data);
                $penilaian = $existing;
                $this->updateSql(new PenilaianAwalKeperawatanRalan(), $data, ['no_rawat' => $request->no_rawat]);
            } else {
                $penilaian = PenilaianAwalKeperawatanRalan::create($data);
                $this->insertSql(new PenilaianAwalKeperawatanRalan(), $data);
            }

            // Sync masalah keperawatan (delete old, insert new)
            PenilaianAwalKeperawatanRalanMasalah::where('no_rawat', $request->no_rawat)->delete();
            if ($request->has('kode_masalah')) {
                $kodeMasalahList = is_array($request->kode_masalah) ? $request->kode_masalah : json_decode($request->kode_masalah, true);
                if (is_array($kodeMasalahList)) {
                    $masalahRows = [];
                    foreach ($kodeMasalahList as $km) {
                        if (!empty($km)) {
                            $masalahRows[] = [
                                'no_rawat' => $request->no_rawat,
                                'kode_masalah' => $km,
                            ];
                        }
                    }
                    if (!empty($masalahRows)) {
                        PenilaianAwalKeperawatanRalanMasalah::insert($masalahRows);
                    }
                }
            }

            // Sync rencana keperawatan (delete old, insert new)
            PenilaianAwalKeperawatanRalanRencana::where('no_rawat', $request->no_rawat)->delete();
            if ($request->has('kode_rencana')) {
                $kodeRencanaList = is_array($request->kode_rencana) ? $request->kode_rencana : json_decode($request->kode_rencana, true);
                if (is_array($kodeRencanaList)) {
                    $rencanaRows = [];
                    foreach ($kodeRencanaList as $kr) {
                        if (!empty($kr)) {
                            $rencanaRows[] = [
                                'no_rawat' => $request->no_rawat,
                                'kode_rencana' => $kr,
                            ];
                        }
                    }
                    if (!empty($rencanaRows)) {
                        PenilaianAwalKeperawatanRalanRencana::insert($rencanaRows);
                    }
                }
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
            PenilaianAwalKeperawatanRalanMasalah::where('no_rawat', $request->no_rawat)->delete();
            PenilaianAwalKeperawatanRalanRencana::where('no_rawat', $request->no_rawat)->delete();
            PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)->delete();
            DB::commit();
            return response()->json(['message' => 'Berhasil menghapus penilaian awal keperawatan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function print(Request $request)
    {
        $data = PenilaianAwalKeperawatanRalan::with([
            'regPeriksa.pasien.bahasaPasien',
            'regPeriksa.pasien.cacatFisik',
            'pegawai',
            'petugas',
            'masalah.masterMasalah',
            'rencanaKeperawatan.masterRencana'
        ])->where('no_rawat', $request->no_rawat)->first();
        $setting = Setting::first();

        $pdf = PDF::loadView('content.print.penilaianAwal', ['data' => $data, 'setting' => $setting])
            ->setPaper("a4")->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        return $pdf->stream('penilaian-awal-keperawatan.pdf');
    }

    /**
     * Automatic Petugas Syncing to satisfy DB constraints
     */
    private function ensurePetugasExists($nip)
    {
        if (empty($nip) || $nip === '-') {
            return;
        }

        $exists = DB::table('petugas')->where('nip', $nip)->exists();
        if (!$exists) {
            $pegawai = DB::table('pegawai')->where('nik', $nip)->first();
            if (!$pegawai) {
                DB::table('pegawai')->insert([
                    'id' => DB::table('pegawai')->max('id') + 1,
                    'nik' => $nip,
                    'nama' => 'Petugas ' . $nip,
                    'jk' => 'L',
                    'jbtn' => 'Perawat',
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
                    'photo' => '-',
                    'no_ktp' => '-'
                ]);
                $pegawai = DB::table('pegawai')->where('nik', $nip)->first();
            }

            if ($pegawai) {
                DB::table('petugas')->insert([
                    'nip' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                    'jk' => in_array($pegawai->jk, ['L', 'P']) ? $pegawai->jk : 'L',
                    'tmp_lahir' => $pegawai->tmp_lahir ?: '-',
                    'tgl_lahir' => $pegawai->tgl_lahir ?: date('Y-m-d'),
                    'gol_darah' => '-',
                    'agama' => '-',
                    'stts_nikah' => '-',
                    'alamat' => $pegawai->alamat ?: '-',
                    'kd_jbtn' => 'J001',
                    'no_telp' => '-',
                    'status' => '1'
                ]);
            }
        }
    }
}
