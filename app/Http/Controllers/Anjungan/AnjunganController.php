<?php

namespace App\Http\Controllers\Anjungan;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Jadwal;
use App\Models\Pasien;
use App\Models\PcarePendaftaran;
use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Models\TbList;
use App\Services\Bpjs\Antrian\AntrianService;
use App\Services\Bpjs\PCare\PCarePendaftaran as PCarePendaftaranService;
use App\Services\RegPeriksaServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnjunganController extends Controller
{
    /**
     * Map English day names to Indonesian day names matching database schedules.
     */
    protected array $hariMap = [
        'Sunday'    => ['AKHAD', 'MINGGU'],
        'Monday'    => ['SENIN'],
        'Tuesday'   => ['SELASA'],
        'Wednesday' => ['RABU'],
        'Thursday'  => ['KAMIS'],
        'Friday'    => ['JUMAT'],
        'Saturday'  => ['SABTU'],
    ];

    /**
     * Halaman Utama Anjungan Pendaftaran Mandiri (APM)
     */
    public function index()
    {
        $setting = Setting::first();
        if ($setting && $setting->logo) {
            $setting->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($setting->logo);
        }

        $today = date('Y-m-d');
        $dayName = date('l');
        $hariArr = $this->hariMap[$dayName] ?? ['SENIN'];

        // Antrean loket hari ini count
        $antreanLoketA = TbList::where('date_list', $today)->where('kd_layanan', 'A')->count();
        $antreanLoketB = TbList::where('date_list', $today)->where('kd_layanan', 'B')->count();

        // Registrasi mandiri hari ini count
        $registrasiHariIni = RegPeriksa::where('tgl_registrasi', $today)->count();

        return view('anjungan.index', compact(
            'setting',
            'today',
            'antreanLoketA',
            'antreanLoketB',
            'registrasiHariIni'
        ));
    }

    /**
     * Ambil Antrean Loket (Khanza tb_list: A = BPJS, B = UMUM)
     */
    public function getAntreanLoket(Request $request): JsonResponse
    {
        $request->validate([
            'jenis' => 'required|in:A,B',
        ]);

        $today = date('Y-m-d');
        $jenis = strtoupper($request->input('jenis')); // 'A' or 'B'
        $label = $jenis === 'A' ? 'LOKET BPJS' : 'LOKET UMUM / ASURANSI';

        try {
            DB::beginTransaction();

            // Insert ke tb_list (Trigger tb_list otomatis mengisi kd_list, date_list, status='Print', dan antrian)
            DB::table('tb_list')->insert([
                'kd_list'    => '',
                'date_list'  => $today,
                'kd_layanan' => $jenis,
                'antrian'    => '',
                'status'     => 'Print',
            ]);

            $tbList = TbList::where('kd_layanan', $jenis)
                ->where('date_list', $today)
                ->orderBy('kd_list', 'desc')
                ->first();

            DB::commit();

            $setting = Setting::first();

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'            => $tbList->kd_list,
                    'nomor_antrean' => $tbList->antrian,
                    'jenis'         => $jenis,
                    'layanan'       => $label,
                    'tanggal'       => Carbon::now()->translatedFormat('l, d F Y'),
                    'jam'           => date('H:i'),
                    'instansi'      => $setting ? $setting->nama_instansi : 'Klinik / FKTP',
                    'alamat'        => $setting ? $setting->alamat_instansi : '',
                    'kontak'        => $setting ? $setting->kontak : '',
                ],
                'message' => "Nomor antrean {$tbList->antrian} berhasil dicetak.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat antrean loket APM: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil nomor antrean: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek Pasien berdasarkan NIK, No. RM, atau No. Kartu BPJS
     */
    public function cekPasien(Request $request): JsonResponse
    {
        $keyword = trim($request->input('keyword', ''));

        if (empty($keyword)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan NIK, No. Rekam Medis, atau No. Kartu BPJS.',
            ], 422);
        }

        $pasien = Pasien::where('no_rkm_medis', $keyword)
            ->orWhere('no_ktp', $keyword)
            ->orWhere('no_peserta', $keyword)
            ->with(['penjab', 'kel', 'kec', 'kab'])
            ->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'status'  => 'not_found',
                'message' => 'Data pasien tidak ditemukan. Untuk pasien baru yang belum pernah berobat, silakan ambil nomor antrean loket pendaftaran.',
            ], 404);
        }

        $today = date('Y-m-d');

        // Cek apakah sudah terdaftar di registrasi hari ini
        $regToday = RegPeriksa::where('no_rkm_medis', $pasien->no_rkm_medis)
            ->where('tgl_registrasi', $today)
            ->with(['poliklinik', 'dokter', 'penjab', 'pcarePendaftaran'])
            ->orderBy('jam_reg', 'desc')
            ->first();

        // Hitung umur
        $umurStr = '-';
        if ($pasien->tgl_lahir) {
            $birthDate = Carbon::parse($pasien->tgl_lahir);
            $diffYears = $birthDate->age;
            $umurStr = "{$diffYears} Th";
        }

        $pasienData = [
            'no_rkm_medis' => $pasien->no_rkm_medis,
            'nm_pasien'    => $pasien->nm_pasien,
            'no_ktp'       => $pasien->no_ktp ?: '-',
            'no_peserta'   => $pasien->no_peserta ?: '-',
            'jk'           => $pasien->jk === 'L' ? 'Laki-Laki' : 'Perempuan',
            'tgl_lahir'    => $pasien->tgl_lahir ? Carbon::parse($pasien->tgl_lahir)->translatedFormat('d-m-Y') : '-',
            'umur'         => $umurStr,
            'alamat'       => trim(($pasien->alamat ?? '') . ' ' . ($pasien->kel->nm_kel ?? '') . ' ' . ($pasien->kec->nm_kec ?? '')),
            'kd_pj'        => $pasien->kd_pj,
            'penjab_nama'  => $pasien->penjab->png_jawab ?? 'UMUM',
            'has_bpjs'     => !empty($pasien->no_peserta) && $pasien->no_peserta !== '-',
        ];

        if ($regToday) {
            return response()->json([
                'success' => true,
                'status'  => 'already_registered',
                'message' => 'Pasien sudah terdaftar berobat untuk hari ini.',
                'pasien'  => $pasienData,
                'registrasi' => [
                    'no_rawat'      => $regToday->no_rawat,
                    'no_reg'        => $regToday->no_reg,
                    'no_urut_pcare' => $regToday->pcarePendaftaran->noUrut ?? null,
                    'nm_poli'       => $regToday->poliklinik->nm_poli ?? '-',
                    'nm_dokter'     => $regToday->dokter->nm_dokter ?? '-',
                    'penjab'        => $regToday->penjab->png_jawab ?? '-',
                    'jam_reg'       => substr($regToday->jam_reg, 0, 5),
                    'stts'          => $regToday->stts,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'status'  => 'ready_to_register',
            'message' => 'Data pasien valid.',
            'pasien'  => $pasienData,
        ]);
    }

    /**
     * Ambil Jadwal Poliklinik & Dokter Hari Ini
     */
    public function getJadwalPoli(Request $request): JsonResponse
    {
        $today = date('Y-m-d');
        $dayName = date('l');
        $hariArr = $this->hariMap[$dayName] ?? ['SENIN'];

        // Cari jadwal aktif untuk hari ini
        $jadwalQuery = Jadwal::whereIn('hari_kerja', $hariArr)
            ->whereHas('poliklinik', function ($q) {
                $q->active()->where('kd_poli', '!=', '-')->where('nm_poli', '!=', '-');
            })
            ->whereHas('dokter', function ($q) {
                $q->where('status', '1')->where('kd_dokter', '!=', '-')->where('nm_dokter', '!=', '-');
            })
            ->with(['poliklinik.maping', 'dokter.maping'])
            ->get();

        $poliList = [];

        if ($jadwalQuery->isNotEmpty()) {
            foreach ($jadwalQuery as $j) {
                if (!$j->dokter || $j->dokter->kd_dokter === '-' || $j->dokter->nm_dokter === '-') {
                    continue;
                }

                $kdPoli = $j->kd_poli;
                $kdDokter = $j->kd_dokter;

                // Hitung kuota & terdaftar hari ini
                $terdaftar = RegPeriksa::where('kd_poli', $kdPoli)
                    ->where('kd_dokter', $kdDokter)
                    ->where('tgl_registrasi', $today)
                    ->count();

                $kuota = (int) $j->kuota;
                $sisaKuota = $kuota > 0 ? max(0, $kuota - $terdaftar) : 999;
                $isFull = $kuota > 0 && $terdaftar >= $kuota;

                if (!isset($poliList[$kdPoli])) {
                    $poliList[$kdPoli] = [
                        'kd_poli'        => $kdPoli,
                        'nm_poli'        => $j->poliklinik->nm_poli,
                        'kd_poli_pcare'  => $j->poliklinik->maping->kd_poli_pcare ?? '',
                        'nm_poli_pcare'  => $j->poliklinik->maping->nm_poli_pcare ?? '',
                        'doctors'        => [],
                    ];
                }

                $poliList[$kdPoli]['doctors'][] = [
                    'kd_dokter'        => $kdDokter,
                    'nm_dokter'        => $j->dokter->nm_dokter,
                    'kd_dokter_pcare'  => $j->dokter->maping->kd_dokter_pcare ?? '',
                    'jam_mulai'        => substr($j->jam_mulai, 0, 5),
                    'jam_selesai'      => substr($j->jam_selesai, 0, 5),
                    'jam_praktek'      => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                    'kuota'            => $kuota,
                    'terdaftar'        => $terdaftar,
                    'sisa_kuota'       => $sisaKuota,
                    'is_full'          => $isFull,
                ];
            }
        } else {
            // Fallback jika tidak ada data jadwal hari ini di database:
            // Tampilkan poliklinik aktif dan dokter aktif agar kiosk tetap dapat melayani
            $polis = Poliklinik::active()->where('kd_poli', '!=', '-')->where('nm_poli', '!=', '-')->with('maping')->get();
            $dokters = Dokter::where('status', '1')->where('kd_dokter', '!=', '-')->where('nm_dokter', '!=', '-')->with('maping')->get();

            foreach ($polis as $p) {
                $docs = [];
                foreach ($dokters as $d) {
                    $terdaftar = RegPeriksa::where('kd_poli', $p->kd_poli)
                        ->where('kd_dokter', $d->kd_dokter)
                        ->where('tgl_registrasi', $today)
                        ->count();

                    $docs[] = [
                        'kd_dokter'        => $d->kd_dokter,
                        'nm_dokter'        => $d->nm_dokter,
                        'kd_dokter_pcare'  => $d->maping->kd_dokter_pcare ?? '',
                        'jam_mulai'        => '08:00',
                        'jam_selesai'      => '20:00',
                        'jam_praktek'      => '08:00 - 20:00',
                        'kuota'            => 100,
                        'terdaftar'        => $terdaftar,
                        'sisa_kuota'       => max(0, 100 - $terdaftar),
                        'is_full'          => false,
                    ];
                }

                $poliList[$p->kd_poli] = [
                    'kd_poli'        => $p->kd_poli,
                    'nm_poli'        => $p->nm_poli,
                    'kd_poli_pcare'  => $p->maping->kd_poli_pcare ?? '',
                    'nm_poli_pcare'  => $p->maping->nm_poli_pcare ?? '',
                    'doctors'        => $docs,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'hari'    => $hariArr[0],
            'tanggal' => Carbon::now()->translatedFormat('l, d F Y'),
            'data'    => array_values($poliList),
        ]);
    }

    /**
     * Proses Pendaftaran Mandiri (Pasien Umum & BPJS)
     */
    public function daftarMandiri(Request $request): JsonResponse
    {
        $request->validate([
            'no_rkm_medis' => 'required',
            'kd_poli'      => 'required',
            'kd_dokter'    => 'required',
            'jenis_bayar'  => 'required|in:BPJS,UMUM',
        ]);

        $today = date('Y-m-d');
        $pasien = Pasien::where('no_rkm_medis', $request->no_rkm_medis)->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.',
            ], 404);
        }

        // Cek double registration hari ini
        $already = RegPeriksa::where('no_rkm_medis', $pasien->no_rkm_medis)
            ->where('tgl_registrasi', $today)
            ->first();

        if ($already) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien sudah terdaftar berobat hari ini pada no rawat: ' . $already->no_rawat,
            ], 409);
        }

        // Tentukan penjamin: 'BPJ' untuk BPJS atau 'A09' untuk UMUM
        $isBpjs = $request->jenis_bayar === 'BPJS';
        $kdPj = $isBpjs ? 'BPJ' : 'A09';

        if ($isBpjs && (empty($pasien->no_peserta) || $pasien->no_peserta === '-')) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien belum memiliki Nomor Kartu BPJS terdaftar di rekam medis. Silakan pilih kategori UMUM atau hubungi loket.',
            ], 422);
        }

        // Ambil data poliklinik & dokter
        $poli = Poliklinik::with('maping')->where('kd_poli', $request->kd_poli)->first();
        $dokter = Dokter::with('maping')->where('kd_dokter', $request->kd_dokter)->first();

        if (!$poli || !$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Poliklinik atau dokter yang dipilih tidak valid.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Generate No Rawat
            $noRawat = RegPeriksaServices::setNoRawat(new RegPeriksa(), $today);

            // 2. Generate No Reg
            $lastReg = RegPeriksa::where([
                'kd_poli'        => $request->kd_poli,
                'kd_dokter'      => $request->kd_dokter,
                'tgl_registrasi' => $today,
            ])->orderBy('no_reg', 'desc')->first();

            $noRegNum = $lastReg ? (int)$lastReg->no_reg + 1 : 1;
            $noReg = sprintf('%03d', $noRegNum);

            // Status poli
            $pernahPoli = RegPeriksa::where([
                'no_rkm_medis' => $pasien->no_rkm_medis,
                'kd_poli'      => $request->kd_poli,
            ])->exists();
            $statusPoli = $pernahPoli ? 'Lama' : 'Baru';

            // Umur daftar
            $umurTh = 0;
            if ($pasien->tgl_lahir) {
                $umurTh = Carbon::parse($pasien->tgl_lahir)->age;
            }

            // Hitung biaya_reg otomatis dari tabel poliklinik (pasien APM mandiri berstatus Lama)
            $biayaReg = 0;
            if ($kdPj !== 'BPJ') {
                $poli = Poliklinik::select('registrasi', 'registrasilama')->where('kd_poli', $request->kd_poli)->first();
                if ($poli) {
                    $biayaReg = (float) $poli->registrasilama;
                }
            }

            $regData = [
                'no_reg'        => $noReg,
                'no_rawat'      => $noRawat,
                'tgl_registrasi'=> $today,
                'jam_reg'       => date('H:i:s'),
                'kd_dokter'     => $request->kd_dokter,
                'kd_poli'       => $request->kd_poli,
                'p_jawab'       => $pasien->namakeluarga ?: $pasien->nm_pasien,
                'almt_pj'       => $pasien->alamatpj ?: ($pasien->alamat ?: '-'),
                'hubunganpj'    => $pasien->keluarga ?: 'Diri Sendiri',
                'biaya_reg'     => $biayaReg,
                'stts'          => 'Belum',
                'stts_daftar'   => 'Lama',
                'status_lanjut' => 'Ralan',
                'kd_pj'         => $kdPj,
                'no_rkm_medis'  => $pasien->no_rkm_medis,
                'umurdaftar'    => $umurTh,
                'sttsumur'      => 'Th',
                'status_bayar'  => 'Belum Bayar',
                'status_poli'   => $statusPoli,
            ];

            $regPeriksa = RegPeriksa::create($regData);

            // Update umur pasien di master pasien
            $pasien->update(['umur' => "{$umurTh} Th"]);

            DB::commit();

            // 3. Integrasi BPJS (Antrol & PCare) jika pasien BPJS
            $bpjsNotice = null;
            $noUrutPcare = null;

            if ($isBpjs) {
                $kdPoliPcare = $poli->maping->kd_poli_pcare ?? $poli->kd_poli;
                $nmPoliPcare = $poli->maping->nm_poli_pcare ?? $poli->nm_poli;
                $kdDokterPcare = $dokter->maping->kd_dokter_pcare ?? 0;

                // A. Bridging BPJS Antrean (Antrol FKTP) jika enabled
                if (config('bpjs.antrian.enabled', false)) {
                    try {
                        $antrianService = new AntrianService();
                        $jamPraktek = '08:00-14:00';
                        $jadwal = Jadwal::where('kd_dokter', $dokter->kd_dokter)->first();
                        if ($jadwal && $jadwal->jam_mulai && $jadwal->jam_selesai) {
                            $jamPraktek = substr($jadwal->jam_mulai, 0, 5) . '-' . substr($jadwal->jam_selesai, 0, 5);
                        }

                        $antrianPayload = [
                            'nomorkartu'     => $pasien->no_peserta,
                            'nik'            => $pasien->no_ktp ?: '',
                            'nohp'           => $pasien->no_tlp ?: '08000000000',
                            'kodepoli'       => $kdPoliPcare,
                            'namapoli'       => $nmPoliPcare,
                            'norm'           => $pasien->no_rkm_medis,
                            'tanggalperiksa' => $today,
                            'kodedokter'     => (int) $kdDokterPcare,
                            'namadokter'     => $dokter->nm_dokter,
                            'jampraktek'     => $jamPraktek,
                            'nomorantrean'   => $noReg,
                            'angkaantrean'   => (int) $noReg,
                            'keterangan'     => 'Kiosk Anjungan Mandiri (APM)',
                        ];

                        $antrolRes = $antrianService->add($antrianPayload);
                        Log::info('APM BPJS Antrol Response: ' . json_encode($antrolRes));
                    } catch (\Throwable $e) {
                        Log::warning('APM Antrol BPJS Warning: ' . $e->getMessage());
                    }
                }

                // B. Bridging PCare Pendaftaran
                try {
                    $pcareService = new PCarePendaftaranService();
                    $pcarePayload = [
                        'kdProviderPeserta' => '',
                        'tgl_daftar'        => date('d-m-Y'),
                        'no_peserta'        => $pasien->no_peserta,
                        'kd_poli_pcare'     => $kdPoliPcare,
                        'keluhan'           => 'Pemeriksaan Rawat Jalan Mandiri',
                        'sistole'           => 0,
                        'diastole'          => 0,
                        'berat'             => 0,
                        'tinggi'            => 0,
                        'respirasi'         => 0,
                        'lingkar_perut'     => 0,
                        'nadi'              => 0,
                        'kdTkp'             => '10',
                    ];

                    $pcareResult = $pcareService->create($pcarePayload);

                    if (isset($pcareResult['response']['message']) && is_string($pcareResult['response']['message'])) {
                        // noUrut PCare sering berupa respons nomor urut
                        $noUrutPcare = $pcareResult['response']['message'];
                    } elseif (isset($pcareResult['response']['noUrut'])) {
                        $noUrutPcare = $pcareResult['response']['noUrut'];
                    }

                    // Simpan ke tabel pcare_pendaftaran lokal
                    if ($noUrutPcare) {
                        PcarePendaftaran::create([
                            'no_rawat'      => $noRawat,
                            'tglDaftar'     => $today,
                            'noKartu'       => $pasien->no_peserta,
                            'kdPoli'        => $kdPoliPcare,
                            'nmPoli'        => $nmPoliPcare,
                            'keluhan'       => '-',
                            'kunjSakit'     => 'true',
                            'sistole'       => 0,
                            'diastole'      => 0,
                            'beratBadan'    => 0,
                            'tinggiBadan'   => 0,
                            'respRate'      => 0,
                            'lingkar_perut' => 0,
                            'heartRate'     => 0,
                            'rujukBalik'    => 0,
                            'kdTkp'         => '10 Rawat Jalan',
                            'noUrut'        => $noUrutPcare,
                            'status'        => 'Terkirim',
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('APM PCare Pendaftaran Warning: ' . $e->getMessage());
                    $bpjsNotice = 'Pendaftaran tersimpan di klinik. Sinkronisasi PCare dapat dikonfirmasi oleh petugas.';
                }
            }

            $setting = Setting::first();

            return response()->json([
                'success' => true,
                'data'    => [
                    'no_rawat'      => $noRawat,
                    'no_reg'        => $noReg,
                    'no_urut_display'=> $noUrutPcare ?: $noReg,
                    'nm_pasien'     => $pasien->nm_pasien,
                    'no_rkm_medis'  => $pasien->no_rkm_medis,
                    'nm_poli'       => $poli->nm_poli,
                    'nm_dokter'     => $dokter->nm_dokter,
                    'penjamin'      => $isBpjs ? 'BPJS KESEHATAN' : 'UMUM',
                    'tanggal'       => Carbon::now()->translatedFormat('l, d F Y'),
                    'jam'           => date('H:i'),
                    'umur'          => "{$umurTh} Th",
                    'alamat'        => $pasien->alamat ?: '-',
                    'instansi'      => $setting ? $setting->nama_instansi : 'Klinik / FKTP',
                    'alamat_instansi' => $setting ? $setting->alamat_instansi : '',
                    'kontak_instansi' => $setting ? $setting->kontak : '',
                    'bpjs_notice'   => $bpjsNotice,
                ],
                'message' => 'Pendaftaran berhasil. Silakan ambil struk bukti registrasi Anda.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal daftar mandiri APM: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mendaftar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cetak Struk Bukti Thermal (58mm / 80mm)
     */
    public function cetakStruk(Request $request, string $jenis, string $id)
    {
        $setting = Setting::first();
        $paperWidth = $request->input('width', '80'); // 58 atau 80 mm

        if ($jenis === 'loket') {
            $antrean = TbList::where('kd_list', $id)->first();
            if (!$antrean) {
                abort(404, 'Data antrean loket tidak ditemukan');
            }

            $label = $antrean->kd_layanan === 'A' ? 'LOKET BPJS' : 'LOKET UMUM / ASURANSI';

            return view('anjungan.cetak_struk_loket', compact('antrean', 'label', 'setting', 'paperWidth'));
        }

        if ($jenis === 'registrasi') {
            $reg = RegPeriksa::where('no_rawat', $id)
                ->with(['pasien.kel', 'pasien.kec', 'dokter', 'poliklinik', 'penjab', 'pcarePendaftaran'])
                ->first();

            if (!$reg) {
                abort(404, 'Data registrasi tidak ditemukan');
            }

            return view('anjungan.cetak_struk_registrasi', compact('reg', 'setting', 'paperWidth'));
        }

        abort(404, 'Tipe struk tidak valid');
    }
}
