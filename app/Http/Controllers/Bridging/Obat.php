<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\PCare\PCareObat;
use App\Models\ResepObat;
use App\Models\MappingObatPcare;
use App\Models\PcareObatDiberikan;
use App\Traits\Track;

class Obat extends Controller
{
    use Track;

    protected PCareObat $pcareObat;

    public function __construct()
    {
        $this->pcareObat = new PCareObat();
    }

    public function get($keyword = '')
    {
        return response()->json($this->pcareObat->getDpho($keyword ?: 'a'));
    }

    public function create(Request $request)
    {
        $obatData = $request->data ?? [$request->all()];
        $results = [];

        foreach ($obatData as $item) {
            if (empty($item['noKunjungan'])) {
                continue;
            }

            $payload = [
                'kdObatSK'      => (int) ($item['kdObatSK'] ?? 0),
                'noKunjungan'   => $item['noKunjungan'],
                'racikan'       => (bool) ($item['racikan'] ?? false),
                'kdRacikan'     => $item['kdRacikan'] ?? null,
                'obatDPHO'      => (bool) ($item['obatDPHO'] ?? true),
                'kdObat'        => (string) ($item['kdObat'] ?? ''),
                'signa1'        => (int) ($item['signa1'] ?? 3),
                'signa2'        => (int) ($item['signa2'] ?? 1),
                'jmlObat'       => (int) ($item['jmlObat'] ?? 1),
                'jmlPermintaan'  => (int) ($item['jmlPermintaan'] ?? 1),
                'nmObatNonDPHO' => (string) ($item['nmObatNonDPHO'] ?? ''),
            ];

            try {
                $res = $this->pcareObat->simpan($payload);
                $results[] = [
                    'payload'  => $payload,
                    'response' => $res,
                ];

                // Jika berhasil, simpan ke pcare_obat_diberikan
                $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
                if ($code == 201 || $code == 200) {
                    $kdObatSK = $res['response']['message'] ?? $res['response'] ?? '0';
                    if (!is_numeric($kdObatSK)) {
                        $kdObatSK = '0';
                    }

                    if (!empty($item['no_rawat']) && !empty($item['kode_brng'])) {
                        $this->savePcareObatDiberikan([
                            'no_rawat'      => $item['no_rawat'],
                            'noKunjungan'   => $item['noKunjungan'],
                            'kdObatSK'      => (string) $kdObatSK,
                            'tgl_perawatan' => $item['tgl_perawatan'] ?? date('Y-m-d'),
                            'jam'           => $item['jam'] ?? date('H:i:s'),
                            'kode_brng'     => $item['kode_brng'],
                            'no_batch'      => $item['no_batch'] ?? '-',
                            'no_faktur'     => $item['no_faktur'] ?? '-',
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                $results[] = [
                    'payload' => $payload,
                    'error'   => $e->getMessage(),
                ];
            }
        }

        return response()->json($results, 200);
    }

    /**
     * Synchronize resep obat dari SIMRS ke BPJS PCare berdasarkan no_rawat & noKunjungan
     */
    public function syncByNoRawat(Request $request)
    {
        $noRawat = $request->no_rawat;
        $noKunjungan = $request->noKunjungan;

        if (!$noRawat || !$noKunjungan) {
            return response()->json(['message' => 'no_rawat dan noKunjungan wajib diisi'], 400);
        }

        $resep = ResepObat::where('no_rawat', $noRawat)
            ->with(['resepDokter.obat', 'resepRacikan.detail.obat'])
            ->first();

        if (!$resep) {
            return response()->json(['message' => 'Tidak ada data resep obat untuk kunjungan ini', 'results' => []], 200);
        }

        $tglPerawatan = $resep->tgl_perawatan ?? date('Y-m-d');
        $jam = $resep->jam ?? date('H:i:s');
        $results = [];

        // 1. Process Obat Non-Racikan (resep_dokter)
        if ($resep->resepDokter && count($resep->resepDokter) > 0) {
            foreach ($resep->resepDokter as $item) {
                $mapping = MappingObatPcare::where('kode_brng', $item->kode_brng)->first();
                $kdObat = $mapping?->kode_brng_pcare ?? '130199999';
                $isDpho = ($kdObat !== '130199999' && !empty($mapping?->kode_brng_pcare));
                $nmObat = $mapping?->nama_brng_pcare ?? $item->obat?->nama_brng ?? 'Obat Non DPHO';

                $signa = $this->parseSigna($item->aturan_pakai);

                $payload = [
                    'kdObatSK'      => 0,
                    'noKunjungan'   => $noKunjungan,
                    'racikan'       => false,
                    'kdRacikan'     => null,
                    'obatDPHO'      => $isDpho,
                    'kdObat'        => $kdObat,
                    'signa1'        => $signa['signa1'],
                    'signa2'        => $signa['signa2'],
                    'jmlObat'       => (int) $item->jml,
                    'jmlPermintaan'  => (int) $item->jml,
                    'nmObatNonDPHO' => $nmObat,
                ];

                try {
                    $res = $this->pcareObat->simpan($payload);
                    $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;
                    
                    if ($code == 201 || $code == 200) {
                        $kdObatSK = $res['response']['message'] ?? $res['response'] ?? '0';
                        if (!is_numeric($kdObatSK)) {
                            $kdObatSK = '0';
                        }

                        $this->savePcareObatDiberikan([
                            'no_rawat'      => $noRawat,
                            'noKunjungan'   => $noKunjungan,
                            'kdObatSK'      => (string) $kdObatSK,
                            'tgl_perawatan' => $tglPerawatan,
                            'jam'           => $jam,
                            'kode_brng'     => $item->kode_brng,
                            'no_batch'      => '-',
                            'no_faktur'     => '-',
                        ]);
                    }
                    $results[] = ['item' => $item->kode_brng, 'status' => 'success', 'response' => $res];
                } catch (\Throwable $e) {
                    $results[] = ['item' => $item->kode_brng, 'status' => 'error', 'error' => $e->getMessage()];
                }
            }
        }

        // 2. Process Obat Racikan (resep_dokter_racikan & detail)
        if ($resep->resepRacikan && count($resep->resepRacikan) > 0) {
            foreach ($resep->resepRacikan as $racik) {
                $signa = $this->parseSigna($racik->aturan_pakai);
                $kdRacikStr = $racik->kd_racik ?? 'R01';

                if ($racik->detail && count($racik->detail) > 0) {
                    foreach ($racik->detail as $detail) {
                        $mapping = MappingObatPcare::where('kode_brng', $detail->kode_brng)->first();
                        $kdObat = $mapping?->kode_brng_pcare ?? '130199999';
                        $isDpho = ($kdObat !== '130199999' && !empty($mapping?->kode_brng_pcare));
                        $nmObat = $mapping?->nama_brng_pcare ?? $detail->obat?->nama_brng ?? 'Racikan Non DPHO';

                        $payload = [
                            'kdObatSK'      => 0,
                            'noKunjungan'   => $noKunjungan,
                            'racikan'       => true,
                            'kdRacikan'     => $kdRacikStr,
                            'obatDPHO'      => $isDpho,
                            'kdObat'        => $kdObat,
                            'signa1'        => $signa['signa1'],
                            'signa2'        => $signa['signa2'],
                            'jmlObat'       => (int) ($detail->jml ?? $racik->jml_dr),
                            'jmlPermintaan'  => (int) $racik->jml_dr,
                            'nmObatNonDPHO' => $nmObat,
                        ];

                        try {
                            $res = $this->pcareObat->simpan($payload);
                            $code = $res['metaData']['code'] ?? $res['metadata']['code'] ?? 500;

                            if ($code == 201 || $code == 200) {
                                $kdObatSK = $res['response']['message'] ?? $res['response'] ?? '0';
                                if (!is_numeric($kdObatSK)) {
                                    $kdObatSK = '0';
                                }

                                $this->savePcareObatDiberikan([
                                    'no_rawat'      => $noRawat,
                                    'noKunjungan'   => $noKunjungan,
                                    'kdObatSK'      => (string) $kdObatSK,
                                    'tgl_perawatan' => $tglPerawatan,
                                    'jam'           => $jam,
                                    'kode_brng'     => $detail->kode_brng,
                                    'no_batch'      => '-',
                                    'no_faktur'     => '-',
                                ]);
                            }
                            $results[] = ['racik' => $kdRacikStr, 'item' => $detail->kode_brng, 'status' => 'success', 'response' => $res];
                        } catch (\Throwable $e) {
                            $results[] = ['racik' => $kdRacikStr, 'item' => $detail->kode_brng, 'status' => 'error', 'error' => $e->getMessage()];
                        }
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Proses sinkronisasi obat PCare selesai',
            'results' => $results
        ], 200);
    }

    private function parseSigna(?string $aturanPakai): array
    {
        if (!$aturanPakai) {
            return ['signa1' => 3, 'signa2' => 1];
        }
        $parts = preg_split('/[xX]/', $aturanPakai);
        $signa1 = isset($parts[0]) && is_numeric(trim($parts[0])) ? (int) trim($parts[0]) : 3;
        $signa2 = isset($parts[1]) && is_numeric(trim($parts[1])) ? (int) trim($parts[1]) : 1;
        return ['signa1' => $signa1, 'signa2' => $signa2];
    }

    private function savePcareObatDiberikan(array $data)
    {
        try {
            $clause = [
                'no_rawat'      => $data['no_rawat'],
                'noKunjungan'   => $data['noKunjungan'],
                'tgl_perawatan' => $data['tgl_perawatan'],
                'jam'           => $data['jam'],
                'kode_brng'     => $data['kode_brng'],
                'no_batch'      => $data['no_batch'],
                'no_faktur'     => $data['no_faktur'],
            ];

            PcareObatDiberikan::updateOrCreate($clause, $data);
            $this->insertSql(new PcareObatDiberikan(), $data);
        } catch (\Throwable $e) {
            // ignore duplicate primary key errors silently
        }
    }
}
