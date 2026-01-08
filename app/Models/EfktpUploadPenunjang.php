<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EfktpUploadPenunjang extends Model
{
    use HasFactory;
    protected $table = 'efktp_upload_penunjang';
    protected $guarded = ['id'];
    protected $fillable = ['id_kategori', 'no_rawat', 'nik', 'file'];

	function kategori() : BelongsTo
	{
		return $this->belongsTo(EfktpKategoriBerkasPenunjang::class, 'id_kategori')
			->select(['id', 'kategori']);
	}
}
