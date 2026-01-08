<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusPulang extends Controller
{
    use PcareConfig;

    public $bpjs;
    public function __construct()
    {
        $this->bpjs = new Pcare\StatusPulang($this->config());
    }

    public function get($status)
    {
        $bpjs = $this->bpjs;
        return $bpjs->rawatInap($status)->index();
    }
}
