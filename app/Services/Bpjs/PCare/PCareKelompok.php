<?php

namespace App\Services\Bpjs\PCare;

class PCareKelompok extends PCareClient
{
    /**
     * Get Data Club Prolanis BPJS PCare
     * @param string $kdProgram 01 : Diabetes Melitus, 02 : Hipertensi
     */
    public function getClub(string $kdProgram = '01'): array
    {
        return $this->get("kelompok/club/$kdProgram");
    }

    /**
     * Get Data Kegiatan Kelompok BPJS PCare
     * @param string $bulan Format: dd-mm-yyyy
     */
    public function getKegiatan(string $bulan): array
    {
        return $this->get("kelompok/kegiatan/$bulan");
    }

    /**
     * Get Data Peserta Kegiatan Kelompok
     * @param string $eduId
     */
    public function getPeserta(string $eduId): array
    {
        return $this->get("kelompok/peserta/$eduId");
    }

    /**
     * Add Data Kegiatan Kelompok
     */
    public function simpanKegiatan(array $data): array
    {
        return $this->post('kelompok/kegiatan', $data);
    }

    /**
     * Add Data Peserta Kegiatan Kelompok
     */
    public function simpanPeserta(array $data): array
    {
        return $this->post('kelompok/peserta', $data);
    }

    /**
     * Delete Data Kegiatan Kelompok
     */
    public function deleteKegiatan(string $eduId): array
    {
        return $this->delete("kelompok/kegiatan/$eduId");
    }

    /**
     * Delete Data Peserta Kegiatan Kelompok
     */
    public function deletePeserta(string $eduId, string $noKartu): array
    {
        return $this->delete("kelompok/peserta/$eduId/$noKartu");
    }
}
