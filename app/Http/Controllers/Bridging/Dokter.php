<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Http\Controllers\Controller;
use App\Traits\PcareConfig;

class Dokter extends Controller
{
  use PcareConfig;
  public function __construct()
  {
  }

  function dokter()
  {
    $bpjs = new Pcare\Dokter($this->config());
    return $bpjs->index(0, 10);
  }
}
