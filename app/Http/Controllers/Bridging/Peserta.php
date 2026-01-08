<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Peserta extends Controller
{
    use PcareConfig;
    public $bpjs;

    public function __construct()
    {
        $this->bpjs = new Pcare\Peserta($this->config());
    }
    function index($noKartu)
    {
        $bpjs = $this->bpjs;

        // dd($bpjs);
        return $bpjs->jenisKartu($noKartu)->index();
    }
}
