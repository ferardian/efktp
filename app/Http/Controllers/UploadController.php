<?php

namespace App\Http\Controllers;

use App\Helpers\FormatNoRawat;
use App\Models\EfktpKategoriBerkasPenunjang;
use App\Models\EfktpUploadPenunjang;
use App\Traits\Track;
use http\Env\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class UploadController extends Controller
{
	use Track;
	function get(Request $request): JsonResponse
	{
		$upload = EfktpUploadPenunjang::where('no_rawat', $request->no_rawat)
			->with('kategori')
			->get();
		return response()->json($upload);
	}

	function upload(Request $request): JsonResponse
	{
		$create = array();
		if (! $request->kategori) {
			return response()->json('Belum memilih kategori berkas', 500);
		}
		try {
			$values = [];
			$stored = [];

			foreach ($request->file('file') as $key => $value) {
				$fileType = $value->getClientOriginalExtension();
				if ($fileType === 'pdf' || $fileType === 'PDF') {
					$destination = "penunjang/pdf";
				} else {
					$destination = "penunjang/images";
				}

				$fileName = Str::uuid().'.'.$fileType;
				$data = [
					'file' => $fileName,
					'id_kategori' => $request->kategori,
					'no_rawat' => $request->no_rawat,
					'nik' => session()->get('pegawai')->nik,
				];
				Storage::disk('public')->putFileAs($destination, $value, $fileName);
				$this->create($data);
			}
			return response()->json('SUKSES', 201);
		} catch (\Exception $e) {
			return response()->json($e->getMessage(), 500);
		}
	}
	function create($data): JsonResponse
	{
		try {
			$upload = EfktpUploadPenunjang::create($data);
			if ($upload) {
				$this->insertSql(new EfktpUploadPenunjang(), $data);
			}
			return response()->json('SUKSES', 201);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
	}
	function delete($id)
	{
		try {
			$kategoris = EfktpUploadPenunjang::where('id', $id);
			if ($kategoris->first()) {
				$kategori = $kategoris->first();

				$fileType = explode('.', $kategori->file);
				if ($fileType[1] === 'pdf') {
					Storage::delete("public/penunjang/pdf/".$kategori->file);
				} else {
					Storage::delete("public/penunjang/images/".$kategori->file);
				}
				$delete = $kategoris->delete();
			}
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
		return response()->json(['message' => 'SUKSES']);
	}
	function formatNoRawat($no_rawat, $replaceWith = ''): string
	{
		$no_rawat = str_replace('/', $replaceWith, $no_rawat);
		return $no_rawat;
	}
}
