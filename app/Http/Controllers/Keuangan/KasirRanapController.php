<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirRanapController extends Controller
{
    public function index(Request $request)
    {
        $bangsal = DB::table('bangsal')->where('status', '1')->orderBy('nm_bangsal')->get();
        $kamar = DB::table('kamar')->where('statusdata', '1')->orderBy('kd_kamar')->get();
        $penjab = DB::table('penjab')->orderBy('png_jawab')->get();
        $dokter = DB::table('dokter')->where('status', '1')->orderBy('nm_dokter')->get();
        $petugas = DB::table('petugas')->where('status', '1')->orderBy('nama')->get();

        return view('content.keuangan.kasirRanap', compact('bangsal', 'kamar', 'penjab', 'dokter', 'petugas'));
    }

    public function getAntrean(Request $request)
    {
        $statusPulang = $request->status_pulang ?? 'Belum Pulang'; // 'Belum Pulang', 'Sudah Pulang', 'Semua'
        $statusBayar = $request->status_bayar ?? 'Belum Bayar';   // 'Belum Bayar', 'Sudah Bayar', 'Semua'
        $tglAwal = $request->tgl_awal ? Carbon::parse($request->tgl_awal)->format('Y-m-d') : date('Y-m-d');
        $tglAkhir = $request->tgl_akhir ? Carbon::parse($request->tgl_akhir)->format('Y-m-d') : date('Y-m-d');
        $filterTglType = $request->filter_tgl_type ?? 'masuk'; // 'masuk' or 'keluar'
        $kdBangsal = $request->kd_bangsal;
        $kdKamar = $request->kd_kamar;
        $kdPj = $request->kd_pj;
        $keyword = $request->keyword;

        // Subquery to get the latest kamar_inap record per no_rawat
        $latestKamarSub = DB::table('kamar_inap as ki1')
            ->select('ki1.*')
            ->join(DB::raw('(SELECT no_rawat, MAX(CONCAT(tgl_masuk, " ", jam_masuk)) as max_masuk FROM kamar_inap GROUP BY no_rawat) as ki2'), function ($join) {
                $join->on('ki1.no_rawat', '=', 'ki2.no_rawat')
                     ->on(DB::raw('CONCAT(ki1.tgl_masuk, " ", ki1.jam_masuk)'), '=', 'ki2.max_masuk');
            });

        $query = DB::table('reg_periksa')
            ->joinSub($latestKamarSub, 'ki', 'reg_periksa.no_rawat', '=', 'ki.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('kamar', 'ki.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->leftJoin('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->leftJoin('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->leftJoin('nota_inap', 'reg_periksa.no_rawat', '=', 'nota_inap.no_rawat');

        // Status Pulang Filter
        if ($statusPulang === 'Belum Pulang') {
            $query->where(function ($q) {
                $q->where('ki.stts_pulang', '-')
                  ->orWhere('ki.tgl_keluar', '0000-00-00')
                  ->orWhereNull('ki.tgl_keluar');
            });
        } elseif ($statusPulang === 'Sudah Pulang') {
            $query->where('ki.stts_pulang', '!=', '-')
                  ->where('ki.tgl_keluar', '!=', '0000-00-00')
                  ->whereNotNull('ki.tgl_keluar');
        }

        // Status Bayar Filter
        if ($statusBayar && $statusBayar !== 'Semua') {
            $query->where('reg_periksa.status_bayar', $statusBayar);
        }

        // Tanggal Filter
        if ($request->tgl_awal && $request->tgl_akhir) {
            if ($filterTglType === 'keluar') {
                $query->whereBetween('ki.tgl_keluar', [$tglAwal, $tglAkhir]);
            } else {
                $query->whereBetween('ki.tgl_masuk', [$tglAwal, $tglAkhir]);
            }
        }

        // Room & Penjamin Filter
        if ($kdBangsal) {
            $query->where('kamar.kd_bangsal', $kdBangsal);
        }
        if ($kdKamar) {
            $query->where('ki.kd_kamar', $kdKamar);
        }
        if ($kdPj) {
            $query->where('reg_periksa.kd_pj', $kdPj);
        }

        // Keyword Search
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('reg_periksa.no_rawat', 'like', "%{$keyword}%")
                  ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$keyword}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$keyword}%")
                  ->orWhere('kamar.kd_kamar', 'like', "%{$keyword}%")
                  ->orWhere('bangsal.nm_bangsal', 'like', "%{$keyword}%");
            });
        }

        $list = $query->select(
            'reg_periksa.no_rawat',
            'reg_periksa.no_rkm_medis',
            'reg_periksa.kd_dokter',
            'reg_periksa.kd_pj',
            'reg_periksa.tgl_registrasi',
            'reg_periksa.jam_reg',
            'reg_periksa.status_bayar',
            'reg_periksa.stts',
            'reg_periksa.biaya_reg',
            'pasien.nm_pasien',
            'dokter.nm_dokter',
            'penjab.png_jawab',
            'ki.kd_kamar',
            'ki.trf_kamar',
            'ki.tgl_masuk',
            'ki.jam_masuk',
            'ki.tgl_keluar',
            'ki.jam_keluar',
            'ki.lama',
            'ki.ttl_biaya',
            'ki.stts_pulang',
            'bangsal.nm_bangsal',
            'nota_inap.no_nota',
            'nota_inap.tanggal as tgl_nota',
            'nota_inap.jam as jam_nota'
        )->orderBy('reg_periksa.status_bayar', 'ASC')
         ->orderBy('ki.tgl_masuk', 'DESC')
         ->orderBy('ki.jam_masuk', 'DESC')
         ->get();

        $noRawats = $list->pluck('no_rawat')->toArray();

        // Check unvalidated ranap prescriptions
        $unvalidatedReseps = DB::table('resep_obat')
            ->whereIn('no_rawat', $noRawats)
            ->where('status', 'ranap')
            ->where(function ($q) {
                $q->where('tgl_perawatan', '0000-00-00')
                  ->orWhereNull('tgl_perawatan');
            })
            ->get()
            ->groupBy('no_rawat');

        // Check deposit per no_rawat
        $deposits = DB::table('deposit')
            ->whereIn('no_rawat', $noRawats)
            ->select('no_rawat', DB::raw('SUM(besar_deposit) as total_deposit'))
            ->groupBy('no_rawat')
            ->pluck('total_deposit', 'no_rawat');

        $data = [];
        foreach ($list as $item) {
            $isPulang = ($item->stts_pulang !== '-' && $item->tgl_keluar !== '0000-00-00' && !empty($item->tgl_keluar));

            $sttsPulangLabel = $isPulang ? $item->stts_pulang : 'Masih Dirawat';

            // Calculate duration
            $tglMasuk = Carbon::parse($item->tgl_masuk);
            $tglKeluar = $isPulang ? Carbon::parse($item->tgl_keluar) : Carbon::now();
            $durasiHari = $tglMasuk->diffInDays($tglKeluar);
            if ($durasiHari == 0) $durasiHari = 1;

            $hasUnvalidated = false;
            $unvalidatedCount = 0;
            if (isset($unvalidatedReseps[$item->no_rawat])) {
                foreach ($unvalidatedReseps[$item->no_rawat] as $r) {
                    $hasItems = DB::table('resep_dokter')->where('no_resep', $r->no_resep)->exists()
                        || DB::table('resep_dokter_racikan')->where('no_resep', $r->no_resep)->exists();
                    if ($hasItems) {
                        $unvalidatedCount++;
                    }
                }
                $hasUnvalidated = $unvalidatedCount > 0;
            }

            $depositAmount = floatval($deposits[$item->no_rawat] ?? 0);

            $data[] = [
                'no_rawat' => $item->no_rawat,
                'no_rkm_medis' => $item->no_rkm_medis,
                'nm_pasien' => $item->nm_pasien,
                'kd_kamar' => $item->kd_kamar,
                'nm_bangsal' => $item->nm_bangsal,
                'kamar_full' => "{$item->kd_kamar} - {$item->nm_bangsal}",
                'trf_kamar' => floatval($item->trf_kamar),
                'tgl_masuk' => date('d/m/Y', strtotime($item->tgl_masuk)),
                'jam_masuk' => $item->jam_masuk,
                'tgl_keluar' => $isPulang ? date('d/m/Y', strtotime($item->tgl_keluar)) : '-',
                'jam_keluar' => $isPulang ? $item->jam_keluar : '-',
                'durasi_hari' => $durasiHari,
                'is_pulang' => $isPulang,
                'stts_pulang' => $sttsPulangLabel,
                'status_bayar' => $item->status_bayar,
                'no_nota' => $item->no_nota ?? '-',
                'tgl_nota' => $item->tgl_nota ? date('d/m/Y', strtotime($item->tgl_nota)) : '-',
                'jam_nota' => $item->jam_nota ?? '-',
                'nm_dokter' => $item->nm_dokter ?? '-',
                'png_jawab' => $item->png_jawab ?? '-',
                'has_unvalidated_resep' => $hasUnvalidated,
                'unvalidated_count' => $unvalidatedCount,
                'deposit' => $depositAmount
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'count' => count($data)
        ]);
    }

    public function closeBillingRanap(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'payments' => 'required|array',
            'payments.*.nama_bayar' => 'required|string',
            'payments.*.besar_bayar' => 'required|numeric',
            'potongan' => 'nullable|numeric',
            'tambahan' => 'nullable|numeric',
            'kd_rek_piutang' => 'nullable|string',
            'tgl_bayar' => 'nullable|date'
        ]);

        $no_rawat = $request->no_rawat;
        $tgl_bayar = $request->tgl_bayar ?? date('Y-m-d');
        $jam_bayar = date('H:i:s');

        try {
            DB::beginTransaction();

            $reg = DB::table('reg_periksa')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->where('reg_periksa.no_rawat', $no_rawat)
                ->select('reg_periksa.biaya_reg', 'pasien.nm_pasien', 'reg_periksa.no_rkm_medis', 'reg_periksa.almt_pj', 'reg_periksa.kd_pj')
                ->first();

            if (!$reg) {
                throw new \Exception('Registrasi rawat inap tidak ditemukan');
            }

            $biaya_reg = floatval($reg->biaya_reg);

            // 1. Kamar & Inap
            $kamar_inap = DB::table('kamar_inap')
                ->where('no_rawat', $no_rawat)
                ->get();

            if ($kamar_inap->isEmpty()) {
                throw new \Exception('Pasien tidak memiliki riwayat kamar inap');
            }

            $total_kamar = 0;
            foreach ($kamar_inap as $ki) {
                $tgl_masuk = Carbon::parse($ki->tgl_masuk);
                $tgl_keluar = ($ki->stts_pulang != 'Pindah Kamar' && ($ki->tgl_keluar == '0000-00-00' || !$ki->tgl_keluar)) 
                    ? Carbon::now() 
                    : Carbon::parse($ki->tgl_keluar);
                
                $durasi = $tgl_masuk->diffInDays($tgl_keluar);
                if ($durasi == 0) $durasi = 1;
                $subtotal = ($durasi * floatval($ki->trf_kamar));
                $total_kamar += $subtotal;

                // Update kamar_inap lama & ttl_biaya if not yet filled
                DB::table('kamar_inap')
                    ->where('no_rawat', $no_rawat)
                    ->where('kd_kamar', $ki->kd_kamar)
                    ->where('tgl_masuk', $ki->tgl_masuk)
                    ->where('jam_masuk', $ki->jam_masuk)
                    ->update([
                        'lama' => $durasi,
                        'ttl_biaya' => $subtotal
                    ]);
            }

            // 2. Tindakan Ranap
            $tindakan_dr = floatval(DB::table('rawat_inap_dr')->where('no_rawat', $no_rawat)->sum('biaya_rawat'));
            $tindakan_pr = floatval(DB::table('rawat_inap_pr')->where('no_rawat', $no_rawat)->sum('biaya_rawat'));
            $tindakan_drpr = floatval(DB::table('rawat_inap_drpr')->where('no_rawat', $no_rawat)->sum('biaya_rawat'));
            $total_tindakan = $tindakan_dr + $tindakan_pr + $tindakan_drpr;

            // 3. Obat & Alkes Ranap
            $total_obat = floatval(DB::table('detail_pemberian_obat')->where('no_rawat', $no_rawat)->sum('total'));

            // 4. Laboratorium
            $total_lab = floatval(DB::table('periksa_lab')->where('no_rawat', $no_rawat)->sum('biaya'));

            // 5. Radiologi
            $total_rad = floatval(DB::table('periksa_radiologi')->where('no_rawat', $no_rawat)->sum('biaya'));

            // 6. Tambahan & Potongan
            $potongan = floatval($request->potongan ?? 0);
            $tambahan = floatval($request->tambahan ?? 0);

            DB::table('tambahan_biaya')->where('no_rawat', $no_rawat)->delete();
            if ($tambahan > 0) {
                DB::table('tambahan_biaya')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_biaya' => 'Tambahan Biaya Ranap',
                    'besar_biaya' => $tambahan
                ]);
            }

            DB::table('pengurangan_biaya')->where('no_rawat', $no_rawat)->delete();
            if ($potongan > 0) {
                DB::table('pengurangan_biaya')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_pengurangan' => 'Potongan Biaya Ranap',
                    'besar_pengurangan' => $potongan
                ]);
            }

            // 7. Titipan Uang Muka / Deposit
            $uang_deposit = floatval(DB::table('deposit')->where('no_rawat', $no_rawat)->sum('besar_deposit'));
            // If already in nota_inap and no deposit table entries
            $existingNota = DB::table('nota_inap')->where('no_rawat', $no_rawat)->first();
            if ($existingNota && floatval($existingNota->Uang_Muka) > 0 && $uang_deposit == 0) {
                $uang_deposit = floatval($existingNota->Uang_Muka);
            }

            $grand_total = ($biaya_reg + $total_kamar + $total_tindakan + $total_obat + $total_lab + $total_rad + $tambahan) - $potongan;
            $net_tagihan = max(0, $grand_total - $uang_deposit);

            $total_bayar = 0;
            foreach ($request->payments as $pay) {
                $total_bayar += floatval($pay['besar_bayar'] ?? 0);
            }

            $piutang = $net_tagihan - $total_bayar;
            $sisadeposit = ($grand_total < $uang_deposit) ? ($uang_deposit - $grand_total) : 0;

            // Generate No Nota Ranap (Format: YYYY/MM/DD/RI0001)
            $date_prefix = str_replace('-', '/', $tgl_bayar) . '/RI';
            $max_nota = DB::table('nota_inap')
                ->where('tanggal', $tgl_bayar)
                ->max('no_nota');
            $next_num = 1;
            if ($max_nota) {
                $last_four = substr($max_nota, -4);
                if (is_numeric($last_four)) {
                    $next_num = intval($last_four) + 1;
                }
            }
            $no_nota = $date_prefix . str_pad($next_num, 4, '0', STR_PAD_LEFT);

            // Update status_bayar in reg_periksa
            DB::table('reg_periksa')
                ->where('no_rawat', $no_rawat)
                ->update(['status_bayar' => 'Sudah Bayar']);

            // Clean up previous billing records for this rawat
            DB::table('nota_inap')->where('no_rawat', $no_rawat)->delete();
            DB::table('detail_nota_inap')->where('no_rawat', $no_rawat)->delete();
            DB::table('piutang_pasien')->where('no_rawat', $no_rawat)->delete();
            DB::table('tagihan_sadewa')->where('no_nota', $no_rawat)->delete();

            // Reversal old journals if any (Audit Trail)
            $existingJurnals = DB::table('jurnal')
                ->where('no_bukti', $no_rawat)
                ->where(function($query) {
                    $query->where('keterangan', 'like', 'PEMBAYARAN PASIEN RAWAT INAP%')
                          ->orWhere('keterangan', 'like', 'PIUTANG PASIEN RAWAT INAP%');
                })
                ->where('jenis', 'U')
                ->get();

            if ($existingJurnals->isNotEmpty()) {
                foreach ($existingJurnals as $oldJurnal) {
                    DB::table('jurnal')
                        ->where('no_jurnal', $oldJurnal->no_jurnal)
                        ->update([
                            'keterangan' => $oldJurnal->keterangan . ' [DIBATALKAN]',
                            'jenis' => 'K'
                        ]);

                    $oldDetails = DB::table('detailjurnal')
                        ->where('no_jurnal', $oldJurnal->no_jurnal)
                        ->get();

                    if ($oldDetails->isNotEmpty()) {
                        $no_jurnal_reversal = $this->generateNoJurnal();
                        DB::table('jurnal')->insert([
                            'no_jurnal' => $no_jurnal_reversal,
                            'tgl_jurnal' => date('Y-m-d'),
                            'jam_jurnal' => date('H:i:s'),
                            'no_bukti' => $no_rawat,
                            'jenis' => 'K',
                            'keterangan' => 'PEMBALIKAN ' . $oldJurnal->keterangan
                        ]);

                        $reversalDetails = $oldDetails->map(function($d) use ($no_jurnal_reversal) {
                            return [
                                'no_jurnal' => $no_jurnal_reversal,
                                'kd_rek'    => $d->kd_rek,
                                'debet'     => $d->kredit,
                                'kredit'    => $d->debet,
                            ];
                        })->toArray();

                        DB::table('detailjurnal')->insert($reversalDetails);
                    }
                }
            }

            // Insert into nota_inap
            DB::table('nota_inap')->insert([
                'no_rawat'   => $no_rawat,
                'no_nota'    => $no_nota,
                'tanggal'    => $tgl_bayar,
                'jam'        => $jam_bayar,
                'Uang_Muka'  => $uang_deposit
            ]);

            // Rebuild tabel billing (Khanza standard)
            DB::table('billing')->where('no_rawat', $no_rawat)->delete();
            $billingRows = [];
            $noIdx = 0;

            // Registrasi
            if ($biaya_reg > 0) {
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'      => $tgl_bayar,
                    'no'           => 'Registrasi',
                    'nm_perawatan' => 'Biaya Registrasi',
                    'pemisah'      => ':',
                    'biaya'        => $biaya_reg,
                    'jumlah'       => 1,
                    'tambahan'     => 0,
                    'totalbiaya'   => $biaya_reg,
                    'status'       => 'Registrasi',
                ];
            }

            // Kamar Inap
            foreach ($kamar_inap as $ki) {
                $subtotal = $ki->lama > 0 ? ($ki->lama * $ki->trf_kamar) : $ki->ttl_biaya;
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'      => $tgl_bayar,
                    'no'           => 'Kamar',
                    'nm_perawatan' => "Sewa Kamar {$ki->kd_kamar}",
                    'pemisah'      => ':',
                    'biaya'        => $ki->trf_kamar,
                    'jumlah'       => $ki->lama ?: 1,
                    'tambahan'     => 0,
                    'totalbiaya'   => $subtotal,
                    'status'       => 'Kamar Inap',
                ];
            }

            // Tindakan Dokter & Paramedis
            $tindakan_sources = [
                ['table' => 'rawat_inap_dr',   'status' => 'Ranap Dokter'],
                ['table' => 'rawat_inap_pr',   'status' => 'Ranap Paramedis'],
                ['table' => 'rawat_inap_drpr', 'status' => 'Ranap Dokter Paramedis'],
            ];

            foreach ($tindakan_sources as $src) {
                $rows = DB::table($src['table'])
                    ->join('jns_perawatan_inap', $src['table'] . '.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
                    ->where($src['table'] . '.no_rawat', $no_rawat)
                    ->select('jns_perawatan_inap.nm_perawatan', $src['table'] . '.biaya_rawat')
                    ->get();

                foreach ($rows as $tr) {
                    $billingRows[] = [
                        'noindex'      => $noIdx++,
                        'no_rawat'     => $no_rawat,
                        'tgl_byr'      => $tgl_bayar,
                        'no'           => 'Tindakan',
                        'nm_perawatan' => $tr->nm_perawatan,
                        'pemisah'      => ':',
                        'biaya'        => $tr->biaya_rawat,
                        'jumlah'       => 1,
                        'tambahan'     => 0,
                        'totalbiaya'   => $tr->biaya_rawat,
                        'status'       => $src['status'],
                    ];
                }
            }

            // Obat Ranap
            $obat_rows = DB::table('detail_pemberian_obat')
                ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
                ->where('detail_pemberian_obat.no_rawat', $no_rawat)
                ->select('databarang.nama_brng', 'detail_pemberian_obat.biaya_obat', 'detail_pemberian_obat.jml', 'detail_pemberian_obat.total')
                ->get();

            foreach ($obat_rows as $or) {
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'      => $tgl_bayar,
                    'no'           => 'Obat & BHP',
                    'nm_perawatan' => $or->nama_brng,
                    'pemisah'      => ':',
                    'biaya'        => $or->biaya_obat,
                    'jumlah'       => $or->jml,
                    'tambahan'     => 0,
                    'totalbiaya'   => $or->total,
                    'status'       => 'Obat',
                ];
            }

            // Laborat
            $lab_rows = DB::table('periksa_lab')
                ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
                ->where('periksa_lab.no_rawat', $no_rawat)
                ->select('jns_perawatan_lab.nm_perawatan', 'periksa_lab.biaya')
                ->get();

            foreach ($lab_rows as $lr) {
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'      => $tgl_bayar,
                    'no'           => 'Laborat',
                    'nm_perawatan' => $lr->nm_perawatan,
                    'pemisah'      => ':',
                    'biaya'        => $lr->biaya,
                    'jumlah'       => 1,
                    'tambahan'     => 0,
                    'totalbiaya'   => $lr->biaya,
                    'status'       => 'Laborat',
                ];
            }

            // Radiologi
            $rad_rows = DB::table('periksa_radiologi')
                ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
                ->where('periksa_radiologi.no_rawat', $no_rawat)
                ->select('jns_perawatan_radiologi.nm_perawatan', 'periksa_radiologi.biaya')
                ->get();

            foreach ($rad_rows as $rr) {
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'      => $tgl_bayar,
                    'no'           => 'Radiologi',
                    'nm_perawatan' => $rr->nm_perawatan,
                    'pemisah'      => ':',
                    'biaya'        => $rr->biaya,
                    'jumlah'       => 1,
                    'tambahan'     => 0,
                    'totalbiaya'   => $rr->biaya,
                    'status'       => 'Radiologi',
                ];
            }

            if (!empty($billingRows)) {
                DB::table('billing')->insert($billingRows);
            }

            // Load Akun Ranap mapping
            $setAkunRanap = DB::table('set_akun_ranap')->first();
            $setAkunRanap2 = DB::table('set_akun_ranap2')->first();

            if (!$setAkunRanap) {
                throw new \Exception('Mapping akun rawat inap belum terkonfigurasi (set_akun_ranap kosong)');
            }

            // Prepare Journal
            $jurnalItems = [];
            $addJurnal = function ($kd, $nm, $debet, $kredit) use (&$jurnalItems) {
                if (empty($kd)) return;
                $debet = floatval($debet);
                $kredit = floatval($kredit);
                if ($debet == 0 && $kredit == 0) return;

                if (isset($jurnalItems[$kd])) {
                    $jurnalItems[$kd]['debet'] += $debet;
                    $jurnalItems[$kd]['kredit'] += $kredit;
                } else {
                    $jurnalItems[$kd] = [
                        'kd_rek' => $kd,
                        'nm_rek' => $nm,
                        'debet' => $debet,
                        'kredit' => $kredit
                    ];
                }
            };

            // 1. Debets: Payments
            foreach ($request->payments as $pay) {
                $besar_bayar = floatval($pay['besar_bayar']);
                if ($besar_bayar <= 0) continue;

                $akunBayar = DB::table('akun_bayar')
                    ->where('nama_bayar', $pay['nama_bayar'])
                    ->first();

                if (!$akunBayar) {
                    throw new \Exception('Akun bayar tidak ditemukan: ' . $pay['nama_bayar']);
                }

                $ppnPercent = floatval($akunBayar->ppn ?? 0);
                $besarppn = 0;
                if ($ppnPercent > 0) {
                    $besarppn = round($besar_bayar * ($ppnPercent / 100), 0);
                }

                // Insert into detail_nota_inap
                DB::table('detail_nota_inap')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_bayar' => $pay['nama_bayar'],
                    'besarppn' => $besarppn,
                    'besar_bayar' => $besar_bayar
                ]);

                // Jurnal Debet
                $addJurnal($akunBayar->kd_rek, $pay['nama_bayar'], $besar_bayar, 0);
            }

            // 2. Debets: Uang Muka / Deposit
            if ($uang_deposit > 0 && $setAkunRanap2 && !empty($setAkunRanap2->Uang_Muka_Ranap)) {
                $addJurnal($setAkunRanap2->Uang_Muka_Ranap, 'Kontra Akun Uang Muka Ranap', $uang_deposit, 0);
            }

            // 3. Debets: Piutang
            $nama_piutang = '';
            if ($piutang > 0) {
                $kd_rek_piutang = $request->kd_rek_piutang;
                if (empty($kd_rek_piutang) && $setAkunRanap2 && !empty($setAkunRanap2->Piutang_Pasien_Ranap)) {
                    $kd_rek_piutang = $setAkunRanap2->Piutang_Pasien_Ranap;
                }
                if (empty($kd_rek_piutang)) {
                    throw new \Exception('Akun piutang wajib dipilih karena ada sisa tagihan');
                }

                $akunPiutang = DB::table('akun_piutang')
                    ->where('kd_rek', $kd_rek_piutang)
                    ->first();

                $nama_piutang = $akunPiutang ? $akunPiutang->nama_bayar : 'Piutang Pasien Ranap';

                DB::table('detail_piutang_pasien')->insert([
                    'no_rawat' => $no_rawat,
                    'kd_rek' => $kd_rek_piutang,
                    'nama_bayar' => $nama_piutang,
                    'totalpiutang' => $piutang,
                    'uangmuka' => $total_bayar + $uang_deposit,
                    'sisapiutang' => $piutang,
                    'tgltempo' => $tgl_bayar
                ]);

                $addJurnal($kd_rek_piutang, $nama_piutang, $piutang, 0);
            }

            // 4. Debets: Potongan
            if ($potongan > 0) {
                $addJurnal($setAkunRanap->Potongan_Ranap, 'Potongan Ranap', $potongan, 0);
            }

            // 5. Credits: Kamar Inap
            if ($total_kamar > 0) {
                $addJurnal($setAkunRanap->Kamar_Inap, 'Pendapatan Kamar Inap', 0, $total_kamar);
            }

            // 6. Credits: Tindakan Ranap
            if ($total_tindakan > 0) {
                $addJurnal($setAkunRanap->Suspen_Piutang_Tindakan_Ranap, 'Suspen Piutang Tindakan Ranap', 0, $total_tindakan);
            }

            // 7. Credits: Laborat
            if ($total_lab > 0) {
                $addJurnal($setAkunRanap->Suspen_Piutang_Laborat_Ranap, 'Suspen Piutang Laborat Ranap', 0, $total_lab);
            }

            // 8. Credits: Radiologi
            if ($total_rad > 0) {
                $addJurnal($setAkunRanap->Suspen_Piutang_Radiologi_Ranap, 'Suspen Piutang Radiologi Ranap', 0, $total_rad);
            }

            // 9. Credits: Obat Ranap
            if ($total_obat > 0) {
                $addJurnal($setAkunRanap->Suspen_Piutang_Obat_Ranap, 'Suspen Piutang Obat Ranap', 0, $total_obat);
            }

            // 10. Credits: Registrasi
            if ($biaya_reg > 0) {
                $addJurnal($setAkunRanap->Registrasi_Ranap, 'Registrasi Ranap', 0, $biaya_reg);
            }

            // 11. Credits: Tambahan
            if ($tambahan > 0) {
                $addJurnal($setAkunRanap->Tambahan_Ranap, 'Tambahan Ranap', 0, $tambahan);
            }

            // 12. Credits: Sisa Uang Muka / Deposit (jika uang muka berlebih)
            if ($sisadeposit > 0 && $setAkunRanap2 && !empty($setAkunRanap2->Sisa_Uang_Muka_Ranap)) {
                $addJurnal($setAkunRanap2->Sisa_Uang_Muka_Ranap, 'Sisa Uang Muka Ranap', 0, $sisadeposit);
            }

            // Save Piutang & Tagihan Sadewa
            $pegawai = session()->get('pegawai');
            $petugas_nama = $pegawai ? $pegawai->nama : 'Kasir';
            $petugas_kode = $pegawai ? $pegawai->nik : 'Admin';
            $alamat = $reg->almt_pj ?? '-';

            if ($piutang > 0) {
                DB::table('piutang_pasien')->insert([
                    'no_rawat' => $no_rawat,
                    'tgl_piutang' => $tgl_bayar,
                    'no_rkm_medis' => $reg->no_rkm_medis,
                    'status' => 'Belum Lunas',
                    'totalpiutang' => $net_tagihan,
                    'uangmuka' => $total_bayar,
                    'sisapiutang' => $piutang,
                    'tgltempo' => $tgl_bayar
                ]);

                if ($total_bayar > 0) {
                    DB::table('tagihan_sadewa')->insert([
                        'no_nota' => $no_rawat,
                        'no_rkm_medis' => $reg->no_rkm_medis,
                        'nama_pasien' => $reg->nm_pasien,
                        'alamat' => $alamat,
                        'tgl_bayar' => $tgl_bayar . ' ' . $jam_bayar,
                        'jenis_bayar' => 'Uang Muka',
                        'jumlah_tagihan' => $net_tagihan,
                        'jumlah_bayar' => $total_bayar,
                        'status' => 'Belum',
                        'petugas' => $petugas_nama
                    ]);
                }
            } else {
                DB::table('tagihan_sadewa')->insert([
                    'no_nota' => $no_rawat,
                    'no_rkm_medis' => $reg->no_rkm_medis,
                    'nama_pasien' => $reg->nm_pasien,
                    'alamat' => $alamat,
                    'tgl_bayar' => $tgl_bayar . ' ' . $jam_bayar,
                    'jenis_bayar' => 'Pelunasan',
                    'jumlah_tagihan' => $net_tagihan,
                    'jumlah_bayar' => $net_tagihan,
                    'status' => 'Sudah',
                    'petugas' => $petugas_nama
                ]);
            }

            // Post Jurnal
            $no_jurnal = $this->generateNoJurnal();
            $jurnalKet = ($piutang > 0 ? 'PIUTANG' : 'PEMBAYARAN') . ' PASIEN RAWAT INAP ' . $no_rawat . ' ' . $reg->no_rkm_medis . ' ' . $reg->nm_pasien . ', DIPOSTING OLEH ' . $petugas_kode;

            DB::table('jurnal')->insert([
                'no_jurnal' => $no_jurnal,
                'no_bukti' => $no_rawat,
                'tgl_jurnal' => $tgl_bayar,
                'jam_jurnal' => $jam_bayar,
                'jenis' => 'U',
                'keterangan' => $jurnalKet
            ]);

            $insertDetails = [];
            foreach ($jurnalItems as $item) {
                $insertDetails[] = [
                    'no_jurnal' => $no_jurnal,
                    'kd_rek' => $item['kd_rek'],
                    'debet' => $item['debet'],
                    'kredit' => $item['kredit']
                ];
            }

            if (!empty($insertDetails)) {
                DB::table('detailjurnal')->insert($insertDetails);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Billing Rawat Inap berhasil disimpan dan ditutup',
                'no_nota' => $no_nota,
                'no_jurnal' => $no_jurnal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menutup billing ranap: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batalBillingRanap(Request $request)
    {
        $request->validate(['no_rawat' => 'required|string']);
        $no_rawat = $request->no_rawat;

        try {
            DB::beginTransaction();

            $nota = DB::table('nota_inap')->where('no_rawat', $no_rawat)->first();
            if (!$nota) {
                return response()->json(['status' => 'error', 'message' => 'Pasien belum memiliki nota billing rawat inap'], 400);
            }

            // Reversal Jurnals
            $existingJurnals = DB::table('jurnal')
                ->where('no_bukti', $no_rawat)
                ->where(function($query) {
                    $query->where('keterangan', 'like', 'PEMBAYARAN PASIEN RAWAT INAP%')
                          ->orWhere('keterangan', 'like', 'PIUTANG PASIEN RAWAT INAP%');
                })
                ->where('jenis', 'U')
                ->get();

            foreach ($existingJurnals as $oldJurnal) {
                DB::table('jurnal')
                    ->where('no_jurnal', $oldJurnal->no_jurnal)
                    ->update([
                        'keterangan' => $oldJurnal->keterangan . ' [DIBATALKAN]',
                        'jenis' => 'K'
                    ]);

                $oldDetails = DB::table('detailjurnal')
                    ->where('no_jurnal', $oldJurnal->no_jurnal)
                    ->get();

                if ($oldDetails->isNotEmpty()) {
                    $no_jurnal_reversal = $this->generateNoJurnal();
                    DB::table('jurnal')->insert([
                        'no_jurnal' => $no_jurnal_reversal,
                        'tgl_jurnal' => date('Y-m-d'),
                        'jam_jurnal' => date('H:i:s'),
                        'no_bukti' => $no_rawat,
                        'jenis' => 'K',
                        'keterangan' => 'PEMBALIKAN ' . $oldJurnal->keterangan
                    ]);

                    $reversalDetails = $oldDetails->map(function($d) use ($no_jurnal_reversal) {
                        return [
                            'no_jurnal' => $no_jurnal_reversal,
                            'kd_rek'    => $d->kd_rek,
                            'debet'     => $d->kredit,
                            'kredit'    => $d->debet,
                        ];
                    })->toArray();

                    DB::table('detailjurnal')->insert($reversalDetails);
                }
            }

            // Delete billing and nota records
            DB::table('nota_inap')->where('no_rawat', $no_rawat)->delete();
            DB::table('detail_nota_inap')->where('no_rawat', $no_rawat)->delete();
            DB::table('billing')->where('no_rawat', $no_rawat)->delete();
            DB::table('piutang_pasien')->where('no_rawat', $no_rawat)->delete();
            DB::table('tagihan_sadewa')->where('no_nota', $no_rawat)->delete();

            // Set back status_bayar to Belum Bayar
            DB::table('reg_periksa')->where('no_rawat', $no_rawat)->update(['status_bayar' => 'Belum Bayar']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Billing Rawat Inap berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membatalkan billing ranap: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateNoJurnal()
    {
        $date = date('Y-m-d');
        $date_formatted = date('Ymd');
        $count = DB::table('jurnal')->whereDate('tgl_jurnal', $date)->count();
        do {
            $count++;
            $no_jurnal = 'JR' . $date_formatted . str_pad($count, 6, '0', STR_PAD_LEFT);
        } while (DB::table('jurnal')->where('no_jurnal', $no_jurnal)->exists());
        return $no_jurnal;
    }
}
