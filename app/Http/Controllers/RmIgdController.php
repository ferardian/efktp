<?php

namespace App\Http\Controllers;

use App\Models\TriaseIgd;
use App\Models\TriaseIgdPrimer;
use App\Models\TriaseIgdSekunder;
use App\Models\TriaseIgdDetailSkala1;
use App\Models\TriaseIgdDetailSkala2;
use App\Models\TriaseIgdDetailSkala3;
use App\Models\TriaseIgdDetailSkala4;
use App\Models\TriaseIgdDetailSkala5;
use App\Models\PenilaianMedisIgd;
use App\Models\MasterTriasePemeriksaan;
use App\Models\MasterTriaseSkala1;
use App\Models\MasterTriaseSkala2;
use App\Models\MasterTriaseSkala3;
use App\Models\MasterTriaseSkala4;
use App\Models\MasterTriaseSkala5;
use App\Models\MasterTriaseMacamKasus;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RmIgdController extends Controller
{
    use Track;

    public function get(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $data = [
            'triase' => TriaseIgd::where('no_rawat', $no_rawat)->with(['primer', 'sekunder'])->first(),
            'medis' => PenilaianMedisIgd::where('no_rawat', $no_rawat)->with('dokter')->first(),
            'master_pemeriksaan' => MasterTriasePemeriksaan::all(),
            'master_kasus' => MasterTriaseMacamKasus::all(),
        ];
        
        // Fetch master scales and transaction scales
        for ($i = 1; $i <= 5; $i++) {
            $masterModel = "App\\Models\\MasterTriaseSkala$i";
            $detailModel = "App\\Models\\TriaseIgdDetailSkala$i";
            $data["master_skala$i"] = $masterModel::all();
            $data["skala$i"] = $detailModel::where('no_rawat', $no_rawat)->get();
        }

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kd_dokter' => 'required',
            'kode_kasus' => 'required',
        ], [
            'no_rawat.required' => 'Nomor Rawat tidak boleh kosong.',
            'kd_dokter.required' => 'Dokter belum dipilih.',
            'kode_kasus.required' => 'Macam Kasus belum dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $no_rawat = $request->no_rawat;
            $nik = session()->get('pegawai')->nik;
            $kd_dokter = $request->kd_dokter; // This should come from form

            // 1. Data Triase IGD (Base)
            try {
                $triase = TriaseIgd::updateOrCreate(
                    ['no_rawat' => $no_rawat],
                    [
                        'tgl_kunjungan' => $request->tgl_kunjungan ?? date('Y-m-d H:i:s'),
                        'kode_kasus' => $request->kode_kasus ?? '004',
                        'tekanan_darah' => $request->td ?? '-',
                        'nadi' => $request->nadi ?? '-',
                        'pernapasan' => $request->rr ?? '-',
                        'suhu' => $request->suhu ?? '-',
                        'saturasi_o2' => $request->spo2 ?? '-',
                        'cara_masuk' => 'Jalan',
                        'alat_transportasi' => 'Sendiri',
                        'alasan_kedatangan' => 'Datang Sendiri',
                        'keterangan_kedatangan' => '-',
                        'nyeri' => '-',
                    ]
                );
            } catch (\Exception $e) {
                throw new \Exception('Error di Tabel TriaseIgd: ' . $e->getMessage());
            }

            try {
                TriaseIgdPrimer::updateOrCreate(
                    ['no_rawat' => $no_rawat],
                    [
                        'tanggaltriase' => date('Y-m-d H:i:s'),
                        'keluhan_utama' => $request->keluhan_utama_triase ?? '-',
                        'nik' => $nik,
                        'kebutuhan_khusus' => '-',
                        'catatan' => '-',
                        'plan' => 'Ruang Resusitasi',
                    ]
                );
                TriaseIgdSekunder::updateOrCreate(
                    ['no_rawat' => $no_rawat],
                    [
                        'tanggaltriase' => date('Y-m-d H:i:s'),
                        'nik' => $nik,
                        'anamnesa_singkat' => '-',
                        'catatan' => '-',
                        'plan' => 'Zona Hijau',
                    ]
                );
            } catch (\Exception $e) {
                throw new \Exception('Error di Tabel TriasePrimer/Sekunder: ' . $e->getMessage());
            }

            // 4. Scales (Details)
            for ($i = 1; $i <= 5; $i++) {
                $key = "skala$i";
                $modelName = "App\\Models\\TriaseIgdDetailSkala$i";
                
                // Always clear existing for this rawat if we are updating triase
                $modelName::where('no_rawat', $no_rawat)->delete();
                
                if ($request->has($key) && is_array($request->$key)) {
                    foreach ($request->$key as $skala) {
                        $modelName::create([
                            'no_rawat' => $no_rawat,
                            "kode_skala$i" => $skala
                        ]);
                    }
                }
            }

            try {
                PenilaianMedisIgd::updateOrCreate(
                    ['no_rawat' => $no_rawat],
                    [
                        'tanggal' => date('Y-m-d H:i:s'),
                        'kd_dokter' => $kd_dokter,
                        'anamnesis' => $request->anamnesis ?? 'Autoanamnesis',
                        'hubungan' => $request->hubungan ?? '-',
                        'keluhan_utama' => $request->keluhan_utama ?? '-',
                        'rps' => $request->rps ?? '-',
                        'rpd' => $request->rpd ?? '-',
                        'rpk' => $request->rpk ?? '-',
                        'rpo' => $request->rpo ?? '-',
                        'alergi' => $request->alergi ?? '-',
                        'keadaan' => 'Sakit Ringan', 
                        'gcs' => $request->gcs ?? '-',
                        'kesadaran' => $request->kesadaran ?? 'Compos Mentis',
                        'td' => $request->td ?? '-',
                        'nadi' => $request->nadi ?? '-',
                        'rr' => $request->rr ?? '-',
                        'suhu' => $request->suhu ?? '-',
                        'spo' => $request->spo2 ?? '-',
                        'bb' => $request->bb ?? '-',
                        'tb' => $request->tb ?? '-',
                        'kepala' => 'Normal',
                        'mata' => 'Normal',
                        'gigi' => 'Normal',
                        'leher' => 'Normal',
                        'thoraks' => 'Normal',
                        'abdomen' => 'Normal',
                        'genital' => 'Normal',
                        'ekstremitas' => 'Normal',
                        'ket_fisik' => $request->ket_fisik ?? '-',
                        'ket_lokalis' => $request->ket_lokalis ?? '-',
                        'ekg' => $request->ekg ?? '-',
                        'rad' => $request->rad ?? '-',
                        'lab' => $request->lab ?? '-',
                        'diagnosis' => $request->diagnosis ?? '-',
                        'tata' => $request->tata ?? '-',
                    ]
                );
            } catch (\Exception $e) {
                throw new \Exception('Error di Tabel PenilaianMedisIgd: ' . $e->getMessage());
            }

            // 6. Sync to pemeriksaan_ralan (SOAP)
            $soapData = [
                'suhu_tubuh' => $request->suhu ?? '-',
                'tensi' => $request->td ?? '-',
                'nadi' => $request->nadi ?? '-',
                'respirasi' => $request->rr ?? '-',
                'tinggi' => $request->tb ?? '-',
                'berat' => $request->bb ?? '-',
                'spo2' => $request->spo2 ?? '-',
                'gcs' => $request->gcs ?? '-',
                'kesadaran' => $request->kesadaran ?? 'Compos Mentis',
                'keluhan' => $request->keluhan_utama ?? '-',
                'pemeriksaan' => $request->ket_fisik ?? '-',
                'alergi' => $request->alergi ?? '-',
                'lingkar_perut' => '-',
                'rtl' => $request->tata ?? '-',
                'penilaian' => $request->diagnosis ?? '-',
                'instruksi' => '-',
                'evaluasi' => '-',
            ];

            try {
                $soap = \App\Models\PemeriksaanRalan::where('no_rawat', $no_rawat)
                    ->where('nip', $kd_dokter)
                    ->where('tgl_perawatan', date('Y-m-d'))
                    ->first();

                if ($soap) {
                    \App\Models\PemeriksaanRalan::where('no_rawat', $no_rawat)
                        ->where('nip', $kd_dokter)
                        ->where('tgl_perawatan', $soap->tgl_perawatan)
                        ->where('jam_rawat', $soap->jam_rawat)
                        ->update($soapData);
                } else {
                    $soapData['no_rawat'] = $no_rawat;
                    $soapData['nip'] = $kd_dokter;
                    $soapData['tgl_perawatan'] = date('Y-m-d');
                    $soapData['jam_rawat'] = date('H:i:s');
                    \App\Models\PemeriksaanRalan::create($soapData);
                }
            } catch (\Exception $e) {
                throw new \Exception('Error di Tabel PemeriksaanRalan (SOAP): ' . $e->getMessage());
            }

            DB::commit();
            return response()->json('Data Berhasil Disimpan', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json('Gagal menyimpan ke database. Pastikan semua data wajib sudah terisi dengan benar. Error: ' . $e->getCode(), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('Terjadi kesalahan sistem: ' . $e->getMessage(), 500);
        }
    }
}
