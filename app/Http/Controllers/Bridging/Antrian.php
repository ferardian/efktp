<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\Antrian\AntrianService;

class Antrian extends Controller
{
    protected AntrianService $antrian;

    public function __construct()
    {
        $this->antrian = new AntrianService();
    }

    /**
     * Tambah antrian pasien ke Mobile JKN FKTP
     */
    public function add(Request $request): JsonResponse
    {
        $noKartu = trim($request->no_peserta ?? $request->nomorkartu ?? '');
        if ($noKartu === '-' || strtolower($noKartu) === 'null') $noKartu = '';

        $nik = trim($request->no_ktp ?? $request->nik ?? '');
        if ($nik === '-' || strtolower($nik) === 'null') $nik = '';

        $noHp = trim($request->no_tlp ?? $request->nohp ?? '');
        if ($noHp === '' || $noHp === '-' || strtolower($noHp) === 'null') {
            $noHp = '08000000000';
        }

        $pendaftaran = new Pendaftaran();
        $jamPraktek = $pendaftaran->getJamPraktek($request->kd_dokter ?? $request->kdDokter ?? $request->kodedokter, $request->tgl_registrasi ?? $request->tanggalperiksa);

        $payload = [
            'nomorkartu'    => $noKartu,
            'nik'           => $nik,
            'nohp'          => $noHp,
            'kodepoli'      => $request->kd_poli_pcare ?? $request->kodepoli,
            'namapoli'      => $request->nm_poli_pcare ?? $request->namapoli,
            'norm'          => $request->no_rkm_medis ?? $request->norm,
            'tanggalperiksa'=> date('Y-m-d', strtotime($request->tgl_registrasi ?? $request->tanggalperiksa ?? date('Y-m-d'))),
            'kodedokter'    => (int) ($request->kd_dokter_pcare ?? $request->kodedokter ?? 0),
            'namadokter'    => $request->nm_dokter ?? $request->namadokter ?? 'Dokter Faskes',
            'jampraktek'    => $jamPraktek,
            'nomorantrean'  => $request->no_reg ?? $request->nomorantrean,
            'angkaantrean'  => (int) ($request->no_reg ?? $request->angkaantrean ?? 0),
            'keterangan'    => $request->keterangan ?? 'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
        ];

        $result = $this->antrian->add($payload);
        return response()->json($result);
    }

    /**
     * Update status antrian (1 = mulai dilayani, 2 = batal)
     */
    public function panggil(Request $request): JsonResponse
    {
        $result = $this->antrian->panggil($request->all());
        return response()->json($result);
    }

    /**
     * Batalkan antrian pasien
     */
    public function batal(Request $request): JsonResponse
    {
        $result = $this->antrian->batal($request->all());
        return response()->json($result);
    }
}
