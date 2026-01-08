<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use App\Http\Controllers\Controller;

class Alergi extends Controller
{
	use PcareConfig;

	public $bpjs;
	public function __construct()
	{
		$this->bpjs = new Pcare\Alergi($this->config());
	}

	public function get($keyword)
	{
		$bpjs = $this->bpjs;
		return $bpjs->index($keyword);
	}
}
