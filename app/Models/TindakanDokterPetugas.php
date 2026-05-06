<?php
namespace App\Models;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
class TindakanDokterPetugas extends Model {
    use Compoships;
    protected $table = 'rawat_jl_drpr';
    protected $guarded = [];
    public $timestamps = false;
    function tindakan() { return $this->belongsTo(JenisPerawatan::class, 'kd_jenis_prw', 'kd_jenis_prw'); }
    function dokter() { return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter'); }
    function petugas() { return $this->belongsTo(Petugas::class, 'nip', 'nip'); }
    function regPeriksa() { return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat'); }
}
