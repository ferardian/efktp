<?php

namespace App\Services\Bpjs\PCare;

class PCareObat extends PCareClient
{
    /**
     * Simpan / Add obat kunjungan ke BPJS PCare
     */
    public function simpan(array $data): array
    {
        return $this->post('obat/kunjungan', $data);
    }

    /**
     * Hapus data obat kunjungan dari BPJS PCare
     */
    public function hapus(string $kdObatSK, string $noKunjungan): array
    {
        return parent::delete("obat/$kdObatSK/kunjungan/$noKunjungan");
    }

    /**
     * Search DPHO obat
     */
    public function getDpho(string $keyword, int $start = 0, int $limit = 100): array
    {
        return $this->get("obat/dpho/$keyword/$start/$limit");
    }
}
