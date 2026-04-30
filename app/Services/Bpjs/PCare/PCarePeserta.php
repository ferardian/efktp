<?php

namespace App\Services\Bpjs\PCare;

class PCarePeserta extends PCareClient
{
    /**
     * Cek kepesertaan berdasarkan nomor kartu BPJS (Method 1)
     */
    public function getByNoKartu(string $noKartu): array
    {
        return $this->get("peserta/{$noKartu}");
    }

    /**
     * Cek kepesertaan berdasarkan jenis (nik / noka) (Method 2)
     */
    public function getByJenis(string $jenis, string $value): array
    {
        return $this->get("peserta/{$jenis}/{$value}");
    }

    /**
     * Cek kepesertaan berdasarkan NIK
     */
    public function getByNik(string $nik): array
    {
        return $this->getByJenis('nik', $nik);
    }
}
