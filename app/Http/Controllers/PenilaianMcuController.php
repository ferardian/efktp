<?php

namespace App\Http\Controllers;

use App\Models\PenilaianMcu;
use App\Models\Setting;
use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianMcuController extends Controller
{
    use Track;

    public $mcu;

    public function __construct()
    {
        $this->mcu = new PenilaianMcu();
    }

    public function get(Request $req)
    {
        $mcu = $this->mcu->where('no_rawat', $req->no_rawat)->first();
        return response()->json($mcu);
    }

    public function create(Request $req)
    {
        $defaults = [
            'tanggal' => date('Y-m-d H:i:s'),
            'informasi' => 'Autoanamnesis',
            'rps' => '-',
            'rpk' => '-',
            'rpd' => '-',
            'alergi' => '-',
            'keadaan' => 'Baik',
            'kesadaran' => 'Composmentis',
            'td' => '-',
            'nadi' => '-',
            'rr' => '-',
            'tb' => '-',
            'bb' => '-',
            'suhu' => '-',
            'bmi' => '-',
            'kasifikasi_bmi' => 'Berat Badan Normal',
            'lingkar_pinggang' => '-',
            'risiko_lingkar_pinggang' => 'Rendah',
            'submandibula' => '-',
            'axilla' => '-',
            'supraklavikula' => '-',
            'leher' => '-',
            'inguinal' => '-',
            'oedema' => '-',
            'sinus_frontalis' => '=',
            'sinus_maxilaris' => '-',
            'rambut' => '-',
            'palpebra' => '-',
            'sklera' => '-',
            'cornea' => '-',
            'buta_warna' => '-',
            'konjungtiva' => '-',
            'lensa' => '-',
            'pupil' => '-',
            'menggunakan_kacamata' => '-',
            'visus' => '-',
            'luas_lapang_pandang' => '-',
            'keterangan_luas_lapang_pandang' => '-',
            'lubang_telinga' => '-',
            'daun_telinga' => '-',
            'selaput_pendengaran' => '-',
            'proc_mastoideus' => '-',
            'septum_nasi' => '-',
            'lubang_hidung' => '-',
            'sinus' => '-',
            'bibir' => 'Lembab',
            'gusi' => '-',
            'gigi' => '-',
            'caries' => '-',
            'lidah' => '-',
            'faring' => '-',
            'tonsil' => '-',
            'kelenjar_limfe' => '-',
            'kelenjar_gondok' => '-',
            'gerakan_dada' => '-',
            'vocal_femitus' => '-',
            'perkusi_dada' => '-',
            'bunyi_napas' => '-',
            'bunyi_tambahan' => '-',
            'ictus_cordis' => '-',
            'bunyi_jantung' => '-',
            'batas' => '-',
            'mamae' => '-',
            'keterangan_mamae' => '-',
            'inspeksi' => '-',
            'palpasi' => '-',
            'hepar' => '-',
            'perkusi_abdomen' => '-',
            'auskultasi' => '-',
            'limpa' => '-',
            'costovertebral' => '-',
            'scoliosis' => '-',
            'kondisi_kulit' => '-',
            'penyakit_kulit' => '-',
            'ekstrimitas_atas' => '-',
            'ekstrimitas_atas_ket' => '-',
            'ekstrimitas_bawah' => '-',
            'ekstrimitas_bawah_ket' => '-',
            'area_genitalia' => '-',
            'keterangan_area_genitalia' => '-',
            'anus_perianal' => '-',
            'keterangan_anus_perianal' => '-',
            'laborat' => '-',
            'radiologi' => '-',
            'ekg' => '-',
            'spirometri' => '-',
            'audiometri' => '-',
            'treadmill' => '-',
            'romberg_test' => '-',
            'back_strength' => '-',
            'abi_tangan_kanan' => '-',
            'abi_tangan_kiri' => '-',
            'abi_kaki_kanan' => '-',
            'abi_kaki_kiri' => '-',
            'lainlain' => '-',
            'merokok' => '-',
            'alkohol' => '-',
            'kesimpulan' => '-',
            'anjuran' => '-',
        ];

        // Merge request values into defaults if they exist and are not empty
        $data = [
            'no_rawat' => $req->no_rawat,
            'kd_dokter' => $req->kd_dokter ?? (session()->get('pegawai')->nik ?? ''),
        ];

        foreach ($defaults as $key => $defaultVal) {
            if ($req->has($key) && $req->input($key) !== null && $req->input($key) !== '') {
                $data[$key] = $req->input($key);
            } else {
                $data[$key] = $defaultVal;
            }
        }

        // Check if record exists
        $find = PenilaianMcu::where('no_rawat', $req->no_rawat)->first();
        if ($find) {
            // Remove tanggal to keep the original date/time, or update it?
            // Usually, SIMKES Khanza keeps the original insert time or updates it. We'll update it to keep track.
            $keys = ['no_rawat' => $req->no_rawat];
            try {
                $this->mcu->where($keys)->update($data);
                $this->updateSql(new PenilaianMcu(), $data, $keys);
                return response()->json('SUKSES', 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 400);
            }
        }

        try {
            $mcu = $this->mcu->create($data);
            if ($mcu) {
                $this->insertSql(new PenilaianMcu(), $data);
                return response()->json('SUKSES', 201);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 400);
        }
    }

    public function getPenunjang(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $type = $request->type; // 'lab' or 'radiologi'

        if ($type === 'lab') {
            $periksa = \App\Models\Lab\PeriksaLab::where('no_rawat', $no_rawat)
                ->with(['jenis', 'detail.template'])
                ->get();

            $formatted = "";
            if ($periksa->isNotEmpty()) {
                foreach ($periksa as $p) {
                    $formatted .= "=== " . ($p->jenis->nm_perawatan ?? 'Laboratorium') . " ===\n";
                    if ($p->detail) {
                        foreach ($p->detail as $d) {
                            $name = $d->template->nama ?? '-';
                            $value = $d->nilai ?? '-';
                            $unit = $d->template->satuan ?? '';
                            $ref = $d->nilai_rujukan ?? '';
                            $ket = $d->keterangan ?? '';

                            $formatted .= "- {$name}: {$value} {$unit} (Rujukan: {$ref})";
                            if ($ket) {
                                $formatted .= " [Ket: {$ket}]";
                            }
                            $formatted .= "\n";
                        }
                    }
                    $formatted .= "\n";
                }
            } else {
                $formatted = "Tidak ada hasil laboratorium.";
            }

            return response()->json(['result' => trim($formatted)]);
        } elseif ($type === 'radiologi') {
            $rad = DB::table('periksa_radiologi')
                ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
                ->leftJoin('hasil_radiologi', function ($join) {
                    $join->on('periksa_radiologi.no_rawat', '=', 'hasil_radiologi.no_rawat')
                         ->on('periksa_radiologi.tgl_periksa', '=', 'hasil_radiologi.tgl_periksa')
                         ->on('periksa_radiologi.jam', '=', 'hasil_radiologi.jam');
                })
                ->where('periksa_radiologi.no_rawat', $no_rawat)
                ->select('jns_perawatan_radiologi.nm_perawatan', 'hasil_radiologi.hasil')
                ->get();

            $formatted = "";
            if ($rad->isNotEmpty()) {
                foreach ($rad as $r) {
                    $formatted .= "=== " . $r->nm_perawatan . " ===\n";
                    $formatted .= ($r->hasil ?? 'Hasil belum di-input.') . "\n\n";
                }
            } else {
                $formatted = "Tidak ada hasil radiologi.";
            }

            return response()->json(['result' => trim($formatted)]);
        }

        return response()->json(['result' => '']);
    }

    public function delete(Request $req)
    {
        $no_rawat = $req->no_rawat;
        if (!$no_rawat) {
            return response()->json('No. Rawat tidak valid', 400);
        }

        try {
            $deleted = PenilaianMcu::where('no_rawat', $no_rawat)->delete();
            if ($deleted) {
                $this->deleteSql(new PenilaianMcu(), ['no_rawat' => $no_rawat]);
                return response()->json('SUKSES');
            } else {
                return response()->json('Data MCU tidak ditemukan', 404);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function print(Request $req)
    {
        $no_rawat = $req->no_rawat;
        if (!$no_rawat) {
            return redirect()->back()->with('error', 'No. Rawat tidak valid');
        }

        $mcu = PenilaianMcu::where('no_rawat', $no_rawat)
            ->with([
                'regPeriksa.pasien' => function ($query) {
                    $query->with(['kel', 'kec', 'kab', 'prop']);
                },
                'dokter'
            ])
            ->first();

        if (!$mcu) {
            return redirect()->back()->with('error', 'Data MCU tidak ditemukan');
        }

        $setting = Setting::first();

        $pdf = Pdf::loadView('content.print.mcu', [
            'data' => $mcu,
            'setting' => $setting
        ])
        ->setPaper('A4', 'portrait')
        ->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);

        return $pdf->stream('mcu_' . str_replace('/', '_', $no_rawat) . '.pdf');
    }
}
