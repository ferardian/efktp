<?php

namespace App\Action;

use App\Models\JenisPerawatan;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\TindakanDokter;
use App\Traits\Track;
use DB;
use Exception;

class TindakanDokterAction
{
	use Track;

	public function handleCreate(array $data)
	{
		$tindakan = [];
		try {
			DB::transaction(function () use ($data, &$tindakan) {

				$tindakan = $this->createTindakanDokter($data['no_rawat'], $data['kd_dokter'], $data['tindakan']);
				$this->createTampJurnal($this->getRekeningMapping(), $tindakan['totals']);
				return $this->writeOnJurnal($data);
			});
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return $tindakan;
	}

	public function handleDelete(array $data)
	{
		$tindakan = [];

		DB::transaction(function () use ($data, &$tindakan) {
			try {
				$tindakan = $this->deleteTindakanDokter($data);
				$this->createTampJurnal($this->getRekeningMapping(), $tindakan, true);
				$this->writeOnJurnal($data, true);

			} catch (Exception $e) {
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
		});
		return $tindakan;
	}


	private function getRekeningMapping()
	{
		$rekening = DB::table('set_akun_ralan')
			->first();
		return (object)[
			'Suspen_Piutang_Tindakan_Ralan' => $rekening->Suspen_Piutang_Tindakan_Ralan,
			'Tindakan_Ralan' => $rekening->Tindakan_Ralan,
			'Beban_Jasa_Medik_Dokter_Tindakan_Ralan' => $rekening->Beban_Jasa_Medik_Dokter_Tindakan_Ralan,
			'Utang_Jasa_Medik_Dokter_Tindakan_Ralan' => $rekening->Utang_Jasa_Medik_Dokter_Tindakan_Ralan,
			'Beban_KSO_Tindakan_Ralan' => $rekening->Beban_KSO_Tindakan_Ralan,
			'Utang_KSO_Tindakan_Ralan' => $rekening->Utang_KSO_Tindakan_Ralan,
			'Beban_Jasa_Sarana_Tindakan_Ralan' => $rekening->Beban_Jasa_Sarana_Tindakan_Ralan,
			'Utang_Jasa_Sarana_Tindakan_Ralan' => $rekening->Utang_Jasa_Sarana_Tindakan_Ralan,
			'Beban_Jasa_Menejemen_Tindakan_Ralan' => $rekening->Beban_Jasa_Menejemen_Tindakan_Ralan,
			'Utang_Jasa_Menejemen_Tindakan_Ralan' => $rekening->Utang_Jasa_Menejemen_Tindakan_Ralan,
			'HPP_BHP_Tindakan_Ralan' => $rekening->HPP_BHP_Tindakan_Ralan,
			'Persediaan_BHP_Tindakan_Ralan' => $rekening->Persediaan_BHP_Tindakan_Ralan,
		];

	}

	function createTindakanDokter(string $no_rawat, string $kd_dokter, array $data)
	{
		$totals = [
			'ttldokter' => 0,
			'ttlkso' => 0,
			'ttlpendapatan' => 0,
			'ttlmaterial' => 0,
			'ttlbhp' => 0,
			'ttlmenejemen' => 0,
		];
//
//        $data = collect($data)->map(function ($item) use ($no_rawat, $kd_dokter, &$totals) {
//
//            $pendapatan = floatval($item['total_byrdr']);
//
//            if ($item['diskonRupiah'] > 0) {
//                $pendapatan = floatval($item['total_byrdr'] - $item['diskonRupiah']);
//            }
//
//            $totals['ttldokter'] += floatval($item['tarif_tindakandr']);
//            $totals['ttlkso'] += floatval($item['kso']);
//            $totals['ttlpendapatan'] += floatval($pendapatan);
//            $totals['ttlmaterial'] += floatval($item['material']);
//            $totals['ttlbhp'] += floatval($item['bhp']);
//            $totals['ttlmenejemen'] += floatval($item['menejemen']);
//
//
//
//            return [
//                'no_rawat' => $no_rawat,
//                'kd_dokter' => $kd_dokter,
//                'kd_jenis_prw' => $item['kd_jenis_prw'],
//                'tgl_perawatan' => date('Y-m-d'),
//                'jam_rawat' => date('H:i:s'),
//                'material' => $item['material'],
//                'bhp' => $item['bhp'],
//                'tarif_tindakandr' => $item['tarif_tindakandr'],
//                'kso' => $item['kso'],
//                'menejemen' => $item['menejemen'],
//                'stts_bayar' => 'Belum',
//                'biaya_rawat' => $pendapatan,
//            ];
//        })->toArray();

		$data = collect($data)->map(function ($item) use ($no_rawat, $kd_dokter, &$totals) {

			// 1. Ambil komponen biaya
			$komponen = [
				'tarif_tindakandr' => floatval($item['tarif_tindakandr']),
				'kso' => floatval($item['kso']),
				'material' => floatval($item['material']),
				'bhp' => floatval($item['bhp']),
				'menejemen' => floatval($item['menejemen']),
			];

			$total_awal = array_sum($komponen);
			$diskon = floatval($item['diskonRupiah']); // diskon total yang harus dibagi

			// 2. Hitung komponen setelah diskon secara proporsional
			if ($diskon > 0 && $total_awal > 0) {
				foreach ($komponen as $key => $value) {
					$proporsi = $value / $total_awal;
					$komponen[$key] = max(0, $value - ($diskon * $proporsi));
				}
			}

			$pendapatan = array_sum($komponen);

			// 3. Tambah ke totals
			$totals['ttldokter'] += $komponen['tarif_tindakandr'];
			$totals['ttlkso'] += $komponen['kso'];
			$totals['ttlpendapatan'] += $pendapatan;
			$totals['ttlmaterial'] += $komponen['material'];
			$totals['ttlbhp'] += $komponen['bhp'];
			$totals['ttlmenejemen'] += $komponen['menejemen'];

			// 4. Return format penyimpanan
			return [
				'no_rawat' => $no_rawat,
				'kd_dokter' => $kd_dokter,
				'kd_jenis_prw' => $item['kd_jenis_prw'],
				'tgl_perawatan' => date('Y-m-d'),
				'jam_rawat' => date('H:i:s'),
				'material' => $komponen['material'],
				'bhp' => $komponen['bhp'],
				'tarif_tindakandr' => $komponen['tarif_tindakandr'],
				'kso' => $komponen['kso'],
				'menejemen' => $komponen['menejemen'],
				'stts_bayar' => 'Belum',
				'biaya_rawat' => $pendapatan,
			];
		})->toArray();

		$create = DB::table('rawat_jl_dr')->insert($data);
		if ($create) {
			$this->insertSql(new TindakanDokter(), $data);
		}

		return ['data' => $data, 'totals' => $totals];
	}

	/**
	 * Membuat tabel tampil jurnal berdasarkan rekening dan total pendapatan, beban dokter, beban KSO, material, BHP, dan Menejemen
	 *
	 * @param object $rekening Rekening yang digunakan
	 * @param array $totals Total pendapatan, beban dokter, beban KSO, material, BHP, dan Menejemen
	 * @param boolean $reverse Tukar posisi debit dan kredit
	 */
	private function createTampJurnal($rekening, $totals, $reverse = false)
	{
		DB::table('tampjurnal')->delete();

		$insertJurnal = function ($kd, $nm, $debet, $kredit) use ($reverse) {
			if ($reverse) {
				[$debet, $kredit] = [$kredit, $debet]; // tukar posisi
			}

			DB::table('tampjurnal')->insert([
				'kd_rek' => $kd,
				'nm_rek' => $nm,
				'debet' => $debet,
				'kredit' => $kredit,
			]);
		};

		if ($totals['ttlpendapatan'] > 0) {
			$insertJurnal($rekening->Suspen_Piutang_Tindakan_Ralan, 'Suspen Piutang Tindakan Ralan', $totals['ttlpendapatan'], 0);
			$insertJurnal($rekening->Tindakan_Ralan, 'Pendapatan Tindakan Rawat Inap', 0, $totals['ttlpendapatan']);
		}

		if ($totals['ttldokter'] > 0) {
			$insertJurnal($rekening->Beban_Jasa_Medik_Dokter_Tindakan_Ralan, 'Beban Jasa Medik Dokter Tindakan Ralan', $totals['ttldokter'], 0);
			$insertJurnal($rekening->Utang_Jasa_Medik_Dokter_Tindakan_Ralan, 'Utang Jasa Medik Dokter Tindakan Ralan', 0, $totals['ttldokter']);
		}

		if ($totals['ttlkso'] > 0) {
			$insertJurnal($rekening->Beban_KSO_Tindakan_Ralan, 'Beban KSO Tindakan Ralan', $totals['ttlkso'], 0);
			$insertJurnal($rekening->Utang_KSO_Tindakan_Ralan, 'Utang KSO Tindakan Ralan', 0, $totals['ttlkso']);
		}

		if ($totals['ttlmaterial'] > 0) {
			$insertJurnal($rekening->Beban_Jasa_Sarana_Tindakan_Ralan, 'Beban Jasa Sarana Tindakan Ralan', $totals['ttlmaterial'], 0);
			$insertJurnal($rekening->Utang_Jasa_Sarana_Tindakan_Ralan, 'Utang Jasa Sarana Tindakan Ralan', 0, $totals['ttlmaterial']);
		}

		if ($totals['ttlbhp'] > 0) {
			$insertJurnal($rekening->HPP_BHP_Tindakan_Ralan, 'HPP BHP Tindakan Ralan', $totals['ttlbhp'], 0);
			$insertJurnal($rekening->Persediaan_BHP_Tindakan_Ralan, 'Persediaan BHP Tindakan Ralan', 0, $totals['ttlbhp']);
		}

		if ($totals['ttlmenejemen'] > 0) {
			$insertJurnal($rekening->Beban_Jasa_Menejemen_Tindakan_Ralan, 'Beban Jasa Menejemen Tindakan Ralan', $totals['ttlmenejemen'], 0);
			$insertJurnal($rekening->Utang_Jasa_Menejemen_Tindakan_Ralan, 'Utang Jasa Menejemen Tindakan Ralan', 0, $totals['ttlmenejemen']);
		}
	}


	private function generateNoJurnal()
	{
		$date = date('Y-m-d');
		$count = DB::table('jurnal')->whereDate('tgl_jurnal', $date)->count();
		$count += 1;
		$no_jurnal = 'JR' . date('Ymd') . str_pad($count, 6, '0', STR_PAD_LEFT);
		return $no_jurnal;
	}

	/**
	 * Write on journal table
	 *
	 * This function will write the data given on journal table.
	 * The data will contain the no bukti, no rkm medis, nama pasien, and the amount of the action.
	 *
	 * If the action is revert, the keterangan field on journal table will be different.
	 *
	 * @param array $data The data to be written on journal table
	 * @param bool $isRevert Whether the action is revert or not
	 */
	private function writeOnJurnal(array $data, bool $isRevert = false)
	{

		$keterangan = $isRevert ? 'PEMBATALAN TINDAKAN RAWAT JALAN ' : 'TINDAKAN RAWAT JALAN ';

		$dataJurnal = [
			'no_jurnal' => $this->generateNoJurnal(),
			'tgl_jurnal' => date('Y-m-d'),
			'jam_jurnal' => date('H:i:s'),
			'no_bukti' => $data['no_rawat'],
			'jenis' => 'U',
			'keterangan' => $keterangan . $data['no_rkm_medis'] . ' ' . $data['nm_pasien'] . ' DI POST OLEH  ' . session()->get('pegawai')->nama,
		];
		DB::table('jurnal')->insert($dataJurnal);
		$this->createDetailJurnal($dataJurnal['no_jurnal']);
	}

	private function createDetailJurnal($no_jurnal)
	{
		try {
			$data = DB::table('tampjurnal')->get();
			if ($data === null) {
				throw new \Exception('Tampjurnal table is empty');
			}
			$data = $data->map(function ($item) use ($no_jurnal) {
				return (array)[
					'no_jurnal' => $no_jurnal,
					'kd_rek' => $item->kd_rek,
					'debet' => $item->debet,
					'kredit' => $item->kredit,
				];
			})->toArray();
			if (empty($data)) {
				throw new \Exception('Tampjurnal table is empty');
			}
			DB::table('detailjurnal')->insert($data);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	protected function deleteTindakanDokter(array $data)
	{
		try {

			$totals = [
				'ttldokter' => 0,
				'ttlkso' => 0,
				'ttlpendapatan' => 0,
				'ttlmaterial' => 0,
				'ttlbhp' => 0,
				'ttlmenejemen' => 0,
			];
			foreach ($data['tindakan'] as $item) {
				$tindakan = TindakanDokter::where([
					'no_rawat' => $data['no_rawat'],
					'kd_dokter' => $data['kd_dokter'],
					'kd_jenis_prw' => $item['kd_jenis_prw'],
				]);

				$row = $tindakan->first();
				$totals['ttldokter'] += floatval($row->tarif_tindakandr);
				$totals['ttlkso'] += floatval($row->kso);
				$totals['ttlpendapatan'] += floatval($row->biaya_rawat);
				$totals['ttlmaterial'] += floatval($row->material);
				$totals['ttlbhp'] += floatval($row->bhp);
				$totals['ttlmenejemen'] += floatval($row->menejemen);
				$delete = $tindakan->delete();

				if ($delete) {
					$this->deleteSql(new TindakanDokter(), [
						'no_rawat' => $row->no_rawat,
						'kd_dokter' => $row->kd_dokter,
						'kd_jenis_prw' => $row->kd_jenis_prw,
					]);
				}
			}

			return $totals;

		} catch (Exception $e) {
			throw new Exception("Error Processing Request " . $e->getMessage(), 1);
		}
	}


}
