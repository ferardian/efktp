<?php

namespace App\Http\Controllers;

use App\Models\EfktpPcareRujukSubspesialis;
use App\Models\PcareRujukSubspesialis;
use App\Models\Setting;
use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PcareRujukSubspesialisController extends Controller
{
    use Track;
    private function parseDateToYmd(?string $dateStr): ?string
    {
        if (empty($dateStr) || $dateStr === '-') return date('Y-m-d');
        $clean = trim($dateStr);
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $clean, $m)) {
            return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        }
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $clean, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        return date('Y-m-d', strtotime($clean));
    }

    public function create(Request $request)
    {
        $tglDaftarRaw = $request->tglDaftar ?? $request->tgl_daftar;
        $tglEstRaw = $request->tglEstRujuk ?? $request->tglEstRujukan;
        $tglPulangRaw = $request->tglPulang;

        $data = [
            'no_rawat' => $request->no_rawat,
            'noKunjungan' => $request->noKunjungan,
            'tglDaftar' => $this->parseDateToYmd($tglDaftarRaw),
            'no_rkm_medis' => $request->no_rkm_medis,
            'nm_pasien' => $request->nm_pasien,
            'noKartu' => $request->noKartu ?? $request->no_peserta,
            'kdPoli' => $request->kdPoli ?? $request->kd_poli_pcare,
            'nmPoli' => $request->nmPoli ?? $request->nm_poli_pcare,
            'keluhan' => $request->keluhan,
            'kdSadar' => $request->kdSadar ?? $request->kesadaran,
            'nmSadar' => $request->nmSadar,
            "sistole" => ($request->tensi && $request->tensi != '-') ? (explode('/', $request->tensi)[0] ?? '0') : '0',
            "diastole" => ($request->tensi && $request->tensi != '-') ? (explode('/', $request->tensi)[1] ?? '0') : '0',
            'beratBadan' => $request->beratBadan ?? $request->berat ?? 0,
            'tinggiBadan' => $request->tinggiBadan ?? $request->tinggi ?? 0,
            'respRate' => $request->respRate ?? $request->respirasi ?? 0,
            'heartRate' => $request->heartRate ?? $request->nadi ?? 0,
            'lingkarPerut' => $request->lingkarPerut ?? $request->lingkar_perut ?? 0,
            'terapi' => $request->terapi ?? $request->rtl ?? '-',
            'kdStatusPulang' => $request->kdStatusPulang ?? $request->sttsPulang,
            'nmStatusPulang' => $request->nmStatusPulang,
            'tglPulang' => $this->parseDateToYmd($tglPulangRaw),
            'kdDokter' => $request->kdDokter ?? $request->kd_dokter_pcare,
            'nmDokter' => $request->nmDokter ?? $request->nm_dokter,
            'kdDiag1' => $request->kdDiag1 ?? $request->kdDiagnosa1,
            'nmDiag1' => $request->nmDiag1 ?? $request->diagnosa1,
            'kdDiag2' => $request->kdDiag2 ?? $request->kdDiagnosa2,
            'nmDiag2' => $request->nmDiag2 ?? $request->diagnosa2,
            'kdDiag3' => $request->kdDiag3 ?? $request->kdDiagnosa3,
            'nmDiag3' => $request->nmDiag3 ?? $request->diagnosa3,
            'tglEstRujuk' => $this->parseDateToYmd($tglEstRaw),
            'kdPPK' => $request->kdPPK ?? $request->kdPpkRujukan,
            'nmPPK' => $request->nmPPK ?? $request->ppkRujukan,
            'kdSubSpesialis' => $request->kdSubSpesialis,
            'nmSubSpesialis' => $request->nmSubSpesialis ?? $request->spesialis,
            'kdSarana' => $request->kdSarana,
            'nmSarana' => $request->nmSarana ?? $request->sarana,
            'kdTACC' => $request->kdTACC ?? $request->kdTacc,
            'nmTACC' => $request->nmTACC ?? $request->nmTacc,
            'alasanTACC' => $request->alasanTACC ?? $request->alasanTacc,
            'jadwal' => $request->jadwal ?? $request->jadwalRujuk,
        ];

        if (empty($data['noKunjungan']) && !empty($data['no_rawat'])) {
            $data['noKunjungan'] = \App\Models\PcareKunjungan::where('no_rawat', $data['no_rawat'])->value('noKunjungan');
        }

        try {
            $rujuk = PcareRujukSubspesialis::updateOrCreate(
                ['no_rawat' => $data['no_rawat']],
                $data
            );
            if ($rujuk) {
                $this->insertSql(new PcareRujukSubspesialis(), $data);
                if (!empty($data['noKunjungan'])) {
                    $setting = Setting::first();
                    $tglEstForAkhir = $data['tglEstRujuk'] ?? date('Y-m-d');
                    $tglAkhirRujukVal = !empty($request->tglAkhirRujuk) 
                        ? $this->parseDateToYmd($request->tglAkhirRujuk) 
                        : date('Y-m-d', strtotime('+89 days', strtotime($tglEstForAkhir)));

                    $dataEfktp = [
                        'noKunjungan' => $data['noKunjungan'],
                        'kdPpkAsal' => $request->kdPpkAsal,
                        'nmPpkAsal' => $request->nmPpkAsal ?? $setting?->nama_instansi ?? '-',
                        'kdKR' => $request->kdKR,
                        'nmKR' => $request->nmKR ?? $setting?->propinsi ?? '-',
                        'kdKC' => $request->kdKC,
                        'nmKC' => $request->nmKC ?? $setting?->kabupaten ?? '-',
                        'tglAkhirRujuk' => $tglAkhirRujukVal,
                        'jadwal' => $request->jadwal ? $request->jadwal : 'Setiap Hari Kerja',
                        'infoDenda' => $request->infoDenda ? $request->infoDenda : '-',
                        'catatanRujuk' => $request->catatanRujuk,
                    ];
                    try {
                        $rujukEfktp = EfktpPcareRujukSubspesialis::updateOrCreate(
                            ['noKunjungan' => $data['noKunjungan']],
                            $dataEfktp
                        );
                        if ($rujukEfktp) {
                            $this->insertSql(new EfktpPcareRujukSubspesialis(), $dataEfktp);
                        }
                    } catch (QueryException $e) {
                        // ignore secondary table failure
                    }
                }
            }
            return response()->json(['SUKSES'], 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function update(Request $request)
    {
        $tglDaftarRaw = $request->tglDaftar ?? $request->tgl_daftar;
        $tglEstRaw = $request->tglEstRujuk ?? $request->tglEstRujukan;
        $tglPulangRaw = $request->tglPulang;

        $data = [
            'no_rawat' => $request->no_rawat,
            'noKunjungan' => $request->noKunjungan,
            'tglDaftar' => $this->parseDateToYmd($tglDaftarRaw),
            'no_rkm_medis' => $request->no_rkm_medis,
            'nm_pasien' => $request->nm_pasien,
            'noKartu' => $request->noKartu ?? $request->no_peserta,
            'kdPoli' => $request->kdPoli ?? $request->kd_poli_pcare,
            'nmPoli' => $request->nmPoli ?? $request->nm_poli_pcare,
            'keluhan' => $request->keluhan,
            'kdSadar' => $request->kdSadar ?? $request->kesadaran,
            'nmSadar' => $request->nmSadar,
            "sistole" => ($request->tensi && $request->tensi != '-') ? (explode('/', $request->tensi)[0] ?? '0') : '0',
            "diastole" => ($request->tensi && $request->tensi != '-') ? (explode('/', $request->tensi)[1] ?? '0') : '0',
            'beratBadan' => $request->beratBadan ?? $request->berat ?? 0,
            'tinggiBadan' => $request->tinggiBadan ?? $request->tinggi ?? 0,
            'respRate' => $request->respRate ?? $request->respirasi ?? 0,
            'heartRate' => $request->heartRate ?? $request->nadi ?? 0,
            'lingkarPerut' => $request->lingkarPerut ?? $request->lingkar_perut ?? 0,
            'terapi' => $request->terapi ?? $request->rtl ?? '-',
            'kdStatusPulang' => $request->kdStatusPulang ?? $request->sttsPulang,
            'nmStatusPulang' => $request->nmStatusPulang,
            'tglPulang' => $this->parseDateToYmd($tglPulangRaw),
            'kdDokter' => $request->kdDokter ?? $request->kd_dokter_pcare,
            'nmDokter' => $request->nmDokter ?? $request->nm_dokter,
            'kdDiag1' => $request->kdDiag1 ?? $request->kdDiagnosa1,
            'nmDiag1' => $request->nmDiag1 ?? $request->diagnosa1,
            'kdDiag2' => $request->kdDiag2 ?? $request->kdDiagnosa2,
            'nmDiag2' => $request->nmDiag2 ?? $request->diagnosa2,
            'kdDiag3' => $request->kdDiag3 ?? $request->kdDiagnosa3,
            'nmDiag3' => $request->nmDiag3 ?? $request->diagnosa3,
            'tglEstRujuk' => $this->parseDateToYmd($tglEstRaw),
            'kdPPK' => $request->kdPPK ?? $request->kdPpkRujukan,
            'nmPPK' => $request->nmPPK ?? $request->ppkRujukan,
            'kdSubSpesialis' => $request->kdSubSpesialis,
            'nmSubSpesialis' => $request->nmSubSpesialis ?? $request->spesialis,
            'kdSarana' => $request->kdSarana,
            'nmSarana' => $request->nmSarana ?? $request->sarana,
            'kdTACC' => $request->kdTACC ?? $request->kdTacc,
            'nmTACC' => $request->nmTACC ?? $request->nmTacc,
            'alasanTACC' => $request->alasanTACC ?? $request->alasanTacc,
            'jadwal' => $request->jadwal ?? $request->jadwalRujuk,
        ];

        if (empty($data['noKunjungan']) && !empty($data['no_rawat'])) {
            $data['noKunjungan'] = \App\Models\PcareKunjungan::where('no_rawat', $data['no_rawat'])->value('noKunjungan');
        }

        try {
            $rujuk = PcareRujukSubspesialis::updateOrCreate(
                ['no_rawat' => $data['no_rawat']],
                $data
            );

            if ($rujuk) {
                $this->updateSql(new PcareRujukSubspesialis(), $data, ['no_rawat' => $data['no_rawat']]);

                if (!empty($data['noKunjungan'])) {
                    $setting = Setting::first();
                    $tglEstForAkhir = $data['tglEstRujuk'] ?? date('Y-m-d');
                    $tglAkhirRujukVal = !empty($request->tglAkhirRujuk) 
                        ? $this->parseDateToYmd($request->tglAkhirRujuk) 
                        : date('Y-m-d', strtotime('+89 days', strtotime($tglEstForAkhir)));

                    $dataEfktp = [
                        'noKunjungan' => $data['noKunjungan'],
                        'kdPpkAsal' => $request->kdPpkAsal,
                        'nmPpkAsal' => $request->nmPpkAsal ?? $setting?->nama_instansi ?? '-',
                        'kdKR' => $request->kdKR,
                        'nmKR' => $request->nmKR ?? $setting?->propinsi ?? '-',
                        'kdKC' => $request->kdKC,
                        'nmKC' => $request->nmKC ?? $setting?->kabupaten ?? '-',
                        'tglAkhirRujuk' => $tglAkhirRujukVal,
                        'jadwal' => $request->jadwal ? $request->jadwal : 'Setiap Hari Kerja',
                        'infoDenda' => $request->infoDenda ? $request->infoDenda : '-',
                        'catatanRujuk' => $request->catatanRujuk,
                    ];
                    try {
                        EfktpPcareRujukSubspesialis::updateOrCreate(
                            ['noKunjungan' => $data['noKunjungan']],
                            $dataEfktp
                        );
                        $this->updateSql(new EfktpPcareRujukSubspesialis(), $dataEfktp, ['noKunjungan' => $data['noKunjungan']]);
                    } catch (QueryException $e) {
                        // ignore secondary table failure
                    }
                }
            }
            return response()->json(['SUKSES'], 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function print(Request $request)
    {
        $noKunjungan = $request->noKunjungan;
        if (!$noKunjungan) {
            return response('No Kunjungan tidak valid', 400);
        }

        $pcareModel = PcareRujukSubspesialis::where('noKunjungan', $noKunjungan)
            ->with(['detail', 'pasien', 'regPeriksa'])
            ->first();

        if (!$pcareModel) {
            $kunjungan = \App\Models\PcareKunjungan::where('noKunjungan', $noKunjungan)->first();
            if ($kunjungan) {
                $pcareModel = PcareRujukSubspesialis::where('no_rawat', $kunjungan->no_rawat)
                    ->with(['detail', 'pasien', 'regPeriksa'])
                    ->first();
            }
        }

        if (!$pcareModel) {
            return response('<div style="text-align:center;padding:50px;font-family:sans-serif;">
                <h3 style="color:#d33;">Data Rujukan Tidak Ditemukan</h3>
                <p>Data rujukan lokal untuk No. Kunjungan <b>' . htmlspecialchars($noKunjungan) . '</b> belum tersimpan di database.</p>
                <p>Silakan buka menu <b>CPPT / Edit Kunjungan</b> pasien dan klik <b>Ubah Kunjungan</b> untuk memperbarui data rujukan.</p>
            </div>', 404);
        }

        $pcare = $pcareModel->toArray();
        $setting = Setting::first();

        if ($request->size == '8') {
            $pdf = PDF::loadView('content.print.rujukanVertikal8', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper([0, 0, $request->size * 28.3465, 600])->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);
        } else if ($request->size == 'a4') {
            $pdf = PDF::loadView('content.print.rujukanVertikalA4', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);
        } else {
            $pdf = PDF::loadView('content.print.rujukanVertikal', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper('a5', 'landscape')->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);
        }
        return $pdf->stream($pcare['noKunjungan'] . '.pdf');
    }

    public function delete($noKunjungan)
    {

        $kunjungan = PcareRujukSubspesialis::where('noKunjungan', $noKunjungan);
        $kunjunganDetail = EfktpPcareRujukSubspesialis::where('noKunjungan', $noKunjungan);
        // return [$kunjungan->first(), $kunjunganDetail->first()];
        try {
            if ($kunjungan) {
                $delete = $kunjungan->delete();
                if ($delete) {
                    if ($kunjunganDetail) {
                        $deleteDetail = $kunjungan->delete();
                        if ($deleteDetail) {
                            $this->deleteSql(new EfktpPcareRujukSubspesialis(), ['noKunjungan' => $noKunjungan]);
                        }
                    }
                    $this->deleteSql(new PcareRujukSubspesialis(), ['noKunjungan' => $noKunjungan]);
                }
                return response()->json('SUKSES', 200);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function getAll($no_rkm_medis)
    {
        $kunjungan = PcareRujukSubspesialis::where('no_rkm_medis', $no_rkm_medis)->with('detail')->orderBy('tglEstRujuk', 'ASC')->get();
        return response()->json($kunjungan);
    }
}
