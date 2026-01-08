<?php

namespace App\Http\Controllers;

use App\Http\Requests\EfktpPaketObatRequest;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EfktpPaketObatController extends Controller
{
	public \App\Models\EfktpPaketObat $model;
	use Track, ResponseTrait;

	public function __construct(\App\Models\EfktpPaketObat $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('content.farmasi.paket-obat.paket_obat');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(EfktpPaketObatRequest $request)
	{
		$validate = $request->validated();

		try {
			DB::transaction(function () use ($validate, $request) {
				$create = $this->model->updateOrCreate(['id' => $request->id], $validate);
				if ($request->umum) {
					app(EfktpPaketObatUmumController::class)->storeMany($request->umum, $create->id);
				}
				if ($request->racik) {
					app(EfktpPaketObatRacikController::class)->storeMany($request->racik, $create->id);
				}
			});

		} catch (\Exception $e) {
			return $this->error(null, $e->getMessage());
		}
		return $this->success();

	}

	/**
	 * Display the specified resource.
	 */
	public function show(int|string $id)
	{
		$result = $this->model
			->with(['umum.databarang', 'racikan' => function ($q) {
				$q->with(['template.detail.barang', 'metode']);
			}, 'poliklinik'])
			->findOrFail($id);

		return $this->success($result);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$paket = $this->model->findOrFail($id);
		try {
			$result = $paket->delete();
			if ($result) {
				$this->deleteSql($this->model, ['id' => $id]);
			}
		} catch (\Exception $e) {
			return $this->error(null, $e->getMessage());
		}
		return $this->success();
	}

	public function datatable(Request $request)
	{
		$result = $this->model
			->with(['poliklinik', 'umum.databarang', 'racikan.template']);

		return DataTables::of($result)
			->filter(function ($query) use ($request) {
				if ($request->has('search') && $request->get('search')['value']) {
					return $query->whereHas('poliklinik', function ($query) use ($request) {
						$query->where('nm_poli', 'like', "%" . $request->get('search')['value'] . "%");
					})->orWhere('nama', 'like', "%" . $request->get('search')['value'] . "%");
				}
			})
			->make(true);
	}
}
