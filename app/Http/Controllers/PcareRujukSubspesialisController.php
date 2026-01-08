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
    public function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'noKunjungan' => $request->noKunjungan,
            'tglDaftar' => date('Y-m-d', strtotime($request->tgl_daftar)),
            'no_rkm_medis' => $request->no_rkm_medis,
            'nm_pasien' => $request->nm_pasien,
            'noKartu' => $request->noKartu,
            'kdPoli' => $request->kdPoli,
            'nmPoli' => $request->nmPoli,
            'keluhan' => $request->keluhan,
            'kdSadar' => $request->kesadaran,
            'nmSadar' => $request->nmSadar,
            "sistole" => $request->tensi != '-' ? explode('/', $request->tensi)[0] : '0',
            "diastole" => $request->tensi != '-' ? explode('/', $request->tensi)[1] : '0',
            'beratBadan' => $request->berat,
            'tinggiBadan' => $request->tinggi,
            'respRate' => $request->respirasi,
            'heartRate' => $request->nadi,
            'lingkarPerut' => $request->lingkar_perut,
            'terapi' => $request->terapi,
            'kdStatusPulang' => $request->kdStatusPulang,
            'nmStatusPulang' => $request->nmStatusPulang,
            'tglPulang' => $request->tglPulang,
            'kdDokter' => $request->kdDokter,
            'nmDokter' => $request->nmDokter,
            'kdDiag1' => $request->kdDiag1,
            'nmDiag1' => $request->nmDiag1,
            'kdDiag2' => $request->kdDiag2,
            'nmDiag2' => $request->nmDiag2,
            'kdDiag3' => $request->kdDiag3,
            'nmDiag3' => $request->nmDiag3,
            'tglEstRujuk' => $request->tglEstRujuk,
            'kdPPK' => $request->kdPPK,
            'nmPPK' => $request->nmPPK,
            'kdSubSpesialis' => $request->kdSubSpesialis,
            'nmSubSpesialis' => $request->nmSubSpesialis,
            'kdSarana' => $request->kdSarana,
            'nmSarana' => $request->nmSarana,
            'kdTACC' => $request->kdTACC,
            'nmTACC' => $request->nmTACC,
            'alasanTACC' => $request->alasanTACC,
        ];

        try {
            $rujuk = PcareRujukSubspesialis::create($data);
            if ($rujuk) {
                $this->insertSql(new PcareRujukSubspesialis(), $data);
                $dataEfktp = [
                    'noKunjungan' => $data['noKunjungan'],
                    'kdPpkAsal' => $request->kdPpkAsal,
                    'nmPpkAsal' => $request->nmPpkAsal,
                    'kdKR' => $request->kdKR,
                    'nmKR' => $request->nmKR,
                    'kdKC' => $request->kdKC,
                    'nmKC' => $request->nmKC,
                    'tglAkhirRujuk' => $request->tglAkhirRujuk,
                    'jadwal' => $request->jadwal,
                    'infoDenda' => $request->infoDenda ? $request->indoDenda : '-',
                    'catatanRujuk' => $request->catatanRujuk,
                ];
                try {
                    $rujukEfktp = EfktpPcareRujukSubspesialis::create($dataEfktp);
                    if ($rujukEfktp) {
                        $this->insertSql(new EfktpPcareRujukSubspesialis(), $dataEfktp);
                    }
                    $response = response()->json('SUKSES', 201);
                } catch (QueryException $e) {
                    return response()->json($e->errorInfo, 500);
                }
            }
            return response()->json(['SUKSES', $response], 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function update(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'noKunjungan' => $request->noKunjungan,
            'tglDaftar' => date('Y-m-d', strtotime($request->tgl_daftar)),
            'no_rkm_medis' => $request->no_rkm_medis,
            'nm_pasien' => $request->nm_pasien,
            'noKartu' => $request->noKartu,
            'kdPoli' => $request->kdPoli,
            'nmPoli' => $request->nmPoli,
            'keluhan' => $request->keluhan,
            'kdSadar' => $request->kesadaran,
            'nmSadar' => $request->nmSadar,
            "sistole" => $request->tensi != '-' ? explode('/', $request->tensi)[0] : '0',
            "diastole" => $request->tensi != '-' ? explode('/', $request->tensi)[1] : '0',
            'beratBadan' => $request->berat,
            'tinggiBadan' => $request->tinggi,
            'respRate' => $request->respirasi,
            'heartRate' => $request->nadi,
            'lingkarPerut' => $request->lingkar_perut,
            'terapi' => $request->terapi,
            'kdStatusPulang' => $request->kdStatusPulang,
            'nmStatusPulang' => $request->nmStatusPulang,
            'tglPulang' => $request->tglPulang,
            'kdDokter' => $request->kdDokter,
            'nmDokter' => $request->nmDokter,
            'kdDiag1' => $request->kdDiag1,
            'nmDiag1' => $request->nmDiag1,
            'kdDiag2' => $request->kdDiag2,
            'nmDiag2' => $request->nmDiag2,
            'kdDiag3' => $request->kdDiag3,
            'nmDiag3' => $request->nmDiag3,
            'tglEstRujuk' => $request->tglEstRujuk,
            'kdPPK' => $request->kdPPK,
            'nmPPK' => $request->nmPPK,
            'kdSubSpesialis' => $request->kdSubSpesialis,
            'nmSubSpesialis' => $request->nmSubSpesialis,
            'kdSarana' => $request->kdSarana,
            'nmSarana' => $request->nmSarana,
            'kdTACC' => $request->kdTACC,
            'nmTACC' => $request->nmTACC,
            'alasanTACC' => $request->alasanTACC,
        ];

        $rujuk = PcareRujukSubspesialis::where('noKunjungan', $data['noKunjungan']);

        if ($rujuk) {
            try {
                $update = $rujuk->update($data);
                if ($update) {
                    $this->insertSql(new PcareRujukSubspesialis(), $data);
                    $dataEfktp = [
                        'noKunjungan' => $data['noKunjungan'],
                        'kdPpkAsal' => $request->kdPpkAsal,
                        'nmPpkAsal' => $request->nmPpkAsal,
                        'kdKR' => $request->kdKR,
                        'nmKR' => $request->nmKR,
                        'kdKC' => $request->kdKC,
                        'nmKC' => $request->nmKC,
                        'tglAkhirRujuk' => $request->tglAkhirRujuk,
                        'jadwal' => $request->jadwal ? $request->jadwal : '-',
                        'infoDenda' => $request->infoDenda ? $request->indoDenda : '-',
                        'catatanRujuk' => $request->catatanRujuk,
                    ];
                    try {
                        $rujukEfktp = EfktpPcareRujukSubspesialis::where(['noKunjungan' => $data['noKunjungan']])->update($dataEfktp);
                        if ($rujukEfktp) {
                            $this->updateSql(new EfktpPcareRujukSubspesialis(), $dataEfktp, ['noKunjungan' => $data['noKunjungan']]);
                        }
                        $response = response()->json('SUKSES', 200);
                    } catch (QueryException $e) {
                        return response()->json($e->errorInfo, 500);
                    }
                }
                return response()->json(['SUKSES', $response], 200);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }

    public function print(Request $request)
    {
        $key = [
            'noKunjungan' => $request->noKunjungan,
        ];
        $pcare = PcareRujukSubspesialis::where($key)->with('detail', 'pasien', 'regPeriksa')->first()->toArray();
        $setting = Setting::first();

        if ($request->size == '8') {
            $pdf = PDF::loadView('content.print.rujukanVertikal8', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper([0, 0, $request->size * 28.3465, 600])->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        } else if ($request->size == 'a4') {
            $pdf = PDF::loadView('content.print.rujukanVertikalA4', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        } else {
            $pdf = PDF::loadView('content.print.rujukanVertikal', ['data' => $pcare, 'setting' => $setting]);
            $pdf->setPaper('a5', 'landscape')->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        }
        return $pdf->stream($pcare['noKunjungan']);
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
