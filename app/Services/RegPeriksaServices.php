<?php

namespace App\Services;

use App\Models\RegPeriksa;

class RegPeriksaServices
{
    public static function setNoRawat(RegPeriksa $model, string $tanggal = null)
    {
        $tglRegistrasi = $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d');
        $tglRawat = date('Y/m/d', strtotime($tglRegistrasi));

        $reg = $model->where('no_rawat', 'LIKE', "{$tglRawat}/%")->orderBy('no_rawat', 'DESC')->first();
        $no = $reg ? (int) (explode('/', $reg->no_rawat)[3] ?? 0) + 1 : 1;

        do {
            $no_reg = sprintf('%06d', $no);
            $candidateNoRawat = "{$tglRawat}/{$no_reg}";
            $exists = $model->where('no_rawat', $candidateNoRawat)->exists();
            if ($exists) {
                $no++;
            }
        } while ($exists);

        return $candidateNoRawat;
    }
}
