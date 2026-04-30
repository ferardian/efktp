<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\PcareRujukSubspesialis;
use App\Services\Bpjs\PCare\PCareKunjungan;
use Illuminate\Support\Facades\Log;

class Kunjungan extends Controller
{
    protected PCareKunjungan $kunjungan;

    public function __construct()
    {
        $this->kunjungan = new PCareKunjungan();
    }

    /**
     * Ambil riwayat kunjungan berdasarkan no kartu
     */
    public function get(string $nokartu): array
    {
        return $this->kunjungan->getRiwayat($nokartu);
    }

    /**
     * Ambil noKunjungan dari riwayat BPJS berdasarkan noKartu + tglDaftar
     */
    public function getNoKunjungan(Request $request): JsonResponse
    {
        $noKartu   = $request->noKartu;
        $tglDaftar = $request->tglDaftar; // format: dd-mm-yyyy

        $riwayat = $this->kunjungan->getRiwayat($noKartu);

        if (($riwayat['metaData']['code'] ?? 0) != 200) {
            return response()->json(['noKunjungan' => null, 'riwayat' => $riwayat]);
        }

        $list  = $riwayat['response']['list'] ?? [];
        $match = collect($list)
            ->filter(fn($item) => ($item['tglDaftar'] ?? '') === $tglDaftar)
            ->sortByDesc('noUrut')
            ->first();

        return response()->json([
            'noKunjungan' => $match['noKunjungan'] ?? null,
            'match'       => $match,
        ]);
    }

    /**
     * Kirim kunjungan baru ke BPJS PCare.
     * Jika ada jenisRujukan, simpan juga ke pcare_rujuk_subspesialis.
     */
    public function post(Request $request): mixed
    {
        $data = $request->all();

        try {
            Log::info('[KUNJUNGAN POST] Payload ke BPJS:', $data);
            $result = $this->kunjungan->create($data);
            Log::info('[KUNJUNGAN POST] Respons BPJS:', $result);

            // Simpan ke pcare_rujuk_subspesialis jika ada rujukan
            if ($request->jenisRujukan && !empty($request->no_rawat)) {
                $noKunjungan = $result['response']['message'] ?? $result['response']['noKunjungan'] ?? null;
                $this->simpanRujukan($request, $noKunjungan);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('[KUNJUNGAN POST] Error: ' . $e->getMessage());
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    /**
     * Update kunjungan yang sudah ada di BPJS PCare.
     * Jika ada jenisRujukan, update juga pcare_rujuk_subspesialis.
     */
    public function put(Request $request): mixed
    {
        $data = $request->all();

        try {
            Log::info('[KUNJUNGAN PUT] Payload ke BPJS:', $data);
            $result = $this->kunjungan->update($data);
            Log::info('[KUNJUNGAN PUT] Respons BPJS:', $result);

            // Update pcare_rujuk_subspesialis jika ada rujukan
            if ($request->jenisRujukan && !empty($request->no_rawat)) {
                $noKunjungan = $request->noKunjungan;
                $this->simpanRujukan($request, $noKunjungan, update: true);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('[KUNJUNGAN PUT] Error: ' . $e->getMessage());
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    /**
     * Ambil data rujukan dari BPJS berdasarkan noKunjungan
     */
    public function getRujukan(string $noKunjungan): JsonResponse
    {
        // Endpoint rujukan masih pakai riwayat — ambil dari data lokal dulu
        $lokal = PcareRujukSubspesialis::where('noKunjungan', $noKunjungan)->first();
        if ($lokal) {
            return response()->json($lokal);
        }

        return response()->json(['message' => 'Data rujukan tidak ditemukan.'], 404);
    }

    /**
     * Hapus kunjungan di server BPJS PCare
     */
    public function delete(string $noKunjungan): mixed
    {
        try {
            Log::info("[KUNJUNGAN DELETE] Menghapus No. Kunjungan: $noKunjungan");
            $result = $this->kunjungan->hapus($noKunjungan);
            Log::info("[KUNJUNGAN DELETE] Respons BPJS:", $result);

            return $result;
        } catch (\Exception $e) {
            Log::error("[KUNJUNGAN DELETE] Error: " . $e->getMessage());
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Simpan atau update data ke tabel pcare_rujuk_subspesialis
     */
    private function simpanRujukan(Request $request, ?string $noKunjungan, bool $update = false): void
    {
        $dataRujuk = [
            'noKunjungan'    => $noKunjungan,
            'tglDaftar'      => $request->tgl_daftar
                                ? date('Y-m-d', strtotime(str_replace('-', '/', $request->tgl_daftar)))
                                : null,
            'no_rkm_medis'   => $request->no_rkm_medis,
            'nm_pasien'      => $request->nm_pasien,
            'noKartu'        => $request->no_peserta,
            'kdPoli'         => $request->kd_poli_pcare,
            'nmPoli'         => $request->nm_poli_pcare,
            'keluhan'        => $request->keluhan,
            'kdSadar'        => $request->kesadaran,
            'nmSadar'        => $request->nmSadar,
            'sistole'        => $request->tensi != '-' ? explode('/', $request->tensi)[0] : '0',
            'diastole'       => $request->tensi != '-' ? explode('/', $request->tensi)[1] : '0',
            'beratBadan'     => $request->berat,
            'tinggiBadan'    => $request->tinggi,
            'respRate'       => $request->respirasi,
            'heartRate'      => $request->nadi,
            'lingkarPerut'   => $request->lingkar_perut,
            'terapi'         => $request->rtl,
            'kdStatusPulang' => $request->sttsPulang,
            'nmStatusPulang' => $request->nmStatusPulang,
            'tglPulang'      => $request->tglPulang
                                ? date('Y-m-d', strtotime(str_replace('-', '/', $request->tglPulang)))
                                : null,
            'kdDokter'       => $request->kd_dokter_pcare,
            'nmDokter'       => $request->nm_dokter,
            'kdDiag1'        => $request->kdDiagnosa1,
            'nmDiag1'        => $request->diagnosa1,
            'kdDiag2'        => $request->kdDiagnosa2,
            'nmDiag2'        => $request->diagnosa2,
            'kdDiag3'        => $request->kdDiagnosa3,
            'nmDiag3'        => $request->diagnosa3,
            'tglEstRujuk'    => $request->tglEstRujukan
                                ? date('Y-m-d', strtotime(str_replace('-', '/', $request->tglEstRujukan)))
                                : null,
            'kdPPK'          => $request->kdPpkRujukan,
            'nmPPK'          => $request->ppkRujukan,
            'kdSubSpesialis' => $request->kdSubSpesialis,
            'nmSubSpesialis' => $request->spesialis,
            'kdSarana'       => $request->kdSarana,
            'nmSarana'       => $request->sarana,
            'kdTACC'         => $request->kdTacc,
            'nmTACC'         => $request->nmTacc,
            'alasanTACC'     => $request->alasanTacc,
            'KdAlergiMakanan'=> $request->alergiMakan,
            'NmAlergiMakanan'=> $request->nmAlergiMakan ?? '',
            'KdAlergiUdara'  => $request->alergiUdara,
            'NmAlergiUdara'  => $request->nmAlergiUdara ?? '',
            'KdAlergiObat'   => $request->alergiObat,
            'NmAlergiObat'   => $request->nmAlergiObat ?? '',
            'KdPrognosa'     => $request->kdPrognosa,
            'NmPrognosa'     => $request->nmPrognosa ?? '',
            'terapi_non_obat'=> $request->instruksi,
            'bmhp'           => '-',
        ];

        if ($update) {
            PcareRujukSubspesialis::where('no_rawat', $request->no_rawat)->update($dataRujuk);
        } else {
            PcareRujukSubspesialis::updateOrCreate(
                ['no_rawat' => $request->no_rawat],
                $dataRujuk
            );
        }
    }
}
