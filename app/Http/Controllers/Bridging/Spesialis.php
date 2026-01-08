<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Spesialis extends Controller
{

    use PcareConfig;

    public $bpjs;
    public function __construct()
    {
        $this->bpjs = new Pcare\Spesialis($this->config());
    }
    public function get()
    {
        $bpjs = $this->bpjs;
        return $bpjs->index();
    }
    public function getSubspesialis($kdSpesialis)
    {
        $bpjs = $this->bpjs;
        return $bpjs->index($kdSpesialis, 'subspesialis');
    }
    public function getSarana()
    {
        $bpjs = $this->bpjs;
        return $bpjs->sarana()->index();
    }
    public function getFaskes(Request $request)
    {

        $bpjs = $this->bpjs;
        return $bpjs->rujuk()->subSpesialis($request->kdSubSpesialis)->sarana($request->kdSarana ? $request->kdSarana : 0)->tanggalRujuk($request->tglRujuk)->index();
    }
    public function getFaskesKhusus(Request $request)
    {
        $bpjs = $this->bpjs;
        return $bpjs->rujuk()->khusus($request->kdKhusus)->nomorKartu($request->noKartu)->tanggalRujuk($request->tglEstRujuk)->index();
    }
    function getKhusus()
    {
        $bpjs = $this->bpjs;
        return $bpjs->khusus()->index();
    }
}
