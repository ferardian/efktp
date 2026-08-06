<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\PCare\PCareKelompok;
use App\Models\PcareKegiatanKelompok;
use App\Models\PcarePesertaKegiatanKelompok;
use App\Models\PcareClubProlanis;
use App\Traits\Track;
use Illuminate\Support\Carbon;

class Kelompok extends Controller
{
    use Track;

    protected PCareKelompok $service;

    public function __construct()
    {
        $this->service = new PCareKelompok();
    }

    public function index()
    {
        return view('content.pcare.kelompok');
    }

    /**
     * Get Club Prolanis BPJS PCare (01: Diabetes Melitus, 02: Hipertensi)
     */
    public function getClub(Request $request, $kdProgram = '01')
    {
        $res = $this->service->getClub($kdProgram);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if (($code == 200 || $code == 201) && !empty($res['response']['list'])) {
            foreach ($res['response']['list'] as $item) {
                try {
                    PcareClubProlanis::updateOrCreate(
                        ['clubId' => $item['clubId']],
                        [
                            'kdProgram'  => $item['jnsKelompok']['kdProgram'] ?? $kdProgram,
                            'nmProgram'  => $item['jnsKelompok']['nmProgram'] ?? '',
                            'nama'       => $item['nama'] ?? '',
                            'alamat'     => $item['alamat'] ?? '',
                            'ketua_nama' => $item['ketua_nama'] ?? '',
                            'ketua_noHP' => $item['ketua_noHP'] ?? '',
                            'tglMulai'   => !empty($item['tglMulai']) ? Carbon::createFromFormat('d-m-Y', $item['tglMulai'])->format('Y-m-d') : null,
                            'tglAkhir'   => !empty($item['tglAkhir']) ? Carbon::createFromFormat('d-m-Y', $item['tglAkhir'])->format('Y-m-d') : null,
                        ]
                    );
                } catch (\Throwable $e) {
                    // Ignore duplicate sync issues
                }
            }
        }

        return response()->json($res);
    }

    /**
     * Get Kegiatan Kelompok BPJS PCare (bulan format dd-mm-yyyy)
     */
    public function getKegiatan(Request $request, $bulan = null)
    {
        $bulanFormatted = $bulan ?: date('d-m-Y');
        $res = $this->service->getKegiatan($bulanFormatted);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if (($code == 200 || $code == 201) && !empty($res['response']['list'])) {
            foreach ($res['response']['list'] as $item) {
                try {
                    $tglPelayanan = !empty($item['tglPelayanan'])
                        ? Carbon::createFromFormat('d-m-Y', $item['tglPelayanan'])->format('Y-m-d')
                        : date('Y-m-d');

                    PcareKegiatanKelompok::updateOrCreate(
                        ['eduId' => $item['eduId']],
                        [
                            'clubId'       => $item['clubProl']['clubId'] ?? 0,
                            'namaClub'     => $item['clubProl']['nama'] ?? '',
                            'tglPelayanan' => $tglPelayanan,
                            'nmKegiatan'   => $item['kegiatan']['nama'] ?? '',
                            'nmKelompok'   => $item['kelompok']['nama'] ?? '',
                            'materi'       => $item['materi'] ?? '',
                            'pembicara'    => $item['pembicara'] ?? '',
                            'lokasi'       => $item['lokasi'] ?? '',
                            'keterangan'   => $item['keterangan'] ?? '',
                            'biaya'        => $item['biaya'] ?? 0,
                        ]
                    );
                } catch (\Throwable $e) {
                    // Ignore duplicate sync issues
                }
            }
        }

        return response()->json($res);
    }

    /**
     * Get Peserta Kegiatan Kelompok berdasarkan eduId
     */
    public function getPeserta(Request $request, $eduId)
    {
        $res = $this->service->getPeserta($eduId);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if (($code == 200 || $code == 201) && !empty($res['response']['list'])) {
            foreach ($res['response']['list'] as $item) {
                $pst = $item['peserta'] ?? [];
                if (!empty($pst['noKartu'])) {
                    try {
                        PcarePesertaKegiatanKelompok::updateOrCreate(
                            [
                                'eduId'   => $eduId,
                                'noKartu' => $pst['noKartu'],
                            ],
                            [
                                'nama'     => $pst['nama'] ?? '',
                                'sex'      => $pst['sex'] ?? '',
                                'tglLahir' => !empty($pst['tglLahir']) ? Carbon::createFromFormat('d-m-Y', $pst['tglLahir'])->format('Y-m-d') : null,
                                'noHP'     => $pst['noHP'] ?? '',
                            ]
                        );
                    } catch (\Throwable $e) {
                        // Ignore duplicate sync issues
                    }
                }
            }
        }

        return response()->json($res);
    }

    /**
     * Add Kegiatan Kelompok Baru ke BPJS PCare & Lokal DB
     */
    public function storeKegiatan(Request $request)
    {
        $payload = [
            'eduId'        => null,
            'clubId'       => (int) $request->clubId,
            'tglPelayanan' => (string) $request->tglPelayanan, // dd-mm-yyyy
            'kdKegiatan'   => (string) $request->kdKegiatan,   // 01: Senam, 10: Penyuluhan, 11: Penyuluhan dan Senam
            'kdKelompok'   => (string) $request->kdKelompok,   // 01: DM, 02: HT, 03: Asthma, dll
            'materi'       => (string) $request->materi,
            'pembicara'    => (string) $request->pembicara,
            'lokasi'       => (string) $request->lokasi,
            'keterangan'   => (string) ($request->keterangan ?? ''),
            'biaya'        => (int) ($request->biaya ?? 0),
        ];

        $res = $this->service->simpanKegiatan($payload);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if ($code == 201 || $code == 200) {
            $eduId = $res['response']['message'] ?? $res['response'] ?? null;

            if ($eduId) {
                $tglFormatted = Carbon::createFromFormat('d-m-Y', $request->tglPelayanan)->format('Y-m-d');
                $nmKegiatan = $request->kdKegiatan == '01' ? 'Senam' : ($request->kdKegiatan == '10' ? 'Penyuluhan' : 'Penyuluhan dan Senam');
                $nmKelompok = $request->kdKelompok == '01' ? 'Diabetes Melitus' : ($request->kdKelompok == '02' ? 'Hipertensi' : 'Asthma');

                $dataLocal = [
                    'eduId'        => $eduId,
                    'clubId'       => $request->clubId,
                    'namaClub'     => $request->namaClub ?? '',
                    'tglPelayanan' => $tglFormatted,
                    'nmKegiatan'   => $nmKegiatan,
                    'nmKelompok'   => $nmKelompok,
                    'materi'       => $request->materi,
                    'pembicara'    => $request->pembicara,
                    'lokasi'       => $request->lokasi,
                    'keterangan'   => $request->keterangan ?? '',
                    'biaya'        => $request->biaya ?? 0,
                ];

                PcareKegiatanKelompok::updateOrCreate(['eduId' => $eduId], $dataLocal);
                $this->insertSql(new PcareKegiatanKelompok(), $dataLocal);
            }
        }

        return response()->json($res);
    }

    /**
     * Add Peserta Kegiatan Kelompok Baru ke BPJS PCare & Lokal DB
     */
    public function storePeserta(Request $request)
    {
        $payload = [
            'eduId'   => (string) $request->eduId,
            'noKartu' => (string) $request->noKartu,
        ];

        $res = $this->service->simpanPeserta($payload);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if ($code == 201 || $code == 200) {
            $dataLocal = [
                'eduId'    => $request->eduId,
                'noKartu'  => $request->noKartu,
                'nama'     => $request->nama ?? '',
                'sex'      => $request->sex ?? '',
                'tglLahir' => $request->tglLahir ?? null,
                'noHP'     => $request->noHP ?? '',
            ];

            PcarePesertaKegiatanKelompok::updateOrCreate(
                ['eduId' => $request->eduId, 'noKartu' => $request->noKartu],
                $dataLocal
            );
            $this->insertSql(new PcarePesertaKegiatanKelompok(), $dataLocal);
        }

        return response()->json($res);
    }

    /**
     * Delete Kegiatan Kelompok
     */
    public function destroyKegiatan(Request $request, $eduId)
    {
        $res = $this->service->deleteKegiatan($eduId);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if ($code == 200 || $code == 201) {
            PcareKegiatanKelompok::where('eduId', $eduId)->delete();
            PcarePesertaKegiatanKelompok::where('eduId', $eduId)->delete();
        }

        return response()->json($res);
    }

    /**
     * Delete Peserta Kegiatan Kelompok
     */
    public function destroyPeserta(Request $request, $eduId, $noKartu)
    {
        $res = $this->service->deletePeserta($eduId, $noKartu);

        $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
        if ($code == 200 || $code == 201) {
            PcarePesertaKegiatanKelompok::where('eduId', $eduId)->where('noKartu', $noKartu)->delete();
        }

        return response()->json($res);
    }
}
