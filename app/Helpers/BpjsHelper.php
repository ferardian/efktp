<?php

namespace App\Helpers;

class BpjsHelper
{
    /**
     * Decompress string from BPJS (LZ-String)
     */
    public static function decompress(string $string): string
    {
        return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
    }
}
