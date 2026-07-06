<?php

namespace App\Http\Controllers;

use App\Action\CreateResepPaketAction;
use App\Action\GenerateNoResep;
use App\Models\ResepDokter;
use App\Models\ResepDokterRacikan;
use App\Models\ResepObat;
use App\Models\Setting;
use App\Traits\ResponseHandlerTrait;
use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ResepObatController extends Controller
{
	use Track, ResponseHandlerTrait;

	public function __construct()
	{

	}

	public function index()
	{
		return view('content.farmasi.resep.resepObat');
	}

	public function setNoResep(Request $request)
	{
		$resepObat = ResepObat::where(['no_rawat' => $request->no_rawat, 'tgl_peresepan' => date('Y-m-d')])->first();
		$generateNoResep = new GenerateNoResep();
		$no_resep = $resepObat ? $resepObat->no_resep : $generateNoResep->handle(new ResepObat());

		return $no_resep;
	}

	public function create(Request $request)
	{
		$data = [
			'no_rawat' => $request->no_rawat,
			'status' => $request->status,
			'kd_dokter' => $request->kd_dokter,
			'tgl_peresepan' => date('Y-m-d'),
			'jam_peresepan' => date('H:i:s'),
			'tgl_perawatan' => '0000-00-00',
			'jam' => '00:00:00',
			'tgl_penyerahan' => '0000-00-00',
			'jam_penyerahan' => '00:00:00',
			'no_resep' => $this->setNoResep($request),
		];

		try {
			$resep = ResepObat::create($data);
			if ($resep) {
				$this->insertSql(new ResepObat(), $data);
			}
			return response()->json($resep, 200);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
	}

	public function get(Request $request)
	{
		$resepObat = new ResepObat();
		if ($request->no_resep) {
			$resepObat = $resepObat->byNoResep($request->no_resep)->first();
		} else if ($request->no_rawat) {
			$resepObat = $resepObat->byNoRawat($request->no_rawat);
			if ($request->status) {
				$resepObat->where('status', $request->status);
			}
			$resepObat = $resepObat->get();
		} else if ($request->tgl_awal && $request->tgl_akhir) {
			$resepObat = ResepObat::whereBetween('tgl_peresepan', [
				date('Y-m-d', strtotime($request->tgl_awal)),
				date('Y-m-d', strtotime($request->tgl_akhir)),
			])->whereHas('regPeriksa', function ($query) {
				return $query->where('stts', 'Sudah');
			})
				->with('regPeriksa')
				->get();
		} else {
			$resepObat = ResepObat::where('tgl_peresepan', date('Y-m-d'))->get();
		}

		if ($request->dataTable) {
			return DataTables::of($resepObat)->make(true);
		}

		return response()->json($resepObat);
	}

	public function delete(Request $request)
	{
		$no_resep = $request->no_resep;
		$no_rawat = $request->no_rawat;
		try {
			$deleted = ResepObat::where(function ($query) use ($no_resep, $no_rawat) {
				if ($no_resep) {
					$query->where('no_resep', $no_resep);
				}
				if ($no_rawat) {
					$query->where('no_rawat', $no_rawat);
				}
			})->delete();

			if ($deleted) {
				$this->deleteSql(new ResepObat(), ['no_resep' => $no_resep, 'no_rawat' => $no_rawat]);
				return response()->json('Berhasil');
			} else {
				return response()->json('Tidak ada resep yang dihapus', 201);
			}
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}

	}

	public function print(Request $request)
	{
		$data = $this->get($request);
		$resepObat = ResepObat::where(['no_rawat' => $request->no_rawat])->with([
			'regPeriksa.pasien' => function ($query) {
				return $query->with(['kel', 'kec', 'kab', 'prop']);
			},
			'resepDokter.obat',
			'resepRacikan.detail.obat.satuan',
			'dokter'
		])->first();
		$setting = Setting::first();
		$pdf = PDF::loadView('content.print.resep', ['data' => $resepObat, 'setting' => $setting])
			->setPaper(array(0, 0, 283, 567.00))
			->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);
		return $pdf->stream('cetak resep.pdf');
	}

	public function setPenyerahan(Request $request)
	{
		$data = [
			'tgl_penyerahan' => date('Y-m-d'),
			'jam_penyerahan' => date('H:i:s'),
		];
		try {
			$resep = ResepObat::where('no_resep', $request->no_resep)->update($data);
			if ($resep) {
				$this->updateSql(new ResepObat(), $data, ['no_resep' => $request->no_resep]);
			}
			return response()->json('SUKSES', 201);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
	}

	public function isExist($no_rawat)
	{
		ResepObat::where('no_rawat', $no_rawat)->first();
	}

	public function copyResep($no_resep, Request $request)
	{
		return $isExist = $this->isExist($request->no_rawat);

		$resepObat = ResepDokter::where('no_resep', $no_resep)->get();
		$resepRacikan = ResepDokterRacikan::where('no_resep', $no_resep)
			->with('detail')
			->get();

		$dataUmum = collect($resepObat)->map(function ($item) {
			return [
				'no_resep' => 'ssss',
				'kode_brng' => $item->kode_brng,
				'jml' => $item->jml,
				'aturan_pakai' => $item->aturan_pakai
			];
		});


		return [$dataUmum, $resepRacikan];
	}

	public function createResepPaket(Request $request)
	{
		try {
			$paket = new CreateResepPaketAction();
			$resep = $paket->handle($request);
		} catch (\Exception $e) {
			$this->error(null, $e->getMessage());
		}
		return $this->success(['no_resep' => $resep]);
	}

	public function getUnvalidated(Request $request)
	{
		$no_rawat = $request->no_rawat;
		if (!$no_rawat) {
			return response()->json(['message' => 'no_rawat is required'], 400);
		}

		$reg = DB::table('reg_periksa')->where('no_rawat', $no_rawat)->first();
		if (!$reg) {
			return response()->json(['message' => 'Registration not found'], 404);
		}

		$bangsal = DB::table('set_depo_ralan')
			->where('kd_poli', $reg->kd_poli)
			->value('kd_bangsal');
		if (!$bangsal) {
			$set_lokasi = DB::table('set_lokasi')->first();
			$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
		}

		$resepObat = ResepObat::where('no_rawat', $no_rawat)
			->where(function ($query) {
				$query->where('tgl_perawatan', '0000-00-00')
					->orWhereNull('tgl_perawatan');
			})
			->with([
				'dokter',
				'resepDokter.obat.satuan',
				'resepRacikan.detail.obat.satuan',
				'resepRacikan.metode'
			])
			->get();

		$resepObat->map(function ($resep) use ($bangsal) {
			foreach ($resep->resepDokter as $rd) {
				if ($rd->obat) {
					$stok = DB::table('gudangbarang')
						->where('kode_brng', $rd->kode_brng)
						->where('kd_bangsal', $bangsal)
						->where('no_batch', '')
						->where('no_faktur', '')
						->value('stok') ?? 0;
					$capacity = floatval($rd->obat->kapasitas) > 0 ? floatval($rd->obat->kapasitas) : 1.0;
					$rd->stok = $stok * $capacity;
				} else {
					$rd->stok = 0;
				}
			}

			foreach ($resep->resepRacikan as $rr) {
				foreach ($rr->detail as $rrd) {
					if ($rrd->obat) {
						$stok = DB::table('gudangbarang')
							->where('kode_brng', $rrd->kode_brng)
							->where('kd_bangsal', $bangsal)
							->where('no_batch', '')
							->where('no_faktur', '')
							->value('stok') ?? 0;
						$rrd->stok = $stok;
					} else {
						$rrd->stok = 0;
					}
				}
			}
			return $resep;
		});

		return response()->json([
			'kd_bangsal' => $bangsal,
			'bangsal_name' => DB::table('bangsal')->where('kd_bangsal', $bangsal)->value('nm_bangsal') ?? '-',
			'resep' => $resepObat
		]);
	}

	public function validateResep(Request $request)
	{
		$no_resep = $request->no_resep;
		if (!$no_resep) {
			return response()->json(['message' => 'no_resep is required'], 400);
		}

		try {
			$result = DB::transaction(function () use ($no_resep) {
				$resep = ResepObat::where('no_resep', $no_resep)
					->where(function ($query) {
						$query->where('tgl_perawatan', '0000-00-00')
							->orWhereNull('tgl_perawatan');
					})
					->first();

				if (!$resep) {
					throw new \Exception('Resep tidak ditemukan atau sudah divalidasi');
				}

				$no_rawat = $resep->no_rawat;
				$reg = DB::table('reg_periksa')->where('no_rawat', $no_rawat)->first();
				if (!$reg) {
					throw new \Exception('Registrasi tidak ditemukan');
				}

				$bangsal = DB::table('set_depo_ralan')
					->where('kd_poli', $reg->kd_poli)
					->value('kd_bangsal');
				if (!$bangsal) {
					$set_lokasi = DB::table('set_lokasi')->first();
					$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
				}

				$tgl_perawatan = date('Y-m-d');
				$jam = date('H:i:s');

				$ttljual = 0;
				$ttlhpp = 0;

				$detailPemberianObatToInsert = [];
				$aturanPakaiToInsert = [];
				$obatRacikanToInsert = [];
				$detailObatRacikanToInsert = [];

				foreach ($resep->resepDokter as $rd) {
					$obat = DB::table('databarang')->where('kode_brng', $rd->kode_brng)->first();
					if (!$obat) {
						throw new \Exception('Barang/obat dengan kode ' . $rd->kode_brng . ' tidak ditemukan');
					}

					$qty = floatval($rd->jml);

					$stok = DB::table('gudangbarang')
						->where('kode_brng', $rd->kode_brng)
						->where('kd_bangsal', $bangsal)
						->where('no_batch', '')
						->where('no_faktur', '')
						->value('stok') ?? 0;

					if ($stok < $qty) {
						throw new \Exception('Stok obat "' . $obat->nama_brng . '" tidak cukup. Stok saat ini: ' . $stok . ' unit, dibutuhkan: ' . $qty);
					}

					DB::table('gudangbarang')
						->where('kode_brng', $rd->kode_brng)
						->where('kd_bangsal', $bangsal)
						->where('no_batch', '')
						->where('no_faktur', '')
						->decrement('stok', $qty);

					$biaya_obat = floatval($obat->ralan);
					$h_beli = floatval($obat->h_beli);
					$total_item = $biaya_obat * $qty;

					$ttljual += $total_item;
					$ttlhpp += $h_beli * $qty;

					$detailPemberianObatToInsert[] = [
						'tgl_perawatan' => $tgl_perawatan,
						'jam' => $jam,
						'no_rawat' => $no_rawat,
						'kode_brng' => $rd->kode_brng,
						'h_beli' => $h_beli,
						'biaya_obat' => $biaya_obat,
						'jml' => $qty,
						'embalase' => 0,
						'tuslah' => 0,
						'total' => $total_item,
						'status' => 'Ralan',
						'kd_bangsal' => $bangsal,
						'no_batch' => '',
						'no_faktur' => ''
					];

					if ($rd->aturan_pakai && trim($rd->aturan_pakai) !== '') {
						$aturanPakaiToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $rd->kode_brng,
							'aturan' => $rd->aturan_pakai
						];
					}
				}

				foreach ($resep->resepRacikan as $rr) {
					$obatRacikanToInsert[] = [
						'tgl_perawatan' => $tgl_perawatan,
						'jam' => $jam,
						'no_rawat' => $no_rawat,
						'no_racik' => $rr->no_racik,
						'nama_racik' => $rr->nama_racik,
						'kd_racik' => $rr->kd_racik,
						'jml_dr' => $rr->jml_dr,
						'aturan_pakai' => $rr->aturan_pakai,
						'keterangan' => $rr->keterangan ?? '-'
					];

					foreach ($rr->detail as $rrd) {
						$obat = DB::table('databarang')->where('kode_brng', $rrd->kode_brng)->first();
						if (!$obat) {
							throw new \Exception('Barang/obat racikan dengan kode ' . $rrd->kode_brng . ' tidak ditemukan');
						}

						$stok = DB::table('gudangbarang')
							->where('kode_brng', $rrd->kode_brng)
							->where('kd_bangsal', $bangsal)
							->where('no_batch', '')
							->where('no_faktur', '')
							->value('stok') ?? 0;

						if ($stok < $rrd->jml) {
							throw new \Exception('Stok obat racikan "' . $obat->nama_brng . '" tidak cukup. Stok saat ini: ' . $stok . ', dibutuhkan: ' . $rrd->jml);
						}

						DB::table('gudangbarang')
							->where('kode_brng', $rrd->kode_brng)
							->where('kd_bangsal', $bangsal)
							->where('no_batch', '')
							->where('no_faktur', '')
							->decrement('stok', $rrd->jml);

						$biaya_obat = floatval($obat->ralan);
						$h_beli = floatval($obat->h_beli);
						$qty = floatval($rrd->jml);
						$total_item = $biaya_obat * $qty;

						$ttljual += $total_item;
						$ttlhpp += $h_beli * $qty;

						$detailObatRacikanToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'no_racik' => $rr->no_racik,
							'kode_brng' => $rrd->kode_brng
						];

						$detailPemberianObatToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $rrd->kode_brng,
							'h_beli' => $h_beli,
							'biaya_obat' => $biaya_obat,
							'jml' => $qty,
							'embalase' => 0,
							'tuslah' => 0,
							'total' => $total_item,
							'status' => 'Ralan',
							'kd_bangsal' => $bangsal,
							'no_batch' => '',
							'no_faktur' => ''
						];
					}
				}

				if (!empty($detailPemberianObatToInsert)) {
					DB::table('detail_pemberian_obat')->insert($detailPemberianObatToInsert);
				}
				if (!empty($aturanPakaiToInsert)) {
					DB::table('aturan_pakai')->insert($aturanPakaiToInsert);
				}
				if (!empty($obatRacikanToInsert)) {
					DB::table('obat_racikan')->insert($obatRacikanToInsert);
				}
				if (!empty($detailObatRacikanToInsert)) {
					DB::table('detail_obat_racikan')->insert($detailObatRacikanToInsert);
				}

				DB::table('resep_obat')
					->where('no_resep', $no_resep)
					->update([
						'tgl_perawatan' => $tgl_perawatan,
						'jam' => $jam
					]);

				$this->postResepJurnal($no_rawat, $ttljual, $ttlhpp);

				return [
					'no_resep' => $no_resep,
					'no_rawat' => $no_rawat,
					'ttljual' => $ttljual,
					'ttlhpp' => $ttlhpp
				];
			});

			return response()->json([
				'status' => 'success',
				'message' => 'Resep berhasil divalidasi',
				'data' => $result
			], 200);

		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	private function postResepJurnal($no_rawat, $ttljual, $ttlhpp)
	{
		if ($ttljual <= 0 && $ttlhpp <= 0) {
			return;
		}

		$rekening = DB::table('set_akun_ralan')->first();
		if (!$rekening) {
			return;
		}

		DB::table('tampjurnal')->delete();

		if ($ttljual > 0) {
			DB::table('tampjurnal')->insert([
				[
					'kd_rek' => $rekening->Suspen_Piutang_Obat_Ralan,
					'nm_rek' => 'Suspen Piutang Obat Ralan',
					'debet' => $ttljual,
					'kredit' => 0
				],
				[
					'kd_rek' => $rekening->Obat_Ralan,
					'nm_rek' => 'Pendapatan Obat Rawat Jalan',
					'debet' => 0,
					'kredit' => $ttljual
				]
			]);
		}

		if ($ttlhpp > 0) {
			DB::table('tampjurnal')->insert([
				[
					'kd_rek' => $rekening->HPP_Obat_Rawat_Jalan,
					'nm_rek' => 'HPP Persediaan Obat Rawat Jalan',
					'debet' => $ttlhpp,
					'kredit' => 0
				],
				[
					'kd_rek' => $rekening->Persediaan_Obat_Rawat_Jalan,
					'nm_rek' => 'Persediaan Obat Rawat Jalan',
					'debet' => 0,
					'kredit' => $ttlhpp
				]
			]);
		}

		$date = date('Y-m-d');
		$date_formatted = date('Ymd');
		$count = DB::table('jurnal')->whereDate('tgl_jurnal', $date)->count();
		do {
			$count++;
			$no_jurnal = 'JR' . $date_formatted . str_pad($count, 6, '0', STR_PAD_LEFT);
		} while (DB::table('jurnal')->where('no_jurnal', $no_jurnal)->exists());

		$reg = DB::table('reg_periksa')
			->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
			->where('reg_periksa.no_rawat', $no_rawat)
			->select('pasien.nm_pasien', 'reg_periksa.no_rkm_medis')
			->first();
		$nm_pasien = $reg ? $reg->nm_pasien : '-';
		$no_rm = $reg ? $reg->no_rkm_medis : '-';

		$pegawai = session()->get('pegawai');
		$post_by = $pegawai ? $pegawai->nama : 'Dokter Mandiri';

		DB::table('jurnal')->insert([
			'no_jurnal' => $no_jurnal,
			'tgl_jurnal' => $date,
			'jam_jurnal' => date('H:i:s'),
			'no_bukti' => $no_rawat,
			'jenis' => 'U',
			'keterangan' => 'PEMBERIAN OBAT RAWAT JALAN PASIEN ' . $no_rm . ' ' . $nm_pasien . ', DIPOSTING OLEH ' . $post_by
		]);

		$tamp = DB::table('tampjurnal')->get();
		$detail = $tamp->map(function ($item) use ($no_jurnal) {
			return [
				'no_jurnal' => $no_jurnal,
				'kd_rek' => $item->kd_rek,
				'debet' => $item->debet,
				'kredit' => $item->kredit
			];
		})->toArray();

		if (!empty($detail)) {
			DB::table('detailjurnal')->insert($detail);
		}
	}

	public function rekapIndex(Request $request)
	{
		$poliklinik = \App\Models\Poliklinik::active()->orderBy('nm_poli', 'asc')->get();
		$dokter = \App\Models\Dokter::where('status', '1')->orderBy('nm_dokter', 'asc')->get();
		return view('content.farmasi.resep.rekapResep', compact('poliklinik', 'dokter'));
	}

	public function rekapData(Request $request)
	{
		$data = $this->getRekapQueryData($request);
		return DataTables::of($data)->addIndexColumn()->make(true);
	}

	private function getRekapQueryData(Request $request)
	{
		$tgl_awal = $request->tgl_awal ? date('Y-m-d', strtotime($request->tgl_awal)) : date('Y-m-d');
		$tgl_akhir = $request->tgl_akhir ? date('Y-m-d', strtotime($request->tgl_akhir)) : date('Y-m-d');
		$kd_poli = $request->kd_poli;
		$kd_dokter = $request->kd_dokter;
		$status_validasi = $request->status_validasi;

		$subQueryNonRacikan = DB::table('resep_dokter as rd')
			->join('resep_obat as ro', 'rd.no_resep', '=', 'ro.no_resep')
			->join('reg_periksa as rp', 'ro.no_rawat', '=', 'rp.no_rawat')
			->select('rd.kode_brng', 'rd.jml', 'ro.tgl_peresepan', 'ro.tgl_perawatan', 'rp.kd_poli', 'ro.kd_dokter');

		$subQueryRacikan = DB::table('resep_dokter_racikan_detail as rrd')
			->join('resep_obat as ro', 'rrd.no_resep', '=', 'ro.no_resep')
			->join('reg_periksa as rp', 'ro.no_rawat', '=', 'rp.no_rawat')
			->select('rrd.kode_brng', 'rrd.jml', 'ro.tgl_peresepan', 'ro.tgl_perawatan', 'rp.kd_poli', 'ro.kd_dokter');

		$unionQuery = $subQueryNonRacikan->unionAll($subQueryRacikan);

		$query = DB::table(DB::raw("({$unionQuery->toSql()}) as detail"))
			->mergeBindings($unionQuery)
			->join('databarang as db', 'detail.kode_brng', '=', 'db.kode_brng')
			->leftJoin('kodesatuan as ks', 'db.kode_sat', '=', 'ks.kode_sat')
			->select('db.kode_brng', 'db.nama_brng', 'ks.satuan', DB::raw('SUM(detail.jml) as total_qty'))
			->whereBetween('detail.tgl_peresepan', [$tgl_awal, $tgl_akhir]);

		if ($kd_poli) {
			$query->where('detail.kd_poli', $kd_poli);
		}
		if ($kd_dokter) {
			$query->where('detail.kd_dokter', $kd_dokter);
		}
		if ($status_validasi === 'belum') {
			$query->where(function ($q) {
				$q->where('detail.tgl_perawatan', '0000-00-00')
					->orWhereNull('detail.tgl_perawatan');
			});
		} elseif ($status_validasi === 'sudah') {
			$query->where('detail.tgl_perawatan', '!=', '0000-00-00')
				->whereNotNull('detail.tgl_perawatan');
		}

		return $query->groupBy('db.kode_brng', 'db.nama_brng', 'ks.satuan')
			->orderBy('db.nama_brng', 'asc')
			->get();
	}


	public function rekapPdf(Request $request)
	{
		$data = $this->getRekapQueryData($request);

		$poliName = 'Semua Poliklinik';
		if ($request->kd_poli) {
			$poliName = DB::table('poliklinik')->where('kd_poli', $request->kd_poli)->value('nm_poli') ?? 'Poliklinik';
		}
		$dokterName = 'Semua Dokter';
		if ($request->kd_dokter) {
			$dokterName = DB::table('dokter')->where('kd_dokter', $request->kd_dokter)->value('nm_dokter') ?? 'Dokter';
		}

		$tgl_awal = $request->tgl_awal ?? date('d-m-Y');
		$tgl_akhir = $request->tgl_akhir ?? date('d-m-Y');
		$status = $request->status_validasi == 'belum' ? 'Belum Validasi' : ($request->status_validasi == 'sudah' ? 'Sudah Validasi' : 'Semua Status');

		$setting = Setting::first();
		$pdf = PDF::loadView('content.print.rekapResepPdf', compact('data', 'poliName', 'dokterName', 'tgl_awal', 'tgl_akhir', 'status', 'setting'))
			->setPaper('A4', 'portrait')
			->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

		return $pdf->stream('rekap_resep_obat.pdf');
	}
}
