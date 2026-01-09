<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Http\Controllers\Controller;
use App\Traits\PcareConfig;

use Illuminate\Http\Request;

class Dokter extends Controller
{
  use PcareConfig;
  public function __construct()
  {
  }

  function dokter($start = 0, $limit = 10)
  {
    $bpjs = new Pcare\Dokter($this->config());
    return $bpjs->index($start, $limit);
  }
}
