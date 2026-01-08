<?php

namespace App\Services;

use App\Models\RegPeriksa;

class RegPeriksaServices
{
    public static function setNoRawat(RegPeriksa $model, string $tanggal = null)
    {
        $tglRegistrasi = $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d');
        $reg = $model->maxByTanggal($tglRegistrasi)->first();
        $no = $reg ? explode('/', $reg->no_rawat)[3] + 1 : 1;
        $no_reg = sprintf('%06d', $no);
        $tglRawat = date('Y/m/d', strtotime($tglRegistrasi));
        return "{$tglRawat}/{$no_reg}";
    }
}
