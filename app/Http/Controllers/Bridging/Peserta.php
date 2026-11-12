<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\PCare\PCarePeserta;

class Peserta extends Controller
{
    protected PCarePeserta $peserta;

    public function __construct()
    {
        $this->peserta = new PCarePeserta();
    }

    public function index(string $value, ?string $type = null)
    {
        // Auto deteksi jika type tidak dikirim
        if (!$type) {
            $type = strlen($value) == 16 ? 'nik' : 'noka';
        }

        if ($type == 'nik') {
            $res = $this->peserta->getByNik($value);
        } else {
            $res = $this->peserta->getByNoKartu($value);
        }

        $this->simpanStatusProlanisPrb($res);

        return $res;
    }

    /**
     * Simpan status Prolanis & PRB dari respons BPJS PCare ke database lokal
     */
    protected function simpanStatusProlanisPrb($res): void
    {
        try {
            if (!is_array($res) || empty($res['metaData']) || ($res['metaData']['code'] ?? 0) != 200 || empty($res['response'])) {
                return;
            }

            $resp = $res['response'];
            $noKartu = $resp['noKartu'] ?? null;
            if (!$noKartu) {
                return;
            }

            $pstProl = $resp['pstProl'] ?? null;
            $pstPrb = $resp['pstPrb'] ?? null;

            $prolanisStr = is_array($pstProl) ? json_encode($pstProl) : ($pstProl ? (string)$pstProl : null);
            $prbStr = is_array($pstPrb) ? json_encode($pstPrb) : ($pstPrb ? (string)$pstPrb : null);

            $isProlanis = !empty($prolanisStr) && trim($prolanisStr) !== '' && $prolanisStr !== '-' && $prolanisStr !== '0';
            $isPrb = !empty($prbStr) && trim($prbStr) !== '' && $prbStr !== '-' && $prbStr !== '0';

            $noKtp = $resp['noKTP'] ?? null;
            $pasien = \App\Models\Pasien::where('no_peserta', $noKartu)
                ->when($noKtp, fn($q) => $q->orWhere('no_ktp', $noKtp))
                ->first();

            \App\Models\EfktpPasienProlanisPrb::updateOrCreate(
                ['no_peserta' => $noKartu],
                [
                    'no_rkm_medis' => $pasien?->no_rkm_medis,
                    'prolanis'     => $prolanisStr,
                    'prb'          => $prbStr,
                    'is_prolanis'  => $isProlanis,
                    'is_prb'       => $isPrb,
                ]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[PROLANIS PRB SYNC ERROR] ' . $e->getMessage());
        }
    }
}
