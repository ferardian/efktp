<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Prognosa extends Controller
{
    use PcareConfig;

    public $bpjs;
    public function __construct()
    {
        $this->bpjs = new Pcare\Prognosa($this->config());
    }

    public function get()
    {
        $bpjs = $this->bpjs;
        return $bpjs->index();
    }
}
