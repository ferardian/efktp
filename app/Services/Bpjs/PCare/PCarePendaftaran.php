<?php

namespace App\Services\Bpjs\PCare;

class PCarePendaftaran extends PCareClient
{
    /**
     * Kirim pendaftaran baru ke BPJS PCare
     */
    public function create(array $data): array
    {
        $payload = [
            'kdProviderPeserta' => $data['kdProviderPeserta'] ?? '',
            'tglDaftar'         => $data['tgl_daftar'],         // dd-mm-yyyy
            'noKartu'           => $data['no_peserta'],
            'kdPoli'            => $data['kd_poli_pcare'],
            'keluhan'           => ($data['keluhan'] == '-' || empty($data['keluhan'])) ? 'Tidak Ada' : $data['keluhan'],
            'kunjSakit'         => true,
            'sistole'           => (int) ($data['sistole'] ?: 0),
            'diastole'          => (int) ($data['diastole'] ?: 0),
            'beratBadan'        => (int) ($data['berat'] ?: 0),
            'tinggiBadan'       => (int) ($data['tinggi'] ?: 0),
            'respRate'          => (int) ($data['respirasi'] ?: 0),
            'lingkarPerut'      => (int) ($data['lingkar_perut'] ?: 0),
            'heartRate'         => (int) ($data['nadi'] ?: 0),
            'rujukBalik'        => 0,
            'kdTkp'             => $data['kdTkp'] ?? '10',
        ];

        return $this->post('pendaftaran', $payload);
    }

    /**
     * Ambil daftar pendaftaran berdasarkan tanggal
     */
    public function getByTanggal(string $tgl = '', int $start = 0, int $limit = 15): array
    {
        $tgl = $tgl ?: date('d-m-Y');
        return $this->get("pendaftaran/tglDaftar/{$tgl}/{$start}/{$limit}");
    }

    /**
     * Ambil pendaftaran berdasarkan nomor urut
     */
    public function getByNoUrut(string $noUrut, string $tgl = ''): array
    {
        $tgl = $tgl ?: date('d-m-Y');
        return $this->get("pendaftaran/noUrut/{$noUrut}/tglDaftar/{$tgl}");
    }

    /**
     * Hapus pendaftaran
     */
    public function hapus(string $noKartu, string $tglDaftar, string $noUrut, string $kdPoli): array
    {
        return $this->delete("pendaftaran/peserta/{$noKartu}/tglDaftar/{$tglDaftar}/noUrut/{$noUrut}/kdPoli/{$kdPoli}");
    }
}
