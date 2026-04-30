<?php

namespace App\Services\Bpjs\PCare;

use Illuminate\Support\Carbon;

class PCareKunjungan extends PCareClient
{
    /**
     * Buat kunjungan baru ke BPJS PCare (endpoint V1)
     */
    public function create(array $data): array
    {
        $payload = [
            'noKunjungan'        => null,
            'noKartu'            => $data['no_peserta'],
            'tglDaftar'          => $data['tgl_daftar'],      // dd-mm-yyyy
            'kdPoli'             => $data['kd_poli_pcare'],
            'keluhan'            => $data['keluhan'],
            'kdSadar'            => $data['kesadaran'],
            'sistole'            => $this->parseTensi($data['tensi'], 0),
            'diastole'           => $this->parseTensi($data['tensi'], 1),
            'beratBadan'         => $data['berat'],
            'tinggiBadan'        => $data['tinggi'],
            'respRate'           => $data['respirasi'],
            'heartRate'          => $data['nadi'],
            'lingkarPerut'       => $data['lingkar_perut'],
            'kdStatusPulang'     => $data['sttsPulang'],
            'tglPulang'          => $data['tglPulang'],       // dd-mm-yyyy
            'kdDokter'           => $data['kd_dokter_pcare'],
            'kdDiag1'            => $data['kdDiagnosa1'],
            'kdDiag2'            => !empty($data['kdDiagnosa2']) ? $data['kdDiagnosa2'] : null,
            'kdDiag3'            => !empty($data['kdDiagnosa3']) ? $data['kdDiagnosa3'] : null,
            'anamnesa'           => $data['anamnesa'],
            'alergiMakan'        => $data['alergiMakan'] ?? '00',
            'alergiUdara'        => $data['alergiUdara'] ?? '00',
            'alergiObat'         => $data['alergiObat'] ?? '00',
            'kdPrognosa'         => $data['kdPrognosa'],
            'terapiObat'         => $data['rtl'],
            'terapiNonObat'      => $data['instruksi'],
            'bmhp'               => '-',
            'suhu'               => $data['suhu_tubuh'],
            'kdPoliRujukInternal'=> $data['kdInternal'] ?? null,
        ];

        // Rujukan jika ada
        if (!empty($data['jenisRujukan'])) {
            $payload = array_merge($payload, $this->buildRujukan($data));
        }

        return $this->post('kunjungan/V1', $payload);
    }

    /**
     * Update kunjungan yang sudah ada
     */
    public function update(array $data): array
    {
        $payload = [
            'noKunjungan'        => $data['noKunjungan'],
            'noKartu'            => $data['no_peserta'],
            'tglDaftar'          => $data['tgl_daftar'],
            'keluhan'            => $data['keluhan'],
            'kdSadar'            => $data['kesadaran'],
            'sistole'            => $this->parseTensi($data['tensi'], 0),
            'diastole'           => $this->parseTensi($data['tensi'], 1),
            'beratBadan'         => $data['berat'],
            'tinggiBadan'        => $data['tinggi'],
            'respRate'           => $data['respirasi'],
            'heartRate'          => $data['nadi'],
            'lingkarPerut'       => $data['lingkar_perut'],
            'kdStatusPulang'     => $data['sttsPulang'],
            'tglPulang'          => $data['tglPulang'],
            'kdDokter'           => $data['kd_dokter_pcare'],
            'kdDiag1'            => $data['kdDiagnosa1'],
            'kdDiag2'            => !empty($data['kdDiagnosa2']) ? $data['kdDiagnosa2'] : null,
            'kdDiag3'            => !empty($data['kdDiagnosa3']) ? $data['kdDiagnosa3'] : null,
            'anamnesa'           => $data['anamnesa'],
            'alergiMakan'        => $data['alergiMakan'] ?? '00',
            'alergiUdara'        => $data['alergiUdara'] ?? '00',
            'alergiObat'         => $data['alergiObat'] ?? '00',
            'kdPrognosa'         => $data['kdPrognosa'],
            'terapiObat'         => $data['rtl'],
            'terapiNonObat'      => $data['instruksi'],
            'bmhp'               => '-',
            'suhu'               => $data['suhu_tubuh'],
            'kdPoliRujukInternal'=> $data['kdInternal'] ?? null,
        ];

        if (!empty($data['jenisRujukan'])) {
            $payload = array_merge($payload, $this->buildRujukan($data));
        }

        return $this->put('kunjungan/V1', $payload);
    }

    /**
     * Ambil riwayat kunjungan berdasarkan noKartu
     */
    public function getRiwayat(string $noKartu): array
    {
        return $this->get("kunjungan/riwayat/{$noKartu}");
    }

    /**
     * Cari noKunjungan dari riwayat berdasarkan noKartu + tanggal
     */
    public function getNoKunjungan(string $noKartu, string $tglDaftar): ?string
    {
        $riwayat = $this->getRiwayat($noKartu);

        if (($riwayat['metaData']['code'] ?? 0) != 200) {
            return null;
        }

        $list = $riwayat['response']['list'] ?? [];

        $match = collect($list)
            ->filter(fn($item) => ($item['tglDaftar'] ?? '') === $tglDaftar)
            ->sortByDesc('noUrut')
            ->first();

        return $match['noKunjungan'] ?? null;
    }

    /**
     * Hapus data kunjungan
     */
    public function hapus(string $noKunjungan): array
    {
        return $this->delete("kunjungan/{$noKunjungan}");
    }

    // -------------------------------------------------------------------------

    private function parseTensi(string $tensi, int $index): string
    {
        if ($tensi === '-' || empty($tensi)) return '0';
        $parts = explode('/', $tensi);
        return $parts[$index] ?? '0';
    }

    private function buildRujukan(array $data): array
    {
        $extra = [];
        $jenis = $data['jenisRujukan'];

        if ($jenis === 'spesialis') {
            $extra['rujukLanjut'] = [
                'kdppk'        => $data['kdPpkRujukan'],
                'tglEstRujuk'  => $data['tglEstRujukan'],
                'subSpesialis' => [
                    'kdSubSpesialis1' => $data['kdSubSpesialis'],
                    'kdSarana'        => $data['kdSarana'],
                ],
                'khusus' => null,
            ];
            $extra['kdTacc']    = $data['kdTacc'] ?? '-1';
            $extra['alasanTacc']= $data['alasanTacc'] ?? null;
        } elseif ($jenis === 'khusus') {
            $extra['rujukLanjut'] = [
                'kdppk'       => $data['kdPpkRujukan'],
                'tglEstRujuk' => $data['tglEstRujukan'],
                'subSpesialis'=> null,
                'khusus'      => [
                    'kdKhusus'    => $data['kdKhusus'],
                    'kdSubSpesialis' => $data['kdKhususSub'],
                    'catatan'     => $data['catatanKhusus'],
                ],
            ];
            $extra['kdTacc']    = $data['kdTacc'] ?? '-1';
            $extra['alasanTacc']= $data['alasanTacc'] ?? null;
        } elseif ($jenis === 'internal') {
            $extra['rujukLanjut'] = [
                'kdppk'       => $data['kdPpkRujukan'],
                'tglEstRujuk' => $data['tglEstRujukan'],
                'subSpesialis'=> null,
            ];
            $extra['kdTacc']    = $data['kdTacc'] ?? '-1';
            $extra['alasanTacc']= $data['alasanTacc'] ?? null;
        }

        return $extra;
    }
}
