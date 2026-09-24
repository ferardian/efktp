<?php

namespace App\Http\Controllers;

use App\Action\CreateResepPaketAction;
use App\Action\GenerateNoResep;
use App\Models\ResepDokter;
use App\Models\ResepDokterRacikan;
use App\Models\ResepDokterRacikanDetail;
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
		$resepObat = ResepObat::where([
			'no_rawat' => $request->no_rawat,
			'tgl_peresepan' => date('Y-m-d'),
			'jam' => '00:00:00',
		])->first();
		$generateNoResep = new GenerateNoResep();
		$no_resep = $resepObat ? $resepObat->no_resep : $generateNoResep->handle(new ResepObat());

		return $no_resep;
	}

	public function create(Request $request)
	{
		$no_rawat = $request->no_rawat;
		$kd_dokter = $request->kd_dokter;

		// Validasi apakah kd_dokter valid dan terdaftar di tabel dokter
		$isDokter = $kd_dokter ? DB::table('dokter')->where('kd_dokter', $kd_dokter)->exists() : false;

		if (!$isDokter) {
			// Jika user login bukan dokter (misal login sebagai admin atau perawat),
			// gunakan dokter penanggung jawab (DPJP) atau dokter pemeriksaan pasien:
			// 1. Cek DPJP Ranap jika status ranap
			if ($request->status === 'ranap') {
				$dpjp = DB::table('dpjp_ranap')->where('no_rawat', $no_rawat)->value('kd_dokter');
				if ($dpjp && DB::table('dokter')->where('kd_dokter', $dpjp)->exists()) {
					$kd_dokter = $dpjp;
				}
			}

			// 2. Cek dokter pada reg_periksa pasien
			if (!$kd_dokter || !DB::table('dokter')->where('kd_dokter', $kd_dokter)->exists()) {
				$regDokter = DB::table('reg_periksa')->where('no_rawat', $no_rawat)->value('kd_dokter');
				if ($regDokter && DB::table('dokter')->where('kd_dokter', $regDokter)->exists()) {
					$kd_dokter = $regDokter;
				}
			}

			// 3. Fallback: ambil dokter aktif pertama di database
			if (!$kd_dokter || !DB::table('dokter')->where('kd_dokter', $kd_dokter)->exists()) {
				$kd_dokter = DB::table('dokter')->where('status', '1')->value('kd_dokter');
			}
		}

		if (!$kd_dokter) {
			return response()->json(['message' => 'Dokter tidak ditemukan untuk peresepan obat ini'], 422);
		}

		$data = [
			'no_rawat' => $no_rawat,
			'status' => $request->status,
			'kd_dokter' => $kd_dokter,
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
		} else {
			$query = ResepObat::query();

			if ($request->tgl_awal && $request->tgl_akhir) {
				$query->whereBetween('tgl_peresepan', [
					date('Y-m-d', strtotime($request->tgl_awal)),
					date('Y-m-d', strtotime($request->tgl_akhir)),
				]);
			} else {
				$query->where('tgl_peresepan', date('Y-m-d'));
			}

			if ($request->status_rawat && $request->status_rawat !== 'semua') {
				$query->where('status', $request->status_rawat);
			}

			$resepObat = $query->with([
				'regPeriksa.pasien',
				'regPeriksa.poliklinik',
				'regPeriksa.dokter',
				'regPeriksa.penjab',
				'regPeriksa.kamarInap.kamar.bangsal',
			])
			->orderByDesc('tgl_peresepan')
			->orderByDesc('jam_peresepan')
			->get();
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

	/**
	 * Hitung total stok obat di suatu bangsal/depo (mencakup seluruh batch/faktur)
	 */
	private function getStokObatBangsal(string $kode_brng, string $kd_bangsal): float
	{
		return (float) (DB::table('gudangbarang')
			->where('kode_brng', $kode_brng)
			->where('kd_bangsal', $kd_bangsal)
			->sum('stok') ?? 0);
	}

	/**
	 * Hitung harga obat berdasarkan status pelayanan (Ranap vs Ralan) dan kelas kamar ranap.
	 */
	private function calculateHargaObat($obat, bool $isRanap, string $kelas = ''): float
	{
		if ($isRanap) {
			$kelasLower = strtolower($kelas);
			$harga = 0;
			if (str_contains($kelasLower, '1')) {
				$harga = floatval($obat->kelas1);
			} elseif (str_contains($kelasLower, '2')) {
				$harga = floatval($obat->kelas2);
			} elseif (str_contains($kelasLower, '3')) {
				$harga = floatval($obat->kelas3);
			} elseif (str_contains($kelasLower, 'vvip')) {
				$harga = floatval($obat->vvip);
			} elseif (str_contains($kelasLower, 'vip')) {
				$harga = floatval($obat->vip);
			} elseif (str_contains($kelasLower, 'utama')) {
				$harga = floatval($obat->utama);
			}

			if ($harga <= 0) {
				$harga = floatval($obat->ralan > 0 ? $obat->ralan : ($obat->h_beli > 0 ? $obat->h_beli : 0));
			}
			return $harga;
		}

		return floatval($obat->ralan > 0 ? $obat->ralan : ($obat->h_beli > 0 ? $obat->h_beli : 0));
	}

	/**
	 * Potong stok obat dari gudangbarang di bangsal tertentu secara cerdas (mendukung batch/faktur).
	 * Mengutamakan record tanpa batch atau record yang memiliki stok > 0 (FIFO).
	 * Mengembalikan array batch yang terpotong untuk dicatat ke detail_pemberian_obat:
	 * [
	 *    ['no_batch' => '...', 'no_faktur' => '...', 'jml' => ...],
	 *    ...
	 * ]
	 */
	private function deductStokObat(string $kode_brng, string $kd_bangsal, float $qtyToDeduct, string $nama_brng = '', string $keterangan = ''): array
	{
		$totalStok = $this->getStokObatBangsal($kode_brng, $kd_bangsal);
		if ($totalStok < $qtyToDeduct) {
			$name = $nama_brng ?: $kode_brng;
			throw new \Exception("Stok obat \"{$name}\" tidak cukup. Stok saat ini: {$totalStok} unit, dibutuhkan: {$qtyToDeduct}");
		}

		$pegawai = session()->get('pegawai');
		$petugas = $pegawai ? ($pegawai->nik ?? $pegawai->nama ?? 'Admin') : 'Admin';

		$gudangRows = DB::table('gudangbarang')
			->where('kode_brng', $kode_brng)
			->where('kd_bangsal', $kd_bangsal)
			->where('stok', '>', 0)
			->orderByRaw("CASE WHEN no_batch = '' OR no_batch IS NULL THEN 0 ELSE 1 END ASC")
			->orderBy('no_batch', 'ASC')
			->get();

		$remaining = $qtyToDeduct;
		$deductions = [];

		foreach ($gudangRows as $row) {
			if ($remaining <= 0) break;

			$take = min((float) $row->stok, $remaining);
			$stokAwal = (float) $row->stok;
			$stokAkhir = $stokAwal - $take;

			DB::table('gudangbarang')
				->where('kode_brng', $kode_brng)
				->where('kd_bangsal', $kd_bangsal)
				->where('no_batch', $row->no_batch ?? '')
				->where('no_faktur', $row->no_faktur ?? '')
				->decrement('stok', $take);

			DB::table('riwayat_barang_medis')->insert([
				'kode_brng'  => $kode_brng,
				'stok_awal'  => $stokAwal,
				'masuk'      => 0,
				'keluar'     => $take,
				'stok_akhir' => $stokAkhir,
				'posisi'     => 'Pemberian Obat',
				'tanggal'    => date('Y-m-d'),
				'jam'        => date('H:i:s'),
				'petugas'    => $petugas,
				'kd_bangsal' => $kd_bangsal,
				'status'     => 'Simpan',
				'no_batch'   => $row->no_batch ?? '',
				'no_faktur'  => $row->no_faktur ?? '',
				'keterangan' => substr($keterangan ?: "Validasi Resep: {$kode_brng}", 0, 150),
			]);

			$deductions[] = [
				'no_batch'  => $row->no_batch ?? '',
				'no_faktur' => $row->no_faktur ?? '',
				'jml'       => $take,
			];

			$remaining -= $take;
		}

		if ($remaining > 0) {
			$defaultRow = DB::table('gudangbarang')
				->where('kode_brng', $kode_brng)
				->where('kd_bangsal', $kd_bangsal)
				->where('no_batch', '')
				->where('no_faktur', '')
				->first();

			$stokAwal = $defaultRow ? (float) $defaultRow->stok : 0;
			$stokAkhir = $stokAwal - $remaining;

			if ($defaultRow) {
				DB::table('gudangbarang')
					->where('kode_brng', $kode_brng)
					->where('kd_bangsal', $kd_bangsal)
					->where('no_batch', '')
					->where('no_faktur', '')
					->decrement('stok', $remaining);
			} else {
				DB::table('gudangbarang')->insert([
					'kode_brng'  => $kode_brng,
					'kd_bangsal' => $kd_bangsal,
					'stok'       => -$remaining,
					'no_batch'   => '',
					'no_faktur'  => '',
				]);
			}

			DB::table('riwayat_barang_medis')->insert([
				'kode_brng'  => $kode_brng,
				'stok_awal'  => $stokAwal,
				'masuk'      => 0,
				'keluar'     => $remaining,
				'stok_akhir' => $stokAkhir,
				'posisi'     => 'Pemberian Obat',
				'tanggal'    => date('Y-m-d'),
				'jam'        => date('H:i:s'),
				'petugas'    => $petugas,
				'kd_bangsal' => $kd_bangsal,
				'status'     => 'Simpan',
				'no_batch'   => '',
				'no_faktur'  => '',
				'keterangan' => substr($keterangan ?: "Validasi Resep: {$kode_brng}", 0, 150),
			]);

			$deductions[] = [
				'no_batch'  => '',
				'no_faktur' => '',
				'jml'       => $remaining,
			];
		}

		return $deductions;
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

		$resepFirst = ResepObat::where('no_rawat', $no_rawat)
			->where(function ($query) {
				$query->where('tgl_perawatan', '0000-00-00')
					->orWhereNull('tgl_perawatan');
			})->first();

		$isRanap = ($resepFirst && $resepFirst->status === 'ranap') || ($request->status === 'ranap');

		$kamarInfo = null;
		$kamarKelas = '';
		if ($isRanap) {
			$kamarRow = DB::table('kamar_inap')
				->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
				->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
				->where('kamar_inap.no_rawat', $no_rawat)
				->orderByDesc('kamar_inap.tgl_masuk')
				->orderByDesc('kamar_inap.jam_masuk')
				->select('kamar.kd_kamar', 'kamar.kelas', 'kamar.kd_bangsal', 'bangsal.nm_bangsal')
				->first();

			if ($kamarRow) {
				$kamarKelas = $kamarRow->kelas;
				$kamarInfo = "{$kamarRow->kd_kamar} - {$kamarRow->nm_bangsal} ({$kamarRow->kelas})";
				$depoRanap = DB::table('set_depo_ranap')->where('kd_bangsal', $kamarRow->kd_bangsal)->value('kd_depo');
				$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
				$bangsal = $depoRanap ?: $defaultApotek;
			} else {
				$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
				$bangsal = $defaultApotek;
			}
		} else {
			$bangsal = DB::table('set_depo_ralan')
				->where('kd_poli', $reg->kd_poli)
				->value('kd_bangsal');
			if (!$bangsal) {
				$set_lokasi = DB::table('set_lokasi')->first();
				$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
			}
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

		$resepObat->map(function ($resep) use ($bangsal, $isRanap, $kamarKelas) {
			foreach ($resep->resepDokter as $rd) {
				if ($rd->obat) {
					$stok = $this->getStokObatBangsal($rd->kode_brng, $bangsal);
					$capacity = floatval($rd->obat->kapasitas) > 0 ? floatval($rd->obat->kapasitas) : 1.0;
					$rd->stok = $stok * $capacity;
					$rd->biaya_obat = $this->calculateHargaObat($rd->obat, $isRanap, $kamarKelas);
				} else {
					$rd->stok = 0;
					$rd->biaya_obat = 0;
				}
			}

			foreach ($resep->resepRacikan as $rr) {
				foreach ($rr->detail as $rrd) {
					if ($rrd->obat) {
						$stok = $this->getStokObatBangsal($rrd->kode_brng, $bangsal);
						$rrd->stok = $stok;
						$rrd->biaya_obat = $this->calculateHargaObat($rrd->obat, $isRanap, $kamarKelas);
					} else {
						$rrd->stok = 0;
						$rrd->biaya_obat = 0;
					}
				}
			}
			return $resep;
		});

		return response()->json([
			'kd_bangsal' => $bangsal,
			'bangsal_name' => DB::table('bangsal')->where('kd_bangsal', $bangsal)->value('nm_bangsal') ?? '-',
			'is_ranap' => $isRanap,
			'kamar_info' => $kamarInfo,
			'kelas' => $kamarKelas,
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

				$isRanap = ($resep->status === 'ranap');

				// Cek proteksi billing terkunci
				if ($isRanap) {
					$isLocked = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
					if ($isLocked) {
						throw new \Exception('Billing Rawat Inap pasien sudah diselesaikan di Kasir. Resep tidak dapat divalidasi.');
					}
				} else {
					$isLocked = DB::table('nota_jalan')->where('no_rawat', $no_rawat)->exists();
					if ($isLocked) {
						throw new \Exception('Billing Rawat Jalan pasien sudah diselesaikan di Kasir. Resep tidak dapat divalidasi.');
					}
				}

				$kamarKelas = '';
				if ($isRanap) {
					$kamarRow = DB::table('kamar_inap')
						->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
						->where('kamar_inap.no_rawat', $no_rawat)
						->orderByDesc('kamar_inap.tgl_masuk')
						->orderByDesc('kamar_inap.jam_masuk')
						->select('kamar.kd_bangsal', 'kamar.kelas')
						->first();

					$kamarKelas = $kamarRow ? $kamarRow->kelas : '';
					$kdBangsalKamar = $kamarRow ? $kamarRow->kd_bangsal : null;

					$depoRanap = $kdBangsalKamar ? DB::table('set_depo_ranap')->where('kd_bangsal', $kdBangsalKamar)->value('kd_depo') : null;
					$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
					$bangsal = $depoRanap ?: $defaultApotek;
				} else {
					$bangsal = DB::table('set_depo_ralan')
						->where('kd_poli', $reg->kd_poli)
						->value('kd_bangsal');
					if (!$bangsal) {
						$set_lokasi = DB::table('set_lokasi')->first();
						$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
					}
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
					if ($qty <= 0) continue;

					$deductions = $this->deductStokObat($rd->kode_brng, $bangsal, $qty, $obat->nama_brng, "Validasi Resep {$no_resep}: {$no_rawat}");

					$biaya_obat = $this->calculateHargaObat($obat, $isRanap, $kamarKelas);
					$h_beli = floatval($obat->h_beli);

					foreach ($deductions as $d) {
						$subQty = floatval($d['jml']);
						$total_item = $biaya_obat * $subQty;

						$ttljual += $total_item;
						$ttlhpp += $h_beli * $subQty;

						$detailPemberianObatToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $rd->kode_brng,
							'h_beli' => $h_beli,
							'biaya_obat' => $biaya_obat,
							'jml' => $subQty,
							'embalase' => 0,
							'tuslah' => 0,
							'total' => $total_item,
							'status' => $isRanap ? 'Ranap' : 'Ralan',
							'kd_bangsal' => $bangsal,
							'no_batch' => $d['no_batch'] ?? '',
							'no_faktur' => $d['no_faktur'] ?? ''
						];
					}

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

						$qty = floatval($rrd->jml);
						if ($qty <= 0) continue;

						$deductions = $this->deductStokObat($rrd->kode_brng, $bangsal, $qty, $obat->nama_brng, "Validasi Resep {$no_resep}: {$no_rawat}");

						$biaya_obat = $this->calculateHargaObat($obat, $isRanap, $kamarKelas);
						$h_beli = floatval($obat->h_beli);

						foreach ($deductions as $d) {
							$subQty = floatval($d['jml']);
							$total_item = $biaya_obat * $subQty;

							$ttljual += $total_item;
							$ttlhpp += $h_beli * $subQty;

							$detailPemberianObatToInsert[] = [
								'tgl_perawatan' => $tgl_perawatan,
								'jam' => $jam,
								'no_rawat' => $no_rawat,
								'kode_brng' => $rrd->kode_brng,
								'h_beli' => $h_beli,
								'biaya_obat' => $biaya_obat,
								'jml' => $subQty,
								'embalase' => 0,
								'tuslah' => 0,
								'total' => $total_item,
								'status' => $isRanap ? 'Ranap' : 'Ralan',
								'kd_bangsal' => $bangsal,
								'no_batch' => $d['no_batch'] ?? '',
								'no_faktur' => $d['no_faktur'] ?? ''
							];
						}

						$detailObatRacikanToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'no_racik' => $rr->no_racik,
							'kode_brng' => $rrd->kode_brng
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

				if (!$isRanap) {
					$this->postResepJurnal($no_rawat, $ttljual, $ttlhpp);
				}

				return [
					'no_resep' => $no_resep,
					'no_rawat' => $no_rawat,
					'status' => $isRanap ? 'Ranap' : 'Ralan',
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
			->leftJoin('golongan_barang as gb', 'db.kode_golongan', '=', 'gb.kode')
			->select('db.kode_brng', 'db.nama_brng', 'db.kode_golongan', 'gb.nama as nama_golongan', 'ks.satuan', DB::raw('SUM(detail.jml) as total_qty'))
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

		return $query->groupBy('db.kode_brng', 'db.nama_brng', 'db.kode_golongan', 'gb.nama', 'ks.satuan')
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

	public function getDetailValidation(Request $request)
	{
		$no_resep = $request->no_resep;
		if (!$no_resep) {
			return response()->json(['message' => 'no_resep is required'], 400);
		}

		$resep = ResepObat::where('no_resep', $no_resep)
			->with([
				'dokter',
				'regPeriksa.pasien',
				'regPeriksa.poliklinik',
				'regPeriksa.penjab',
				'regPeriksa.kamarInap.kamar.bangsal',
				'resepDokter.obat.satuan',
				'resepDokter.obat.golongan',
				'resepRacikan.detail.obat.satuan',
				'resepRacikan.detail.obat.golongan',
				'resepRacikan.metode'
			])
			->first();

		if (!$resep) {
			return response()->json(['message' => 'Resep tidak ditemukan'], 404);
		}

		$isRanap = ($resep->status === 'ranap');
		$kamarInfo = null;
		$kamarKelas = '';

		if ($isRanap) {
			$kamarRow = DB::table('kamar_inap')
				->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
				->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
				->where('kamar_inap.no_rawat', $resep->no_rawat)
				->orderByDesc('kamar_inap.tgl_masuk')
				->orderByDesc('kamar_inap.jam_masuk')
				->select('kamar.kd_kamar', 'kamar.kelas', 'kamar.kd_bangsal', 'bangsal.nm_bangsal')
				->first();

			if ($kamarRow) {
				$kamarKelas = $kamarRow->kelas;
				$kamarInfo = "{$kamarRow->kd_kamar} - {$kamarRow->nm_bangsal} ({$kamarRow->kelas})";
				$depoRanap = DB::table('set_depo_ranap')->where('kd_bangsal', $kamarRow->kd_bangsal)->value('kd_depo');
				$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
				$bangsal = $depoRanap ?: $defaultApotek;
			} else {
				$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
				$bangsal = $defaultApotek;
			}
		} else {
			$bangsal = DB::table('set_depo_ralan')
				->where('kd_poli', $resep->regPeriksa->kd_poli ?? '')
				->value('kd_bangsal');
			if (!$bangsal) {
				$set_lokasi = DB::table('set_lokasi')->first();
				$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
			}
		}

		$nm_bangsal = DB::table('bangsal')->where('kd_bangsal', $bangsal)->value('nm_bangsal') ?? $bangsal;

		foreach ($resep->resepDokter as $rd) {
			if ($rd->obat) {
				$stok = $this->getStokObatBangsal($rd->kode_brng, $bangsal);
				$capacity = floatval($rd->obat->kapasitas) > 0 ? floatval($rd->obat->kapasitas) : 1.0;
				$rd->stok = $stok * $capacity;
				$rd->biaya_obat = $this->calculateHargaObat($rd->obat, $isRanap, $kamarKelas);
			} else {
				$rd->stok = 0;
				$rd->biaya_obat = 0;
			}
		}

		foreach ($resep->resepRacikan as $rr) {
			foreach ($rr->detail as $rrd) {
				if ($rrd->obat) {
					$stok = $this->getStokObatBangsal($rrd->kode_brng, $bangsal);
					$rrd->stok = $stok;
					$rrd->biaya_obat = $this->calculateHargaObat($rrd->obat, $isRanap, $kamarKelas);
				} else {
					$rrd->stok = 0;
					$rrd->biaya_obat = 0;
				}
			}
		}

		return response()->json([
			'kd_bangsal' => $bangsal,
			'nm_bangsal' => $nm_bangsal,
			'is_ranap' => $isRanap,
			'kamar_info' => $kamarInfo,
			'kelas' => $kamarKelas,
			'resep' => $resep
		]);
	}

	public function validateResepWithAdjust(Request $request)
	{
		$no_resep = $request->no_resep;
		if (!$no_resep) {
			return response()->json(['message' => 'no_resep is required'], 400);
		}

		$items_non_racik = $request->input('items_non_racik', []);
		$items_racik_detail = $request->input('items_racik_detail', []);
		$items_racik = $request->input('items_racik', []);

		try {
			$result = DB::transaction(function () use ($no_resep, $items_non_racik, $items_racik_detail, $items_racik) {
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

				$isRanap = ($resep->status === 'ranap');

				// Cek proteksi billing terkunci
				if ($isRanap) {
					$isLocked = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
					if ($isLocked) {
						throw new \Exception('Billing Rawat Inap pasien sudah diselesaikan di Kasir. Resep tidak dapat divalidasi/diubah.');
					}
				} else {
					$isLocked = DB::table('nota_jalan')->where('no_rawat', $no_rawat)->exists();
					if ($isLocked) {
						throw new \Exception('Billing Rawat Jalan pasien sudah diselesaikan di Kasir. Resep tidak dapat divalidasi/diubah.');
					}
				}

				$kamarKelas = '';
				if ($isRanap) {
					$kamarRow = DB::table('kamar_inap')
						->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
						->where('kamar_inap.no_rawat', $no_rawat)
						->orderByDesc('kamar_inap.tgl_masuk')
						->orderByDesc('kamar_inap.jam_masuk')
						->select('kamar.kd_bangsal', 'kamar.kelas')
						->first();

					$kamarKelas = $kamarRow ? $kamarRow->kelas : '';
					$kdBangsalKamar = $kamarRow ? $kamarRow->kd_bangsal : null;

					$depoRanap = $kdBangsalKamar ? DB::table('set_depo_ranap')->where('kd_bangsal', $kdBangsalKamar)->value('kd_depo') : null;
					$defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
					$bangsal = $depoRanap ?: $defaultApotek;
				} else {
					$bangsal = DB::table('set_depo_ralan')
						->where('kd_poli', $reg->kd_poli)
						->value('kd_bangsal');
					if (!$bangsal) {
						$set_lokasi = DB::table('set_lokasi')->first();
						$bangsal = $set_lokasi ? $set_lokasi->kd_bangsal : 'AP';
					}
				}

				$tgl_perawatan = date('Y-m-d');
				$jam = date('H:i:s');

				$ttljual = 0;
				$ttlhpp = 0;

				$detailPemberianObatToInsert = [];
				$aturanPakaiToInsert = [];
				$obatRacikanToInsert = [];
				$detailObatRacikanToInsert = [];

				// 1. Proses Obat Non-Racikan
				foreach ($items_non_racik as $item) {
					$kode_brng = $item['kode_brng'] ?? '';
					$qty = floatval($item['jml'] ?? 0);
					$aturan = $item['aturan_pakai'] ?? '';
					$is_deleted = !empty($item['is_deleted']);

					if (!$kode_brng || $is_deleted || $qty <= 0) {
						continue;
					}

					$obat = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
					if (!$obat) {
						throw new \Exception('Barang/obat dengan kode ' . $kode_brng . ' tidak ditemukan');
					}

					$deductions = $this->deductStokObat($kode_brng, $bangsal, $qty, $obat->nama_brng, "Validasi Resep {$no_resep}: {$no_rawat}");

					$biaya_obat = $this->calculateHargaObat($obat, $isRanap, $kamarKelas);
					$h_beli = floatval($obat->h_beli);

					foreach ($deductions as $d) {
						$subQty = floatval($d['jml']);
						$total_item = $biaya_obat * $subQty;

						$ttljual += $total_item;
						$ttlhpp += $h_beli * $subQty;

						$detailPemberianObatToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $kode_brng,
							'h_beli' => $h_beli,
							'biaya_obat' => $biaya_obat,
							'jml' => $subQty,
							'embalase' => 0,
							'tuslah' => 0,
							'total' => $total_item,
							'status' => $isRanap ? 'Ranap' : 'Ralan',
							'kd_bangsal' => $bangsal,
							'no_batch' => $d['no_batch'] ?? '',
							'no_faktur' => $d['no_faktur'] ?? ''
						];
					}

					if ($aturan && trim($aturan) !== '') {
						$aturanPakaiToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $kode_brng,
							'aturan' => $aturan
						];
					}
				}

				// 2. Ambil master racikan dokter asli untuk metadata racikan (nama_racik, kd_racik, keterangan)
				$existingRacik = DB::table('resep_dokter_racikan')
					->where('no_resep', $no_resep)
					->get()
					->keyBy('no_racik');

				$racikMap = [];
				foreach ($existingRacik as $noRacik => $rdr) {
					$racikMap[$noRacik] = [
						'no_racik' => $noRacik,
						'nama_racik' => $rdr->nama_racik,
						'kd_racik' => $rdr->kd_racik,
						'jml_dr' => floatval($rdr->jml_dr),
						'aturan_pakai' => $rdr->aturan_pakai,
						'keterangan' => $rdr->keterangan ?? '-'
					];
				}

				// Update data racikan jika disesuaikan pada modal (jml_dr atau aturan_pakai)
				foreach ($items_racik as $ir) {
					$nr = $ir['no_racik'] ?? null;
					if (!$nr) continue;
					if (isset($racikMap[$nr])) {
						if (isset($ir['jml_dr'])) {
							$racikMap[$nr]['jml_dr'] = floatval($ir['jml_dr']);
						}
						if (isset($ir['aturan_pakai'])) {
							$racikMap[$nr]['aturan_pakai'] = $ir['aturan_pakai'];
						}
					} else {
						$racikMap[$nr] = [
							'no_racik' => $nr,
							'nama_racik' => 'Racikan ' . $nr,
							'kd_racik' => 'R01',
							'jml_dr' => floatval($ir['jml_dr'] ?? 1),
							'aturan_pakai' => $ir['aturan_pakai'] ?? '',
							'keterangan' => '-'
						];
					}
				}

				// 3. Proses Detail Bahan Racikan & Header Racikan
				$processedRacikHeaders = [];

				foreach ($items_racik_detail as $ird) {
					$no_racik = $ird['no_racik'] ?? '';
					$kode_brng = $ird['kode_brng'] ?? '';
					$qty = floatval($ird['jml'] ?? 0);
					$is_deleted = !empty($ird['is_deleted']);

					if (!$no_racik || !$kode_brng || $is_deleted || $qty <= 0) {
						continue;
					}

					$obat = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
					if (!$obat) {
						throw new \Exception('Barang/obat racikan dengan kode ' . $kode_brng . ' tidak ditemukan');
					}

					$deductions = $this->deductStokObat($kode_brng, $bangsal, $qty, $obat->nama_brng, "Validasi Resep {$no_resep}: {$no_rawat}");

					$biaya_obat = $this->calculateHargaObat($obat, $isRanap, $kamarKelas);
					$h_beli = floatval($obat->h_beli);

					foreach ($deductions as $d) {
						$subQty = floatval($d['jml']);
						$total_item = $biaya_obat * $subQty;

						$ttljual += $total_item;
						$ttlhpp += $h_beli * $subQty;

						$detailPemberianObatToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'kode_brng' => $kode_brng,
							'h_beli' => $h_beli,
							'biaya_obat' => $biaya_obat,
							'jml' => $subQty,
							'embalase' => 0,
							'tuslah' => 0,
							'total' => $total_item,
							'status' => $isRanap ? 'Ranap' : 'Ralan',
							'kd_bangsal' => $bangsal,
							'no_batch' => $d['no_batch'] ?? '',
							'no_faktur' => $d['no_faktur'] ?? ''
						];
					}

					$detailObatRacikanToInsert[] = [
						'tgl_perawatan' => $tgl_perawatan,
						'jam' => $jam,
						'no_rawat' => $no_rawat,
						'no_racik' => $no_racik,
						'kode_brng' => $kode_brng
					];

					// Masukkan header obat_racikan jika belum dimasukkan
					if (!isset($processedRacikHeaders[$no_racik])) {
						$header = $racikMap[$no_racik] ?? [
							'no_racik' => $no_racik,
							'nama_racik' => 'Racikan ' . $no_racik,
							'kd_racik' => 'R01',
							'jml_dr' => 1,
							'aturan_pakai' => '',
							'keterangan' => '-'
						];

						$obatRacikanToInsert[] = [
							'tgl_perawatan' => $tgl_perawatan,
							'jam' => $jam,
							'no_rawat' => $no_rawat,
							'no_racik' => $no_racik,
							'nama_racik' => $header['nama_racik'],
							'kd_racik' => $header['kd_racik'],
							'jml_dr' => $header['jml_dr'],
							'aturan_pakai' => $header['aturan_pakai'],
							'keterangan' => $header['keterangan'] ?? '-'
						];
						$processedRacikHeaders[$no_racik] = true;
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

				if (!$isRanap) {
					$this->postResepJurnal($no_rawat, $ttljual, $ttlhpp);
				}

				return [
					'no_resep' => $no_resep,
					'no_rawat' => $no_rawat,
					'status' => $isRanap ? 'Ranap' : 'Ralan',
					'ttljual' => $ttljual,
					'ttlhpp' => $ttlhpp
				];
			});

			return response()->json([
				'status' => 'success',
				'message' => 'Berhasil memvalidasi dan meng-adjust resep obat (Resep asli dokter tetap tersimpan)',
				'data' => $result
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 400);
		}
	}

	/**
	 * Batal Validasi Resep
	 * - Kembalikan stok ke gudangbarang
	 * - Hapus detail_pemberian_obat, aturan_pakai, obat_racikan, detail_obat_racikan
	 * - Buat jurnal reversal
	 * - Reset resep_obat ke status belum divalidasi
	 */
	public function batalValidasi(Request $request)
	{
		$no_resep = $request->no_resep;

		if (!$no_resep) {
			return response()->json(['status' => 'error', 'message' => 'No resep tidak boleh kosong'], 400);
		}

		$resep = DB::table('resep_obat')->where('no_resep', $no_resep)->first();

		if (!$resep) {
			return response()->json(['status' => 'error', 'message' => 'Resep tidak ditemukan'], 404);
		}

		// Pastikan sudah divalidasi
		if ($resep->jam === '00:00:00' || !$resep->jam) {
			return response()->json(['status' => 'error', 'message' => 'Resep belum divalidasi, tidak perlu dibatalkan'], 400);
		}

		// Pastikan belum diserahkan
		if ($resep->jam_penyerahan !== '00:00:00') {
			return response()->json(['status' => 'error', 'message' => 'Resep sudah diserahkan ke pasien, tidak dapat dibatalkan. Gunakan fitur Retur Farmasi.'], 400);
		}

		$no_rawat       = $resep->no_rawat;
		$tgl_perawatan  = $resep->tgl_perawatan;
		$jam            = $resep->jam;
		$bangsal        = Setting::first()?->kd_bangsal_apotek ?? 'AP';
		$isRanap        = ($resep->status ?? '') === 'ranap';

		// Billing lock check: jangan batalkan jika billing sudah ditutup
		if ($isRanap) {
			$isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
			if ($isBilled) {
				return response()->json([
					'status' => 'error',
					'message' => 'Pasien rawat inap ini sudah selesai billing (sudah ada Nota Inap). Pembatalan validasi resep ditolak.'
				], 422);
			}
		} else {
			$isBilled = DB::table('nota_jalan')->where('no_rawat', $no_rawat)->exists();
			if ($isBilled) {
				return response()->json([
					'status' => 'error',
					'message' => 'Pasien rawat jalan ini sudah selesai billing (sudah ada Nota Jalan). Pembatalan validasi resep ditolak.'
				], 422);
			}
		}

		try {
			DB::transaction(function () use ($no_rawat, $tgl_perawatan, $jam, $no_resep, $bangsal, $isRanap) {
				// 1. Ambil semua detail_pemberian_obat yang terkait dengan no_rawat, tgl, jam
				$detailObat = DB::table('detail_pemberian_obat')
					->where('no_rawat', $no_rawat)
					->where('tgl_perawatan', $tgl_perawatan)
					->where('jam', $jam)
					->get();

				$pegawai = session()->get('pegawai');
				$petugas = $pegawai ? ($pegawai->nik ?? $pegawai->nama ?? 'Admin') : 'Admin';

				// 2. Kembalikan stok ke gudangbarang & catat riwayat_barang_medis
				foreach ($detailObat as $item) {
					$targetBangsal = $item->kd_bangsal ?: $bangsal;
					$gbQuery = DB::table('gudangbarang')
						->where('kode_brng', $item->kode_brng)
						->where('kd_bangsal', $targetBangsal)
						->where('no_batch', $item->no_batch ?? '')
						->where('no_faktur', $item->no_faktur ?? '');

					$currentStock = (float) ($gbQuery->value('stok') ?? 0);
					$qty = floatval($item->jml);

					if ($gbQuery->exists()) {
						$gbQuery->increment('stok', $qty);
					} else {
						DB::table('gudangbarang')->insert([
							'kode_brng'  => $item->kode_brng,
							'kd_bangsal' => $targetBangsal,
							'stok'       => $qty,
							'no_batch'   => $item->no_batch ?? '',
							'no_faktur'  => $item->no_faktur ?? '',
						]);
					}

					DB::table('riwayat_barang_medis')->insert([
						'kode_brng'  => $item->kode_brng,
						'stok_awal'  => $currentStock,
						'masuk'      => $qty,
						'keluar'     => 0,
						'stok_akhir' => $currentStock + $qty,
						'posisi'     => 'Pemberian Obat',
						'tanggal'    => date('Y-m-d'),
						'jam'        => date('H:i:s'),
						'petugas'    => $petugas,
						'kd_bangsal' => $targetBangsal,
						'status'     => 'Hapus',
						'no_batch'   => $item->no_batch ?? '',
						'no_faktur'  => $item->no_faktur ?? '',
						'keterangan' => substr("Batal Validasi Resep {$no_resep}: {$no_rawat}", 0, 150),
					]);
				}

				// 3. Reverse jurnal HANYA untuk Rawat Jalan (Rawat Inap dijurnal saat Kasir Close Billing)
				if (!$isRanap) {
					$ttljual = $detailObat->sum(fn($d) => floatval($d->biaya_obat) * floatval($d->jml));
					$ttlhpp  = $detailObat->sum(fn($d) => floatval($d->h_beli) * floatval($d->jml));

					if ($ttljual > 0 || $ttlhpp > 0) {
						$rekening = DB::table('set_akun_ralan')->first();
						if ($rekening) {
							DB::table('tampjurnal')->delete();

							$jurnalItems = [];
							if ($ttljual > 0) {
								$jurnalItems[] = ['kd_rek' => $rekening->Obat_Ralan,                 'nm_rek' => 'Pendapatan Obat Rawat Jalan',      'debet' => $ttljual, 'kredit' => 0];
								$jurnalItems[] = ['kd_rek' => $rekening->Suspen_Piutang_Obat_Ralan,  'nm_rek' => 'Suspen Piutang Obat Ralan',         'debet' => 0,        'kredit' => $ttljual];
							}
							if ($ttlhpp > 0) {
								$jurnalItems[] = ['kd_rek' => $rekening->Persediaan_Obat_Rawat_Jalan,'nm_rek' => 'Persediaan Obat Rawat Jalan',       'debet' => $ttlhpp,  'kredit' => 0];
								$jurnalItems[] = ['kd_rek' => $rekening->HPP_Obat_Rawat_Jalan,       'nm_rek' => 'HPP Persediaan Obat Rawat Jalan',   'debet' => 0,        'kredit' => $ttlhpp];
							}

							DB::table('tampjurnal')->insert($jurnalItems);

							$date          = date('Y-m-d');
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
							$nm_pasien = $reg?->nm_pasien ?? '-';
							$no_rm     = $reg?->no_rkm_medis ?? '-';
							$post_by   = $pegawai ? $pegawai->nama : 'Sistem';

							DB::table('jurnal')->insert([
								'no_jurnal'  => $no_jurnal,
								'tgl_jurnal' => $date,
								'jam_jurnal' => date('H:i:s'),
								'no_bukti'   => $no_rawat,
								'jenis'      => 'U',
								'keterangan' => 'BATAL VALIDASI OBAT RALAN - PASIEN ' . $no_rm . ' ' . $nm_pasien . ', DIPROSES OLEH ' . $post_by,
							]);

							$tamp   = DB::table('tampjurnal')->get();
							$detail = $tamp->map(fn($item) => [
								'no_jurnal' => $no_jurnal,
								'kd_rek'    => $item->kd_rek,
								'debet'     => $item->debet,
								'kredit'    => $item->kredit,
							])->toArray();

							if (!empty($detail)) {
								DB::table('detailjurnal')->insert($detail);
							}
						}
					}
				}

				// 5. Hapus record terkait validasi
				DB::table('detail_pemberian_obat')
					->where('no_rawat', $no_rawat)
					->where('tgl_perawatan', $tgl_perawatan)
					->where('jam', $jam)
					->delete();

				DB::table('aturan_pakai')
					->where('no_rawat', $no_rawat)
					->where('tgl_perawatan', $tgl_perawatan)
					->where('jam', $jam)
					->delete();

				$racikIds = DB::table('obat_racikan')
					->where('no_rawat', $no_rawat)
					->where('tgl_perawatan', $tgl_perawatan)
					->where('jam', $jam)
					->pluck('no_racik');

				if ($racikIds->isNotEmpty()) {
					DB::table('detail_obat_racikan')
						->where('no_rawat', $no_rawat)
						->where('tgl_perawatan', $tgl_perawatan)
						->where('jam', $jam)
						->delete();

					DB::table('obat_racikan')
						->where('no_rawat', $no_rawat)
						->where('tgl_perawatan', $tgl_perawatan)
						->where('jam', $jam)
						->delete();
				}

				// 6. Reset resep_obat ke status belum divalidasi
				DB::table('resep_obat')
					->where('no_resep', $no_resep)
					->update([
						'tgl_perawatan' => '0000-00-00',
						'jam'           => '00:00:00',
					]);
			});

			return response()->json([
				'status'  => 'success',
				'message' => 'Validasi resep berhasil dibatalkan. Stok obat telah dikembalikan.',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status'  => 'error',
				'message' => $e->getMessage(),
			], 400);
		}
	}
}
