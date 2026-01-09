<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Poli extends Controller
{
    use PcareConfig;
    public $bpjs;

    public function __construct()
    {
        $this->bpjs = new Pcare\Poli($this->config());
    }
    function index($start = 0, $limit = 50)
    {
        $bpjs = $this->bpjs;
        return $bpjs->fktp()->index($start, $limit);
    }
}
