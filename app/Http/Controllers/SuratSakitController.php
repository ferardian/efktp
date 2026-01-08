<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SuratSakit;
use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Riskihajar\Terbilang\Facades\Terbilang;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\Tcpdf;
use Yajra\DataTables\DataTables;

class SuratSakitController extends Controller
{
	use Track;

	public function get(Request $request)
	{
		$suratSakit = SuratSakit::where('tanggalawal', date('Y-m-d'))
			->with([
				'regPeriksa' => function ($q) {
					return $q->with('pasien', 'pemeriksaanRalan', 'diagnosa');
				}
			])
			->get();
		if ($request->tgl_pertama && $request->tgl_kedua) {
			$suratSakit = SuratSakit::whereBetween('tanggalawal', [
				date('Y-m-d', strtotime($request->tgl_pertama)),
				date('Y-m-d', strtotime($request->tgl_kedua)),
			])
				->with([
					'regPeriksa' => function ($q) {
						return $q->with('pasien', 'pemeriksaanRalan', 'diagnosa');
					}
				])
				->get();
		}

		if ($request->no_rawat) {
			$suratSakit = SuratSakit::where('no_rawat', $request->no_rawat)->with([
				'regPeriksa' => function ($q) {
					return $q->with('pasien', 'pemeriksaanRalan', 'diagnosa', 'prosedur');
				}
			])->first();
		}
		if ($request->dataTable) {
			return DataTables::of($suratSakit)->make(true);
		}

		return response()->json($suratSakit);
	}

	public function setNoSurat(Request $request)
	{
		$tgl_surat = $request->tgl_surat ? $request->tgl_surat : date('Y-m-d');
		$surat = SuratSakit::select('no_surat')
			->where('tanggalawal', date('Y-m-d', strtotime($tgl_surat)))
			->orderBy('no_surat', 'DESC')->first();

		$strTanggal = Carbon::parse($tgl_surat)->translatedFormat('Ymd');
		if (!$surat) {
			$no = '001';
		} else {
			$noAkhir = substr($surat->no_surat, -3);
			$no = (int) $noAkhir + 1;
			$no = sprintf('%03d', $no);
		}
		$no = "SKS{$strTanggal}{$no}";

		return response()->json($no);
	}

	public function create(Request $request)
	{
		$terbilang = ucwords(Terbilang::make($request->lama));
		$data = [
			'no_surat' => $request->no_surat,
			'no_rawat' => $request->no_rawat,
			'tanggalawal' => date('Y-m-d', strtotime($request->tanggalawal)),
			'tanggalakhir' => date('Y-m-d', strtotime($request->tanggalakhir)),
			'lamasakit' => "{$request->lama} ({$terbilang})",
		];

		try {
			$surat = SuratSakit::create($data);
			$this->insertSql(new SuratSakit(), $data);
			return response()->json('SUKES', 201);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
	}

	public function delete($noSurat)
	{
		try {
			$surat = SuratSakit::where('no_surat', $noSurat)->delete();
			if ($surat) {
				$this->deleteSql(new SuratSakit(), ['no_surat' => $noSurat]);
			}
			return response()->json('SUKSES', 200);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo);
		}
	}

	public function print($noSurat)
	{
		$surat = SuratSakit::with([
			'regPeriksa' => function ($q) {
				return $q->with(['dokter', 'pasien.kel', 'pasien.kec', 'pasien.kab', 'pasien.perusahaanPasien']);
			},
			'pemeriksaanRalan',
			'diagnosa.penyakit'
		])->where('no_surat', $noSurat)->first();
		$setting = Setting::first();

		if ($surat->diagnosa) {
			$diagnosa = collect($surat->diagnosa)->map(function ($dx) {
				return $dx->penyakit->nm_penyakit;
			})->join(';');
		} else {
			$diagnosa = '-';
		}

		$data = [
			'no_surat' => $surat->no_surat,
			'nm_pasien' => $surat->regPeriksa->pasien->nm_pasien,
			'tgl_lahir' => Carbon::parse($surat->regPeriksa->pasien->tgl_lahir)->translatedFormat('d F Y'),
			'umur' => Carbon::parse($surat->regPeriksa->pasien->tgl_lahir)->diff($surat->regPeriksa->tgl_registrasi)->format('%y Th %m Bl %d Hr'),
			'jk' => $surat->regPeriksa->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan',
			'pekerjaan' => $surat->regPeriksa->pasien->pekerjaan,
			'instansi' => $surat->regPeriksa->pasien->perusahaanPasien->nama_perusahaan,
			'alamat' => "{$surat->regPeriksa->pasien->alamat}, {$surat->regPeriksa->pasien->kel->nm_kel}, {$surat->regPeriksa->pasien->kec->nm_kec}, {$surat->regPeriksa->pasien->kab->nm_kab}",
			'lama' => $surat->lamasakit,
			'diagnosa' => $diagnosa,
			'tgl_awal' => $surat->tanggalawal,
			'tgl_akhir' => $surat->tanggalakhir,
			'dokter' => $surat->regPeriksa->dokter->nm_dokter,
			'sip' => $surat->regPeriksa->dokter->no_ijn_praktek,
			'nama_instansi' => $setting->nama_instansi,
			'alamat_instansi' => "{$setting->alamat_instansi}, {$setting->kabupaten}, {$setting->propinsi}",
			'kontak' => $setting->kontak,
			'email' => $setting->email,
			'logo' => base64_encode($setting->logo),
		];
		$pdf = Pdf::loadView('content.print.suratSakit', ['data' => $data])
			->setPaper('a5', 'potrait')->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);

		return $pdf->stream("{$data['no_surat']}.pdf");

		// 1️⃣ Generate PDF biasa dulu (DomPDF)
		// 1️⃣ Generate PDF dengan DomPDF → simpan sementara
		// $tempPath = storage_path("app/temp/{$data['no_surat']}.pdf");

		// $pdf = Pdf::loadView('content.print.suratSakit', compact('data'))
		// 	->setPaper('a5', 'portrait')
		// 	->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => true]);

		// file_put_contents($tempPath, $pdf->output());

		// // 2️⃣ Buat instance TCPDF (FPDI)
		// $tcpdf = new Tcpdf\Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// $tcpdf->SetCreator($data['dokter']);
		// $tcpdf->SetAuthor(env('APP_NAME'));
		// $tcpdf->SetTitle('Surat Keterangan Sakit');
		// $tcpdf->SetSubject('Digital Signed');
		// $tcpdf->SetKeywords('TCPDF, PDF, digital signature');
		// $tcpdf->AddPage();

		// // 3️⃣ Impor halaman dari hasil DomPDF
		// $pageCount = $tcpdf->setSourceFile($tempPath);
		// for ($i = 1; $i <= $pageCount; $i++) {
		// 	$tpl = $tcpdf->importPage($i);
		// 	$tcpdf->useTemplate($tpl, 0, 0, 210, 297, false);
		// }


		// // 4️⃣ Tambahkan watermark tanda tangan visual dokter + timestamp
		// $timestamp = now()->format('Y-m-d H:i:s');
		// $signedBy = strtoupper($data['dokter']);

		// // Set transparansi agar watermark samar
		// $tcpdf->SetAlpha(0.10);
		// $tcpdf->SetFont('helvetica', 'B', 12);
		// $tcpdf->SetTextColor(100, 100, 100);
		// $tcpdf->StartTransform();
		// //		$tcpdf->Rotate(45, 60, 60);
		// $tcpdf->Text(50, 205, "SIGNED BY: {$signedBy}");
		// $tcpdf->StopTransform();

		// $tcpdf->SetFont('courier', '', 10);
		// $tcpdf->SetAlpha(0.10);
		// $tcpdf->Text(50, 200, "Timestamp: {$timestamp}");
		// $tcpdf->SetAlpha(1);

		// $tcpdf->Footer();
		// $tcpdf->setPrintFooter();
		// $tcpdf->setFooterData([150, 150, 150], [100, 2100, 5]);


		// // 4️⃣ Load sertifikat digital (.pfx)
		// $certificate = storage_path('app/certs/dr_aisyiyah/certificate.pfx');
		// $password = 'secret123'; // ubah sesuai password PFX kamu

		// $pkcs12 = file_get_contents($certificate);
		// openssl_pkcs12_read($pkcs12, $certs, $password);

		// $info = [
		// 	'Name' => env('APP_NAME'),
		// 	'Location' => env('APP_CITY'),
		// 	'Reason' => 'Surat Keterangan Sakit',
		// 	'ContactInfo' => env('APP_EMAIL'),
		// ];

		// $tcpdf->setSignature(
		// 	$certs['cert'],
		// 	$certs['pkey'],
		// 	'',
		// 	'',
		// 	2,
		// 	$info
		// );

		// // 5️⃣ (Opsional) Tambahkan tanda tangan visual
		// // $tcpdf->Image(storage_path('app/public/logo_rs.png'), 90, 185, 25, 0, 'PNG');
		// // $tcpdf->setSignatureAppearance(15, 135, 40, 20);

		// // 6️⃣ Simpan hasil final
		// $signedPath = storage_path("app/public/documents/surat-sakit/signed_{$data['no_surat']}.pdf");
		// $tcpdf->Output($signedPath, 'F');

		// return response()->file($signedPath);
	}
}
