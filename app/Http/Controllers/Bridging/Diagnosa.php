<?php

namespace App\Http\Controllers\Bridging;

use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use AamDsam\Bpjs\PCare;
use App\Http\Controllers\Controller;
use App\Traits\PcareConfig;

class Diagnosa extends Controller
{
  use PcareConfig;
  protected  $bpjs;
  public function __construct()
  {
    $bpjs = new Pcare\Diagnosa($this->config());
    $this->bpjs = $bpjs;
  }

  function get($diagnosa): JsonResponse
  {
    try {
      $result = $this->bpjs->diagnosa($diagnosa)->index(1, 15);
    } catch (BadResponseException $e) {
      $result = $e->getMessage();
    }
    return response()->json($result);
  }
}
