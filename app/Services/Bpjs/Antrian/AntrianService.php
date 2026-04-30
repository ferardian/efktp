<?php

namespace App\Services\Bpjs\Antrian;

class AntrianService extends AntrianClient
{
    /**
     * Tambah antrian pasien ke Mobile JKN FKTP
     *
     * @param array $data
     *   - nomorkartu    : no kartu BPJS (kosong jika non-BPJS)
     *   - nik           : NIK pasien
     *   - nohp          : no HP pasien
     *   - kodepoli      : kode poli PCare
     *   - namapoli      : nama poli
     *   - norm          : no rekam medis
     *   - tanggalperiksa: tanggal periksa (yyyy-mm-dd HH:mm:ss)
     *   - kodedokter    : kode dokter PCare
     *   - namadokter    : nama dokter
     *   - jampraktek    : jam praktek (HH:mm-HH:mm)
     *   - nomorantrean  : no antrian (no_reg)
     *   - angkaantrean  : angka antrian (integer)
     *   - keterangan    : pesan keterangan
     */
    public function add(array $data): array
    {
        $payload = [
            'nomorkartu'    => $data['nomorkartu'] ?? '',
            'nik'           => $data['nik'] ?? '',
            'nohp'          => $data['nohp'] ?? '',
            'kodepoli'      => $data['kodepoli'],
            'namapoli'      => $data['namapoli'],
            'norm'          => $data['norm'],
            'tanggalperiksa'=> $data['tanggalperiksa'],
            'kodedokter'    => $data['kodedokter'],
            'namadokter'    => $data['namadokter'],
            'jampraktek'    => $data['jampraktek'],
            'nomorantrean'  => $data['nomorantrean'],
            'angkaantrean'  => (int) $data['angkaantrean'],
            'keterangan'    => $data['keterangan'] ?? 'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
        ];

        return $this->post('antrean/add', $payload);
    }

    /**
     * Update status antrian (mulai dilayani atau batal)
     *
     * @param array $data
     *   - tanggalperiksa: yyyy-mm-dd
     *   - kodepoli      : kode poli PCare
     *   - nomorkartu    : no kartu BPJS (kosong jika non-BPJS)
     *   - status        : 1 = mulai dilayani, 2 = batal
     *   - waktu         : Unix timestamp dalam milliseconds
     */
    public function panggil(array $data): array
    {
        $payload = [
            'tanggalperiksa' => $data['tanggalperiksa'],
            'kodepoli'       => $data['kodepoli'],
            'nomorkartu'     => $data['nomorkartu'] ?? '',
            'status'         => (string) $data['status'],
            'waktu'          => (string) $data['waktu'],
        ];

        return $this->post('antrean/panggil', $payload);
    }

    /**
     * Batalkan antrian pasien
     *
     * @param array $data
     *   - tanggalperiksa: yyyy-mm-dd
     *   - kodepoli      : kode poli PCare
     *   - nomorkartu    : no kartu BPJS (kosong jika non-BPJS)
     *   - alasan        : alasan pembatalan
     */
    public function batal(array $data): array
    {
        $payload = [
            'tanggalperiksa' => $data['tanggalperiksa'],
            'kodepoli'       => $data['kodepoli'],
            'nomorkartu'     => $data['nomorkartu'] ?? '',
            'alasan'         => $data['alasan'] ?? 'pasien batal periksa',
        ];

        return $this->post('antrean/batal', $payload);
    }
}
