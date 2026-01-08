<?php

namespace AamDsam\Bpjs\PCare;

use AamDsam\Bpjs\PCare\PcareService;

class Alergi extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'alergi/jenis';

    public function jenis($keyword)
    {
        $this->feature .= "/{$keyword}";
        return $this;
    }
    public function prognosa($keyword)
    {
        $this->feature .= "/{$keyword}";
        return $this;
    }
}
