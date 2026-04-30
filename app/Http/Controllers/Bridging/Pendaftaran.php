<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\PCare\PCarePendaftaran;
use App\Services\Bpjs\Antrian\AntrianService;

class Pendaftaran extends Controller
{
    protected PCarePendaftaran $pendaftaran;
    protected AntrianService $antrian;

    public function __construct()
    {
        $this->pendaftaran = new PCarePendaftaran();
        $this->antrian     = new AntrianService();
    }

    /**
     * Ambil daftar pendaftaran hari ini
     */
    public function get(Request $request): array
    {
        $start = $request->start ?? 0;
        $limit = $request->length ?? 15;
        return $this->pendaftaran->getByTanggal(date('d-m-Y'), $start, $limit);
    }

    /**
     * Ambil pendaftaran berdasarkan tanggal
     */
    public function getByTanggal(string $tgl = '', int $start = 0, int $limit = 15): array
    {
        return $this->pendaftaran->getByTanggal($tgl ?: date('d-m-Y'), $start, $limit);
    }

    /**
     * Ambil pendaftaran berdasarkan nomor urut
     */
    public function getUrut(string $noUrut): array
    {
        return $this->pendaftaran->getByNoUrut($noUrut, date('d-m-Y'));
    }

    /**
     * Hapus pendaftaran
     */
    public function delete(Request $request): mixed
    {
        return $this->pendaftaran->hapus(
            $request->noKartu,
            $request->tglDaftar,
            $request->noUrut,
            $request->kdPoli
        );
    }

    /**
     * Kirim pendaftaran PCare.
     * Jika ANTRIAN_ENABLED=true, kirim antrian dulu.
     * Jika antrian gagal, kembalikan error + flag requires_confirm.
     * Jika skip_antrian=true (user konfirmasi), langsung daftar PCare.
     */
    public function post(Request $request): JsonResponse
    {
        $antrianEnabled = config('bpjs.antrian.enabled', false);
        $antrianResult  = null;

        // --- Kirim Antrian (jika enabled dan belum di-skip) ---
        if ($antrianEnabled && !$request->boolean('skip_antrian')) {
            $antrianPayload = [
                'nomorkartu'    => $request->no_peserta ?? '',
                'nik'           => $request->no_ktp ?? '',
                'nohp'          => $request->no_tlp ?? '',
                'kodepoli'      => $request->kd_poli_pcare,
                'namapoli'      => $request->nm_poli_pcare,
                'norm'          => $request->no_rkm_medis,
                'tanggalperiksa'=> date('Y-m-d', strtotime($request->tgl_registrasi)),
                'kodedokter'    => (int) $request->kd_dokter_pcare,
                'namadokter'    => $request->nm_dokter ?? 'Dokter Faskes',
                'jampraktek'    => $request->jampraktek ?? '08:00-14:00',
                'nomorantrean'  => $request->no_reg,
                'angkaantrean'  => (int) $request->no_reg,
                'keterangan'    => 'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
            ];

            $antrianResult = $this->antrian->add($antrianPayload);
            $antrianCode   = $antrianResult['metadata']['code']
                          ?? $antrianResult['metaData']['code']
                          ?? 500;

            // Antrian gagal → kembalikan response khusus untuk konfirmasi frontend
            if ($antrianCode != 200) {
                $antrianMessage = $antrianResult['metadata']['message']
                               ?? $antrianResult['metaData']['message']
                               ?? 'Gagal terhubung ke server Antrian BPJS.';

                return response()->json([
                    'antrian_error'   => $antrianMessage,
                    'requires_confirm'=> true,
                    'antrian_response'=> $antrianResult,
                ], 200);
            }
        }

        // --- Kirim Pendaftaran PCare ---
        $data = [
            'kdProviderPeserta' => $request->kdProviderPeserta,
            'tgl_daftar'        => $request->tgl_registrasi
                                    ? date('d-m-Y', strtotime($request->tgl_registrasi))
                                    : date('d-m-Y'),
            'no_peserta'        => $request->no_peserta,
            'kd_poli_pcare'     => $request->kd_poli_pcare,
            'keluhan'           => $request->keluhan,
            'sistole'           => $request->sistole,
            'diastole'          => $request->diastole,
            'berat'             => $request->berat,
            'tinggi'            => $request->tinggi,
            'respirasi'         => $request->respirasi,
            'lingkar_perut'     => $request->lingkar_perut,
            'nadi'              => $request->nadi,
            'kdTkp'             => $request->kdTkp ?? '10',
        ];

        $pendaftaranResult = $this->pendaftaran->create($data);

        // Jika respons kosong sama sekali
        if (empty($pendaftaranResult)) {
            return response()->json([
                'metaData' => ['code' => 500, 'message' => 'Gagal mendapatkan respons dari server PCare BPJS.']
            ], 200);
        }

        return response()->json([
            'antrian'    => $antrianResult,
            'pendaftaran'=> $pendaftaranResult,
        ]);
    }
}
