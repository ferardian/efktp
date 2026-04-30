<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\PCare\PCarePeserta;

class Peserta extends Controller
{
    protected PCarePeserta $peserta;

    public function __construct()
    {
        $this->peserta = new PCarePeserta();
    }

    public function index(string $value, string $type = null)
    {
        // Auto deteksi jika type tidak dikirim
        if (!$type) {
            $type = strlen($value) == 16 ? 'nik' : 'noka';
        }

        if ($type == 'nik') {
            return $this->peserta->getByNik($value);
        }

        return $this->peserta->getByNoKartu($value);
    }
}
