<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahasaPasienController;
use App\Http\Controllers\Bridging as Bridging;
use App\Http\Controllers\Bridging\Icare;
use App\Http\Controllers\BridgingPcareSettingController;
use App\Http\Controllers\CacatFisikController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\DiagnosaPasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\EfktpKategoriBerkasPenunjangController;
use App\Http\Controllers\EfktpPcareAlergiController;
use App\Http\Controllers\EfktpTemplateRacikanController;
use App\Http\Controllers\EfktpTindakanResikoJatuhController;
use App\Http\Controllers\Icd9Controller;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KamarInapController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\Lab\JnsPerawatanLabController;
use App\Http\Controllers\Lab\PeriksaLabController;
use App\Http\Controllers\Lab\Permintaan\DetailPermintaanLabController;
use App\Http\Controllers\Lab\Permintaan\PermintaanLabController;
use App\Http\Controllers\Lab\Permintaan\PermintaanPemeriksaanLabController;
use App\Http\Controllers\MapingDokterController;
use App\Http\Controllers\MappingObatPcareController;
use App\Http\Controllers\MappingPoliklinikPcareController;
use App\Http\Controllers\MetodeRacikController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PcareKunjunganController;
use App\Http\Controllers\PcarePendaftaranController;
use App\Http\Controllers\PcareRujukSubspesialisController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PemeriksaanGigiController;
use App\Http\Controllers\PemeriksaanGigiHasilController;
use App\Http\Controllers\PemeriksaanRalanController;
use App\Http\Controllers\PemeriksaanRanapController;
use App\Http\Controllers\PenilaianAwalKeperawatanRalanController;
use App\Http\Controllers\PenjabController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\PerusahaanPasienController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\PropinsiController;
use App\Http\Controllers\ProsedurPasienController;
use App\Http\Controllers\RegPeriksaController;
use App\Http\Controllers\ResepDokterController;
use App\Http\Controllers\ResepDokterRacikanController;
use App\Http\Controllers\ResepDokterRacikanDetailController;
use App\Http\Controllers\ResepObatController;
use App\Http\Controllers\RujukController;
use App\Http\Controllers\RujukInternalController;
use App\Http\Controllers\setNoRkmMedisController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SukuBangsaController;
use App\Http\Controllers\SuratSakitController;
use App\Http\Controllers\SuratSehatController;
use App\Http\Controllers\UploadController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'auth'])->middleware('guest');

Route::get('/antrean/poliklinik', function () {
	$settings = Setting::select()->get();

	foreach ($settings as $setting) {
		$setting->logo = 'data:image/jpeg;base64,' . base64_encode($setting->logo);
		$setting->wallpaper = 'data:image/jpeg;base64,' . base64_encode($setting->wallpaper);
	}
	return view('antrean.poliklinik', ['data' => $setting]);
});
Route::get('/setting/antrean/video', function () {
	$data = Storage::get('public/video.json');
	if ($data) {
		return response()->json(json_decode($data));
	}
});

// set status layanan
Route::post('/registrasi/update/status', [RegPeriksaController::class, 'setStatusLayanan']);

Route::get('/setting/ppk', [SettingController::class, 'getKodePPK']);

Route::get('/registrasi/get/panggil', [RegPeriksaController::class, 'getPanggil']);
Route::post('/registrasi/update', [RegPeriksaController::class, 'update']);
Route::get('/registrasi/bukti/print', [RegPeriksaController::class, 'print']);

Route::middleware('auth')->group(function () {

	Route::post('/logout', [AuthController::class, 'logout']);
	Route::get('/logout', [AuthController::class, 'logout']);
	Route::get('/', function () {
		return view('content.dashboard', ['data' => Setting::first()]);
	});

	// pasien
	Route::get('/pasien/riwayat', [PasienController::class, 'getRiwayat']);
	Route::post('/pasien', [PasienController::class, 'create']);
	Route::get('/pasien', [PasienController::class, 'get']);
	Route::get('/pasien/exist', [PasienController::class, 'isExistPasien']);
	Route::get('/pasien/get/nokartu/{noKartu}', [PasienController::class, 'getByNoka']);

	// SUKU BANGSA
	Route::get('suku', [SukuBangsaController::class, 'get']);
	Route::post('suku', [SukuBangsaController::class, 'create']);
	// SUKU BAHASAS
	Route::get('bahasa', [BahasaPasienController::class, 'get']);
	Route::post('bahasa', [BahasaPasienController::class, 'create']);
	// CACAT FISIK
	Route::get('cacat', [CacatFisikController::class, 'get']);
	Route::post('cacat', [CacatFisikController::class, 'create']);
	// PENJAB
	Route::get('penjab', [PenjabController::class, 'get']);
	// KELURAHAN
	Route::get('kelurahan', [KelurahanController::class, 'get']);
	Route::post('kelurahan', [KelurahanController::class, 'create']);
	Route::get('pasien/data/kelurahan', [PasienController::class, 'dataKelurahan']);
	// KECAMATAN
	Route::get('kecamatan', [KecamatanController::class, 'get']);
	Route::post('kecamatan', [KecamatanController::class, 'create']);
	Route::get('pasien/data/kecamatan', [PasienController::class, 'dataKecamatan']);

	// KABUPATEN
	Route::get('kabupaten', [KabupatenController::class, 'get']);
	Route::post('kabupaten', [KabupatenController::class, 'create']);
	// PROPINSI
	Route::get('propinsi', [PropinsiController::class, 'get']);
	Route::post('propinsi', [PropinsiController::class, 'create']);
	// PERUSAHAAN
	Route::get('perusahaan', [PerusahaanPasienController::class, 'get']);
	Route::post('perusahaan', [PerusahaanPasienController::class, 'create']);
	// SET NO RKM MEDIS
	Route::get('/set/norm', [setNoRkmMedisController::class, 'get']);
	Route::post('/set/norm/delete', [setNoRkmMedisController::class, 'delete']);
	// POLIKLINIK
	Route::get('/poliklinik', [PoliklinikController::class, 'get']);
	Route::post('/poliklinik', [PoliklinikController::class, 'delete']);
	// DOKTER
	Route::get('/dokter', [DokterController::class, 'get']);
	Route::post('/dokter', [DokterController::class, 'delete']);

	Route::get('/pegawai', [PegawaiController::class, 'get']);
	Route::get('/pegawai/profil', function () {
		return view('content.pegawai');
	});

	Route::get('/registrasi/pasien/{no_rkm_medis}', [RegPeriksaController::class, 'getAllRegPasien']);
	Route::get('/registrasi/set/noreg', [RegPeriksaController::class, 'setNoReg']);
	Route::get('/registrasi/set/norawat', [RegPeriksaController::class, 'setNoRawat']);
	Route::post('/registrasi', [RegPeriksaController::class, 'create']);
	Route::post('/registrasi/update', [RegPeriksaController::class, 'update']);


	Route::get('/registrasi', function () {
		return view('content.registrasi');
	});
	Route::get('/registrasi/get', [RegPeriksaController::class, 'get']);
	Route::get('/registrasi/get/detail', [RegPeriksaController::class, 'show']);
	Route::get('/registrasi/kecamatan', [RegPeriksaController::class, 'getKecamatan']);
	Route::get('/registrasi/kelurahan', [RegPeriksaController::class, 'getKelurahan']);
	Route::get('/registrasi/grafik', [RegPeriksaController::class, 'getGrafik']);
	Route::get('/registrasi/grafik/tahun', [RegPeriksaController::class, 'getGrafikTahunan']);

	// PENILIAIAN AWAL/SKRINING
	Route::get('/penilaian/awal/keperawatan/ralan', [PenilaianAwalKeperawatanRalanController::class, 'get']);
	Route::post('/penilaian/awal/keperawatan/ralan', [PenilaianAwalKeperawatanRalanController::class, 'createPenilaian']);
	Route::get('/penilaian/awal/keperawatan/ralan/print', [PenilaianAwalKeperawatanRalanController::class, 'print']);

	// TINDAKAN SKRINING RESIKO JATUH
	Route::post('/skrining/jatuh', [EfktpTindakanResikoJatuhController::class, 'create']);
	Route::get('/skrining/jatuh', [EfktpTindakanResikoJatuhController::class, 'get']);
	Route::get('/skrining/jatuh/print', [EfktpTindakanResikoJatuhController::class, 'print']);

	// Pemeriksaan
	Route::get('/pemeriksaan/ralan', [PemeriksaanRalanController::class, 'get']);
	Route::get('/pemeriksaan/ralan/get', [PemeriksaanRalanController::class, 'get']);
	Route::get('/pemeriksaan/ralan/show', [PemeriksaanRalanController::class, 'show']);
	Route::post('/pemeriksaan/ralan/create', [PemeriksaanRalanController::class, 'create']);

	// PEMERIKSAAN GIGI
	Route::get('/pemeriksaan/gigi', [PemeriksaanGigiController::class, 'get']);
	Route::post('/pemeriksaan/gigi', [PemeriksaanGigiController::class, 'create']);

	Route::get('/pemeriksaan/gigi/riwayat', [PemeriksaanGigiController::class, 'getRiwayat']);
	Route::get('/pemeriksaan/gigi/hasil', [PemeriksaanGigiHasilController::class, 'get']);
	Route::post('/pemeriksaan/gigi/hasil', [PemeriksaanGigiHasilController::class, 'create']);
	Route::post('/pemeriksaan/gigi/hasil/update', [PemeriksaanGigiHasilController::class, 'update']);
	Route::post('/pemeriksaan/gigi/hasil/delete', [PemeriksaanGigiHasilController::class, 'delete']);

	// Dignosa/Penyakit
	Route::get('/penyakit/get', [PenyakitController::class, 'get']);

	// Prosedur/tindakan ata ic9
	Route::get('/tindakan/get', [Icd9Controller::class, 'get']);

	// Dokter
	Route::get('/dokter/get', [DokterController::class, 'get']);
	//Diagnosa Pasien
	Route::post('/diagnosa/pasien/create', [DiagnosaPasienController::class, 'create']);
	Route::post('/diagnosa/pasien/delete', [DiagnosaPasienController::class, 'delete']);
	Route::post('/diagnosa/pasien/update', [DiagnosaPasienController::class, 'update']);
	Route::get('/diagnosa/pasien/get', [DiagnosaPasienController::class, 'get']);
	Route::get('/diagnosa/pasien/grafik', [DiagnosaPasienController::class, 'grafik']);

	// Prosedur/tindakan
	Route::get('/prosedur/pasien/get', [ProsedurPasienController::class, 'get']);
	Route::post('/prosedur/pasien/create', [ProsedurPasienController::class, 'create']);
	Route::post('/prosedur/pasien/delete', [ProsedurPasienController::class, 'delete']);
	Route::post('/prosedur/pasien/update', [ProsedurPasienController::class, 'update']);

	// Barang/obat
	Route::get('/farmasi/obat', [DataBarangController::class, 'index']);

	Route::get('/barang/get', [DataBarangController::class, 'get']);

	// Resep Obat
	Route::post('/resep/create', [ResepObatController::class, 'create']);
	Route::get('/resep/get', [ResepObatController::class, 'get']);
	Route::post('/resep/delete', [ResepObatController::class, 'delete']);
	Route::get('/resep/print', [ResepObatController::class, 'print']);
	Route::post('/resep/create-form-paket', [ResepObatController::class, 'createResepPaket']);

	Route::get('/resep/nomor/generate', [\App\Action\GenerateNoResep::class, 'handle']);

	// Resep Dokter (NON RACIKAN)
	Route::post('/resep/dokter/create', [ResepDokterController::class, 'create']);
	Route::get('/resep/dokter/get', [ResepDokterController::class, 'get']);
	Route::post('/resep/dokter/delete', [ResepDokterController::class, 'delete']);
	Route::post('/resep/dokter/update', [ResepDokterController::class, 'update']);

	// Resep Racikan
	Route::get('/resep/racikan/get', [ResepDokterRacikanController::class, 'get']);
	Route::post('/resep/racikan/create', [ResepDokterRacikanController::class, 'create']);
	Route::post('/resep/racikan/delete', [ResepDokterRacikanController::class, 'delete']);

	// Detail resep racikam
	Route::get('resep/racikan/detail/get', [ResepDokterRacikanDetailController::class, 'get']);
	Route::post('resep/racikan/detail/create', [ResepDokterRacikanDetailController::class, 'create']);
	Route::post('resep/racikan/detail/delete', [ResepDokterRacikanDetailController::class, 'delete']);

	// Metode Racikan
	Route::get('/metode/racik/get', [MetodeRacikController::class, 'get']);

	// FARMASI
	Route::get('farmasi/resep', [ResepObatController::class, 'index']);
	Route::get('farmasi/resep/get', [ResepObatController::class, 'get']);
	Route::post('farmasi/resep/set/penyerahan', [ResepObatController::class, 'setPenyerahan']);

	// Template Racikan
	Route::get('farmasi/racik/template', [EfktpTemplateRacikanController::class, 'index']);
	Route::get('resep/racikan/template/get', [EfktpTemplateRacikanController::class, 'get']);
	Route::get('resep/racikan/template/search', [EfktpTemplateRacikanController::class, 'search']);
	Route::post('resep/racikan/template/create', [EfktpTemplateRacikanController::class, 'create']);
	Route::post('resep/racikan/template/update', [EfktpTemplateRacikanController::class, 'update']);
	Route::post('resep/racikan/template/delete', [EfktpTemplateRacikanController::class, 'delete']);

	Route::group(['prefix' => 'lab'], function () {

		Route::get('/jenis/get', [JnsPerawatanLabController::class, 'get']);
		Route::get('/jenis/template/get', [JnsPerawatanLabController::class, 'getTemplate']);
		Route::get('/periksa/get', [PeriksaLabController::class, 'get']);

		Route::group(['prefix' => 'permintaan'], function () {
			Route::get('/', [PermintaanLabController::class, 'index']);
			Route::get('/get', [PermintaanLabController::class, 'get']);
			Route::get('/test', [PermintaanLabController::class, 'createPermintaan']);
			Route::post('/', [PermintaanLabController::class, 'create']);
			Route::post('/delete/{noorder}', [PermintaanLabController::class, 'delete']);
			Route::post('/pemeriksaan', [PermintaanPemeriksaanLabController::class, 'create']);
			Route::post('/detail', [DetailPermintaanLabController::class, 'create']);
			Route::get('/detail/{noorder}/{kd_jenis_prw?}', [DetailPermintaanLabController::class, 'get']);

			Route::get('/noorder', [PermintaanLabController::class, 'getNoOrder']);
		});
	});

	Route::post('upload', [UploadController::class, 'upload']);
	Route::get('upload', [UploadController::class, 'get']);
	Route::post('upload/delete/{id}', [UploadController::class, 'delete']);

	Route::get('berkas/penunjang/kategori', [EfktpKategoriBerkasPenunjangController::class, 'get']);
	Route::get('berkas/penunjang/kategori/first', [EfktpKategoriBerkasPenunjangController::class, 'getFirst']);
	Route::post('berkas/penunjang/kategori', [EfktpKategoriBerkasPenunjangController::class, 'create']);

	Route::get('icare', [Icare::class, 'get']);

	// SURAT SAKIT
	Route::get('surat/sakit', [SuratSakitController::class, 'get']);
	Route::get('surat/sakit/print/{noSurat}', [SuratSakitController::class, 'print']);
	Route::post('surat/sakit', [SuratSakitController::class, 'create']);
	Route::post('surat/sakit/delete/{noSurat}', [SuratSakitController::class, 'delete']);
	Route::get('surat/sakit/setnomor', [SuratSakitController::class, 'setNoSurat']);

	// SUrat Sehat
	Route::get('surat/sehat', [SuratSehatController::class, 'get']);
	Route::post('surat/sehat', [SuratSehatController::class, 'create']);
	Route::get('surat/sehat/setnomor', [SuratSehatController::class, 'setNoSurat']);
	Route::get('surat/sehat/{noSurat}', [SuratSehatController::class, 'getSurat']);
	Route::post('surat/sehat/delete/{noSurat}', [SuratSehatController::class, 'delete']);
	Route::get('surat/sehat/print/{noSurat}', [SuratSehatController::class, 'print']);

	Route::get('ranap', function () {
		return view('content.kamarInap');
	});
	Route::get('kamar/inap/get', [KamarInapController::class, 'get']);

	Route::get('pemeriksaan/ranap', [PemeriksaanRanapController::class, 'get']);
	Route::post('pemeriksaan/ranap', [PemeriksaanRanapController::class, 'create']);
	Route::post('pemeriksaan/ranap/update', [PemeriksaanRanapController::class, 'update']);
	Route::post('pemeriksaan/ranap/delete', [PemeriksaanRanapController::class, 'delete']);

	// TINDAKAN DOKTER
	Route::post('pemeriksaan/tindakan-dokter', [\App\Http\Controllers\TindakanDokterController::class, 'create']);
	Route::get('pemeriksaan/tindakan-dokter/get', [\App\Http\Controllers\TindakanDokterController::class, 'get']);
	Route::delete('pemeriksaan/tindakan-dokter/delete', [\App\Http\Controllers\TindakanDokterController::class, 'delete']);

	//RESUME MEDIS
	Route::get('resume/medis', [\App\Http\Controllers\ResumeMedisController::class, 'get']);
	Route::post('resume/medis', [\App\Http\Controllers\ResumeMedisController::class, 'create']);

	// RUJUK INTERNAL POLI
	Route::get('/rujuk/internal/poli', [RujukInternalController::class, 'get']);
	Route::post('/rujuk/internal/poli', [RujukInternalController::class, 'create']);
	Route::get('/rujuk/internal/poli/show', [RujukInternalController::class, 'show']);
	Route::post('/rujuk/internal/poli/delete', [RujukInternalController::class, 'delete']);
	Route::post('/rujuk/internal/poli/update', [RujukInternalController::class, 'update']);
	// RUJUK KELUAR
	Route::get('/rujuk/keluar', [RujukController::class, 'get']);
	Route::post('/rujuk/keluar', [RujukController::class, 'create']);
	Route::post('/rujuk/keluar/delete', [RujukController::class, 'delete']);
	Route::get('/rujuk/keluar/detail', [RujukController::class, 'detail']);
	Route::get('/rujuk/keluar/nomor', [RujukController::class, 'setNoRujuk']);
	Route::get('/rujuk/keluar/keterangan', [RujukController::class, 'getKeterangan']);
	Route::get('/rujuk/keluar/faskes', [RujukController::class, 'getFaskesRujuk']);
	Route::get('/rujuk/keluar/print', [RujukController::class, 'print']);

	Route::get('/pcare/pendaftaran', [PcarePendaftaranController::class, 'index']);
	Route::post('/pcare/pendaftaran', [PcarePendaftaranController::class, 'create']);
	Route::get('/pcare/pendaftaran/get', [PcarePendaftaranController::class, 'get']);
	Route::post('/pcare/pendaftaran/delete', [PcarePendaftaranController::class, 'delete']);

	// PCARE KUNJUNGAN
	Route::get('/pcare/kunjungan', [PcareKunjunganController::class, 'index']);
	Route::post('/pcare/kunjungan', [PcareKunjunganController::class, 'create']);
	Route::post('/pcare/kunjungan/update', [PcareKunjunganController::class, 'update']);
	Route::get('/pcare/kunjungan/get', [PcareKunjunganController::class, 'get']);
	Route::post('/pcare/kunjungan/delete/{noKunjungan}', [PcareKunjunganController::class, 'delete']);
	Route::post('/pcare/kunjungan/print/{noKunjungan}', [PcareKunjunganController::class, 'print']);

	// PCARE KUNJUNGAN RUJUK SUBSPESIALIS
	Route::post('/pcare/kunjungan/rujuk/subspesialis/', [PcareRujukSubspesialisController::class, 'create']);
	Route::post('/pcare/kunjungan/rujuk/subspesialis/update', [PcareRujukSubspesialisController::class, 'update']);
	Route::post('/pcare/kunjungan/rujuk/subspesialis/delete/{noKunjungan}', [PcareRujukSubspesialisController::class, 'delete']);
	Route::get('/pcare/kunjungan/rujuk/subspesialis/print', [PcareRujukSubspesialisController::class, 'print']);
	Route::get('/pcare/kunjungan/rujuk/subspesialis/riwayat/{no_rkm_medis}', [PcareRujukSubspesialisController::class, 'getAll']);

	// MAPPING
	Route::get('mapping/pcare/poliklinik', [MappingPoliklinikPcareController::class, 'get']);
	Route::get('mapping/pcare/dokter', [MapingDokterController::class, 'get']);
	Route::get('mapping/pcare/obat', [MappingObatPcareController::class, 'get']);
	Route::post('mapping/pcare/obat', [MappingObatPcareController::class, 'create']);
	Route::post('mapping/pcare/obat/delete/{kdBarang}', [MappingObatPcareController::class, 'delete']);

	// SETTING

	Route::get('/setting', [BridgingPcareSettingController::class, 'index']);
	Route::post('/setting/pcare', [BridgingPcareSettingController::class, 'create']);
	Route::get('/setting/pcare', [BridgingPcareSettingController::class, 'get']);
	Route::get('/setting/pcare/user', [BridgingPcareSettingController::class, 'getUser']);
	Route::post('/setting/antrean/video', function (Request $request) {
		$data = [
			'title' => 'video',
			'content' => $request['content'],
		];
		try {
			Storage::put('public/video.json', json_encode($data));
		} catch (Throwable $e) {
			return response()->json($e, 500);
		}
		return response()->json($data);
	});


	// EFKTP
	Route::post('pasien/alergi', [EfktpPcareAlergiController::class, 'create']);
	Route::get('pasien/alergi', [EfktpPcareAlergiController::class, 'get']);

	// BRIDGING
	Route::get('/bridging/pcare/dokter', [Bridging\Dokter::class, 'dokter']);
	Route::get('/bridging/pcare/alergi/{keyword?}', [Bridging\Alergi::class, 'get']);
	Route::get('/bridging/pcare/prognosa', [Bridging\Prognosa::class, 'get']);

	// PENDAFTARAN
	Route::get('/bridging/pcare/pendaftaran', [Bridging\Pendaftaran::class, 'get']);
	Route::get('/bridging/pcare/pendaftaran/tglDaftar/{tglDaftar?}/{start?}/{limit?}', [Bridging\Pendaftaran::class, 'getByTanggal']);
	Route::post('/bridging/pcare/pendaftaran', [Bridging\Pendaftaran::class, 'post']);
	Route::post('/bridging/pcare/pendaftaran/delete', [Bridging\Pendaftaran::class, 'delete']);
	Route::get('/bridging/pcare/pendaftaran/nourut/{noUrut}', [Bridging\Pendaftaran::class, 'getUrut']);

	// KUNJUNGAN
	Route::get('/bridging/pcare/kunjungan/{nokartu}', [Bridging\Kunjungan::class, 'get']);
	Route::delete('/bridging/pcare/kunjungan/{nokartu}', [Bridging\Kunjungan::class, 'get']);
	Route::post('/bridging/pcare/kunjungan/post', [Bridging\Kunjungan::class, 'post']);
	Route::post('/bridging/pcare/kunjungan/update', [Bridging\Kunjungan::class, 'put']);
	Route::post('/bridging/pcare/kunjungan/delete/{noKunjungan}', [Bridging\Kunjungan::class, 'delete']);
	Route::get('/bridging/pcare/kunjungan/rujukan/{noKunjungan}', [Bridging\Kunjungan::class, 'getRujukan']);

	// SPESIALIS
	Route::get('/bridging/pcare/spesialis', [Bridging\Spesialis::class, 'get']);
	Route::get('/bridging/pcare/spesialis/{kdSpesialis}/subspesialis', [Bridging\Spesialis::class, 'getSubspesialis']);
	Route::get('/bridging/pcare/spesialis/sarana', [Bridging\Spesialis::class, 'getsarana']);
	Route::get('/bridging/pcare/spesialis/rujukan', [Bridging\Spesialis::class, 'getFaskes']);
	Route::get('/bridging/pcare/spesialis/rujukan/khusus', [Bridging\Spesialis::class, 'getFaskesKhusus']);

	// POLI FKTP
	Route::get('/bridging/pcare/fktp/poli', [Bridging\Poli::class, 'index']);
	Route::get('/bridging/pcare/tacc', [Bridging\Poli::class, 'tacc']);

	//REFERENSI SPESIALIS KHUSUS
	Route::get('/bridging/pcare/spesialis/khusus', [Bridging\Spesialis::class, 'getKhusus']);
	Route::get('/bridging/pcare/spesialis/rujuk/khusus', [Bridging\Spesialis::class, 'getFaskesKhusus']);

	// STATUS PULANG
	Route::get('/bridging/pcare/status/pulang/{status}', [Bridging\StatusPulang::class, 'get']);

	// PESERTA
	Route::get('/bridging/pcare/peserta/{noKartu}', [Bridging\Peserta::class, 'index']);

	// OBAT
	Route::post('/bridging/pcare/obat', [Bridging\Obat::class, 'create']);
	Route::get('/bridging/pcare/obat/{keyword?}', [Bridging\Obat::class, 'get']);

	//    Diagnosa
	Route::get('/bridging/pcare/diagnosa/{diagnosa}', [Bridging\Diagnosa::class, 'get']);
});

Route::get('/test/{no_resep}', [ResepObatController::class, 'copyResep']);

require 'Extras/web.php';
require 'Extras/jenis_perawatan.php';
require 'Extras/antrean.php';
require 'Extras/hasil_usg.php';
require 'Extras/paket_obat.php';
