<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class BillingController extends Controller
{
    public function getBillingRanap(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $data = $this->getBillingRanapData($no_rawat);
        return response()->json($data);
    }

    public function getBillingRalan(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $data = $this->getBillingRalanData($no_rawat);
        return response()->json($data);
    }

    public function printBilling(Request $request)
    {
        $no_rawat = $request->no_rawat;
        $size = $request->input('size', '80'); // '80' or '58'
        $show_obat = $request->input('show_obat', '1') !== '0';

        // Check if Ralan or Ranap
        $reg = DB::table('reg_periksa')
            ->where('no_rawat', $no_rawat)
            ->first();

        if (!$reg) {
            return abort(404, 'Data tidak ditemukan');
        }

        // Get matching data
        if ($reg->status_lanjut == 'Ranap') {
            $billingData = $this->getBillingRanapData($no_rawat);
            $billingData['type'] = 'RANAP';
        } else {
            $billingData = $this->getBillingRalanData($no_rawat);
            $billingData['type'] = 'RALAN';
        }

        $setting = Setting::first();

        // Convert mm to points (1mm = 2.83465 pt)
        // 58 mm width = 164.4 pt
        // 80 mm width = 226.7 pt
        // Let's compute a dynamic height based on the number of items, to prevent unnecessary blank pages
        $itemCount = 0;
        foreach ($billingData['categories'] as $cat) {
            if (count($cat['items']) > 0) {
                if ($cat['label'] === 'Obat & Alkes' && !$show_obat) {
                    $itemCount += 1; // only the category header
                } else {
                    $itemCount += count($cat['items']) + 1; // items + category header
                }
            }
        }
        
        $baseHeight = ($size == '58') ? 350 : 400;
        $itemHeight = ($size == '58') ? 18 : 22;
        $height = $baseHeight + ($itemCount * $itemHeight);

        $width = ($size == '58') ? 164.4 : 226.7;

        $pdf = PDF::loadView('content.print.billing', [
            'data' => $billingData,
            'setting' => $setting,
            'size' => $size,
            'show_obat' => $show_obat
        ])
        ->setPaper(array(0, 0, $width, $height))
        ->setOptions(['defaultFont' => 'Arial', 'isRemoteEnabled' => true]);

        return $pdf->stream('cetak billing.pdf');
    }

    private function getBillingRanapData($no_rawat)
    {
        // 1. Biaya Registrasi
        $reg = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('no_rawat', $no_rawat)
            ->select('reg_periksa.biaya_reg', 'pasien.nm_pasien', 'reg_periksa.tgl_registrasi', 'reg_periksa.no_rkm_medis')
            ->first();
        $biaya_reg = $reg ? $reg->biaya_reg : 0;

        // 2. Biaya Kamar
        $kamar_inap = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('no_rawat', $no_rawat)
            ->select('kamar_inap.*', 'kamar.trf_kamar', 'bangsal.nm_bangsal')
            ->get();

        $detail_kamar = [];
        $total_kamar = 0;
        foreach ($kamar_inap as $ki) {
            $tgl_masuk = Carbon::parse($ki->tgl_masuk);
            $tgl_keluar = ($ki->stts_pulang != 'Pindah Kamar' && ($ki->tgl_keluar == '0000-00-00' || !$ki->tgl_keluar)) 
                ? Carbon::now() 
                : Carbon::parse($ki->tgl_keluar);
            
            $durasi = $tgl_masuk->diffInDays($tgl_keluar);
            if ($durasi == 0) $durasi = 1;
            
            $subtotal = ($durasi * $ki->trf_kamar);
            $total_kamar += $subtotal;
            $detail_kamar[] = [
                'item' => "Sewa Kamar - {$ki->nm_bangsal} ({$ki->kd_kamar})",
                'tgl' => "{$ki->tgl_masuk} s.d {$ki->tgl_keluar}",
                'qty' => $durasi,
                'tarif' => $ki->trf_kamar,
                'subtotal' => $subtotal
            ];
        }

        // 3. Biaya Tindakan
        $tindakan_dr = DB::table('rawat_inap_dr')
            ->join('jns_perawatan_inap', 'rawat_inap_dr.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_inap.nm_perawatan', 'rawat_inap_dr.tgl_perawatan', 'rawat_inap_dr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $tindakan_pr = DB::table('rawat_inap_pr')
            ->join('jns_perawatan_inap', 'rawat_inap_pr.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_inap.nm_perawatan', 'rawat_inap_pr.tgl_perawatan', 'rawat_inap_pr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $tindakan_drpr = DB::table('rawat_inap_drpr')
            ->join('jns_perawatan_inap', 'rawat_inap_drpr.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_inap.nm_perawatan', 'rawat_inap_drpr.tgl_perawatan', 'rawat_inap_drpr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $detail_tindakan = $tindakan_dr->concat($tindakan_pr)->concat($tindakan_drpr);
        $total_tindakan = $detail_tindakan->sum('subtotal');

        // 4. Biaya Obat & Alkes
        $detail_obat = DB::table('detail_pemberian_obat')
            ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
            ->where('no_rawat', $no_rawat)
            ->select('databarang.nama_brng', 'detail_pemberian_obat.tgl_perawatan', 'detail_pemberian_obat.jml', 'detail_pemberian_obat.biaya_obat', 'detail_pemberian_obat.total')
            ->get()->map(fn($item) => ['item' => $item->nama_brng, 'tgl' => $item->tgl_perawatan, 'qty' => $item->jml, 'tarif' => $item->biaya_obat, 'subtotal' => $item->total]);
        $total_obat = $detail_obat->sum('subtotal');

        // 5. Biaya Laboratorium
        $detail_lab = DB::table('periksa_lab')
            ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_lab.nm_perawatan', 'periksa_lab.tgl_periksa', 'periksa_lab.biaya')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_periksa, 'qty' => 1, 'tarif' => $item->biaya, 'subtotal' => $item->biaya]);
        $total_lab = $detail_lab->sum('subtotal');

        // 6. Biaya Radiologi
        $detail_rad = DB::table('periksa_radiologi')
            ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_radiologi.nm_perawatan', 'periksa_radiologi.tgl_periksa', 'periksa_radiologi.biaya')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_periksa, 'qty' => 1, 'tarif' => $item->biaya, 'subtotal' => $item->biaya]);
        $total_rad = $detail_rad->sum('subtotal');

        $tambahan = DB::table('tambahan_biaya')->where('no_rawat', $no_rawat)->sum('besar_biaya');
        $potongan = DB::table('pengurangan_biaya')->where('no_rawat', $no_rawat)->sum('besar_pengurangan');

        $grand_total = ($biaya_reg + $total_kamar + $total_tindakan + $total_obat + $total_lab + $total_rad + $tambahan) - $potongan;

        // Header Info
        $first_kamar = $kamar_inap->first();
        $tgl_masuk_awal = $kamar_inap->min('tgl_masuk');
        $tgl_keluar_akhir = $kamar_inap->max('tgl_keluar');
        if ($tgl_keluar_akhir == '0000-00-00' || !$tgl_keluar_akhir) $tgl_keluar_akhir = Carbon::now()->format('Y-m-d');

        $total_hari = 0;
        foreach($kamar_inap as $ki) {
            $m = Carbon::parse($ki->tgl_masuk);
            $k = ($ki->tgl_keluar == '0000-00-00' || !$ki->tgl_keluar) ? Carbon::now() : Carbon::parse($ki->tgl_keluar);
            $d = $m->diffInDays($k);
            $total_hari += ($d == 0 ? 1 : $d);
        }

        return [
            'no_rawat' => $no_rawat,
            'no_rm' => $reg->no_rkm_medis ?? '-',
            'pasien' => $reg->nm_pasien ?? '-',
            'kamar' => $first_kamar ? "{$first_kamar->kd_kamar}, {$first_kamar->nm_bangsal}" : '-',
            'tgl_perawatan' => "{$tgl_masuk_awal} s.d {$tgl_keluar_akhir} ( {$total_hari} Hari )",
            'categories' => [
                ['label' => 'Registrasi', 'total' => $biaya_reg, 'items' => [['item' => 'Biaya Registrasi', 'qty' => 1, 'tarif' => $biaya_reg, 'subtotal' => $biaya_reg]]],
                ['label' => 'Kamar & Inap', 'total' => $total_kamar, 'items' => $detail_kamar],
                ['label' => 'Tindakan & Perawatan', 'total' => $total_tindakan, 'items' => $detail_tindakan],
                ['label' => 'Obat & Alkes', 'total' => $total_obat, 'items' => $detail_obat],
                ['label' => 'Laboratorium', 'total' => $total_lab, 'items' => $detail_lab],
                ['label' => 'Radiologi', 'total' => $total_rad, 'items' => $detail_rad],
                ['label' => 'Tambahan/Potongan', 'total' => $tambahan - $potongan, 'items' => [
                    ['item' => 'Tambahan Biaya', 'qty' => 1, 'tarif' => $tambahan, 'subtotal' => $tambahan],
                    ['item' => 'Potongan Biaya', 'qty' => 1, 'tarif' => -$potongan, 'subtotal' => -$potongan],
                ]],
            ],
            'grand_total' => $grand_total
        ];
    }

    private function getBillingRalanData($no_rawat)
    {
        // 1. Biaya Registrasi
        $reg = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->where('no_rawat', $no_rawat)
            ->select('reg_periksa.biaya_reg', 'pasien.nm_pasien', 'reg_periksa.tgl_registrasi', 'reg_periksa.no_rkm_medis', 'poliklinik.nm_poli', 'reg_periksa.status_bayar')
            ->first();
        $biaya_reg = $reg ? $reg->biaya_reg : 0;

        // 2. Biaya Tindakan
        $tindakan_dr = DB::table('rawat_jl_dr')
            ->join('jns_perawatan', 'rawat_jl_dr.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan.nm_perawatan', 'rawat_jl_dr.tgl_perawatan', 'rawat_jl_dr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $tindakan_pr = DB::table('rawat_jl_pr')
            ->join('jns_perawatan', 'rawat_jl_pr.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan.nm_perawatan', 'rawat_jl_pr.tgl_perawatan', 'rawat_jl_pr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $tindakan_drpr = DB::table('rawat_jl_drpr')
            ->join('jns_perawatan', 'rawat_jl_drpr.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan.nm_perawatan', 'rawat_jl_drpr.tgl_perawatan', 'rawat_jl_drpr.biaya_rawat')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_perawatan, 'qty' => 1, 'tarif' => $item->biaya_rawat, 'subtotal' => $item->biaya_rawat]);

        $detail_tindakan = $tindakan_dr->concat($tindakan_pr)->concat($tindakan_drpr);
        $total_tindakan = $detail_tindakan->sum('subtotal');

        // 3. Biaya Obat & Alkes
        $detail_obat = DB::table('detail_pemberian_obat')
            ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
            ->where('no_rawat', $no_rawat)
            ->select('databarang.nama_brng', 'detail_pemberian_obat.tgl_perawatan', 'detail_pemberian_obat.jml', 'detail_pemberian_obat.biaya_obat', 'detail_pemberian_obat.total')
            ->get()->map(fn($item) => ['item' => $item->nama_brng, 'tgl' => $item->tgl_perawatan, 'qty' => $item->jml, 'tarif' => $item->biaya_obat, 'subtotal' => $item->total]);
        $total_obat = $detail_obat->sum('subtotal');

        // 4. Biaya Laboratorium
        $detail_lab = DB::table('periksa_lab')
            ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_lab.nm_perawatan', 'periksa_lab.tgl_periksa', 'periksa_lab.biaya')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_periksa, 'qty' => 1, 'tarif' => $item->biaya, 'subtotal' => $item->biaya]);
        $total_lab = $detail_lab->sum('subtotal');

        // 5. Biaya Radiologi
        $detail_rad = DB::table('periksa_radiologi')
            ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)
            ->select('jns_perawatan_radiologi.nm_perawatan', 'periksa_radiologi.tgl_periksa', 'periksa_radiologi.biaya')
            ->get()->map(fn($item) => ['item' => $item->nm_perawatan, 'tgl' => $item->tgl_periksa, 'qty' => 1, 'tarif' => $item->biaya, 'subtotal' => $item->biaya]);
        $total_rad = $detail_rad->sum('subtotal');

        $tambahan = DB::table('tambahan_biaya')->where('no_rawat', $no_rawat)->sum('besar_biaya');
        $potongan = DB::table('pengurangan_biaya')->where('no_rawat', $no_rawat)->sum('besar_pengurangan');

        $grand_total = ($biaya_reg + $total_tindakan + $total_obat + $total_lab + $total_rad + $tambahan) - $potongan;

        return [
            'no_rawat' => $no_rawat,
            'no_rm' => $reg->no_rkm_medis ?? '-',
            'pasien' => $reg->nm_pasien ?? '-',
            'poli' => $reg->nm_poli ?? '-',
            'tgl_perawatan' => $reg ? $reg->tgl_registrasi : '-',
            'status_bayar' => $reg ? $reg->status_bayar : 'Belum Bayar',
            'categories' => [
                ['label' => 'Registrasi', 'total' => $biaya_reg, 'items' => [['item' => 'Biaya Registrasi', 'qty' => 1, 'tarif' => $biaya_reg, 'subtotal' => $biaya_reg]]],
                ['label' => 'Tindakan & Perawatan', 'total' => $total_tindakan, 'items' => $detail_tindakan],
                ['label' => 'Obat & Alkes', 'total' => $total_obat, 'items' => $detail_obat],
                ['label' => 'Laboratorium', 'total' => $total_lab, 'items' => $detail_lab],
                ['label' => 'Radiologi', 'total' => $total_rad, 'items' => $detail_rad],
                ['label' => 'Tambahan/Potongan', 'total' => $tambahan - $potongan, 'items' => [
                    ['item' => 'Tambahan Biaya', 'qty' => 1, 'tarif' => $tambahan, 'subtotal' => $tambahan],
                    ['item' => 'Potongan Biaya', 'qty' => 1, 'tarif' => -$potongan, 'subtotal' => -$potongan],
                ]],
            ],
            'grand_total' => $grand_total
        ];
    }

    public function getBillingAccounts(Request $request)
    {
        $request->validate(['no_rawat' => 'required']);
        $no_rawat = $request->no_rawat;

        $reg = DB::table('reg_periksa')
            ->where('no_rawat', $no_rawat)
            ->first();

        $kd_pj = $reg ? $reg->kd_pj : '-';

        $default_piutang = DB::table('akun_piutang')
            ->where('kd_pj', $kd_pj)
            ->first();

        $akun_bayar = DB::table('akun_bayar')->get();
        $akun_piutang = DB::table('akun_piutang')->get();

        return response()->json([
            'akun_bayar' => $akun_bayar,
            'akun_piutang' => $akun_piutang,
            'default_piutang' => $default_piutang,
            'kd_pj' => $kd_pj
        ]);
    }

    public function closeBilling(Request $request)
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
                ->select('reg_periksa.biaya_reg', 'pasien.nm_pasien', 'reg_periksa.no_rkm_medis', 'reg_periksa.almt_pj')
                ->first();

            if (!$reg) {
                throw new \Exception('Registrasi pasien tidak ditemukan');
            }

            $biaya_reg = floatval($reg->biaya_reg);

            // Calculate Tindakan
            $tindakan_dr = DB::table('rawat_jl_dr')->where('no_rawat', $no_rawat)->sum('biaya_rawat');
            $tindakan_pr = DB::table('rawat_jl_pr')->where('no_rawat', $no_rawat)->sum('biaya_rawat');
            $tindakan_drpr = DB::table('rawat_jl_drpr')->where('no_rawat', $no_rawat)->sum('biaya_rawat');
            $total_tindakan = floatval($tindakan_dr) + floatval($tindakan_pr) + floatval($tindakan_drpr);

            // Calculate Laborat
            $total_lab = floatval(DB::table('periksa_lab')->where('no_rawat', $no_rawat)->sum('biaya'));

            // Calculate Radiologi
            $total_rad = floatval(DB::table('periksa_radiologi')->where('no_rawat', $no_rawat)->sum('biaya'));

            // Calculate Obat
            $total_obat = floatval(DB::table('detail_pemberian_obat')->where('no_rawat', $no_rawat)->sum('total'));

            $potongan = floatval($request->potongan ?? 0);
            $tambahan = floatval($request->tambahan ?? 0);

            // Update tambahan_biaya
            DB::table('tambahan_biaya')->where('no_rawat', $no_rawat)->delete();
            if ($tambahan > 0) {
                DB::table('tambahan_biaya')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_biaya' => 'Tambahan Biaya',
                    'besar_biaya' => $tambahan
                ]);
            }

            // Update pengurangan_biaya
            DB::table('pengurangan_biaya')->where('no_rawat', $no_rawat)->delete();
            if ($potongan > 0) {
                DB::table('pengurangan_biaya')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_pengurangan' => 'Potongan Biaya',
                    'besar_pengurangan' => $potongan
                ]);
            }

            $grand_total = ($biaya_reg + $total_tindakan + $total_obat + $total_lab + $total_rad + $tambahan) - $potongan;

            $total_bayar = 0;
            foreach ($request->payments as $pay) {
                $total_bayar += floatval($pay['besar_bayar'] ?? 0);
            }

            $piutang = $grand_total - $total_bayar;

            // Generate No Nota
            $date_prefix = str_replace('-', '/', $tgl_bayar) . '/RJ';
            $max_nota = DB::table('nota_jalan')
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

            // Update status_bayar in reg_periksa to 'Sudah Bayar'
            DB::table('reg_periksa')
                ->where('no_rawat', $no_rawat)
                ->update(['status_bayar' => 'Sudah Bayar']);

            // Clean up previous billing records for this rawat
            DB::table('nota_jalan')->where('no_rawat', $no_rawat)->delete();
            DB::table('detail_nota_jalan')->where('no_rawat', $no_rawat)->delete();
            DB::table('piutang_pasien')->where('no_rawat', $no_rawat)->delete();
            DB::table('tagihan_sadewa')->where('no_nota', $no_rawat)->delete();
            
            // AUDIT TRAIL: Check if there is a previous billing journal for this no_rawat
            // If yes, create REVERSAL journals instead of deleting them (immutable audit trail)
            $existingJurnals = DB::table('jurnal')
                ->where('no_bukti', $no_rawat)
                ->where(function($query) {
                    $query->where('keterangan', 'like', 'PEMBAYARAN PASIEN RAWAT JALAN%')
                          ->orWhere('keterangan', 'like', 'PIUTANG PASIEN RAWAT JALAN%');
                })
                ->where('jenis', 'U') // only active billing journals (not already reversed)
                ->get();

            if ($existingJurnals->isNotEmpty()) {
                foreach ($existingJurnals as $oldJurnal) {
                    // 1. Mark the original journal as BATAL
                    DB::table('jurnal')
                        ->where('no_jurnal', $oldJurnal->no_jurnal)
                        ->update([
                            'keterangan' => $oldJurnal->keterangan . ' [DIBATALKAN]',
                            'jenis' => 'K' // K = Koreksi/Batal, U = Umum aktif
                        ]);

                    // 2. Create REVERSAL journal (flip debit & kredit of old detail)
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
                            'jenis' => 'K', // K = Koreksi/Reversal
                            'keterangan' => 'PEMBALIKAN ' . $oldJurnal->keterangan
                        ]);

                        $reversalDetails = $oldDetails->map(function($d) use ($no_jurnal_reversal) {
                            return [
                                'no_jurnal' => $no_jurnal_reversal,
                                'kd_rek'    => $d->kd_rek,
                                'debet'     => $d->kredit, // swap: old kredit becomes debet
                                'kredit'    => $d->debet,  // swap: old debet becomes kredit
                            ];
                        })->toArray();

                        DB::table('detailjurnal')->insert($reversalDetails);
                    }
                }
            }

            // Insert into nota_jalan
            DB::table('nota_jalan')->insert([
                'no_rawat' => $no_rawat,
                'no_nota'  => $no_nota,
                'tanggal'  => $tgl_bayar,
                'jam'      => $jam_bayar
            ]);

            // Rebuild tabel `billing` — Khanza mengecek: SELECT count(*) FROM billing WHERE no_rawat = ?
            // Jika count > 0, Khanza masuk mode "sudah bayar" (form terkunci).
            DB::table('billing')->where('no_rawat', $no_rawat)->delete();

            $billingRows = [];
            $noIdx = 0;

            // Baris: Registrasi
            if ($biaya_reg > 0) {
                $billingRows[] = [
                    'noindex'    => $noIdx++,
                    'no_rawat'   => $no_rawat,
                    'tgl_byr'  => $tgl_bayar,
                    'no'         => 'Registrasi',
                    'nm_perawatan' => 'Biaya Registrasi',
                    'pemisah'    => ':',
                    'biaya'      => $biaya_reg,
                    'jumlah'     => 1,
                    'tambahan'   => 0,
                    'totalbiaya' => $biaya_reg,
                    'status'     => 'Registrasi',
                ];
            }

            // Baris: Tindakan (dari 3 tabel: dokter, paramedis, dokter+paramedis)
            $tindakan_sources = [
                ['table' => 'rawat_jl_dr',   'alias' => 'dr'],
                ['table' => 'rawat_jl_pr',   'alias' => 'pr'],
                ['table' => 'rawat_jl_drpr', 'alias' => 'drpr'],
            ];

            foreach ($tindakan_sources as $src) {
                $rows = DB::table($src['table'])
                    ->join('jns_perawatan', $src['table'] . '.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
                    ->where($src['table'] . '.no_rawat', $no_rawat)
                    ->select('jns_perawatan.nm_perawatan', $src['table'] . '.biaya_rawat')
                    ->get();

                foreach ($rows as $tr) {
                    $billingRows[] = [
                        'noindex'      => $noIdx++,
                        'no_rawat'     => $no_rawat,
                        'tgl_byr'    => $tgl_bayar,
                        'no'           => 'Tindakan',
                        'nm_perawatan' => $tr->nm_perawatan,
                        'pemisah'      => ':',
                        'biaya'        => $tr->biaya_rawat,
                        'jumlah'       => 1,
                        'tambahan'     => 0,
                        'totalbiaya'   => $tr->biaya_rawat,
                        'status'       => 'Ralan Dokter',
                    ];
                }
            }

            // Baris: Obat
            $obat_rows = DB::table('detail_pemberian_obat')
                ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
                ->where('detail_pemberian_obat.no_rawat', $no_rawat)
                ->select('databarang.nama_brng', 'detail_pemberian_obat.biaya_obat', 'detail_pemberian_obat.jml', 'detail_pemberian_obat.total')
                ->get();

            foreach ($obat_rows as $or) {
                $billingRows[] = [
                    'noindex'      => $noIdx++,
                    'no_rawat'     => $no_rawat,
                    'tgl_byr'    => $tgl_bayar,
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

            if (!empty($billingRows)) {
                DB::table('billing')->insert($billingRows);
            }

            $setAkunRalan = DB::table('set_akun_ralan')->first();
            if (!$setAkunRalan) {
                throw new \Exception('Mapping akun ralan belum terkonfigurasi (set_akun_ralan kosong)');
            }

            // Setup Journal Items aggregation
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

                // Insert into detail_nota_jalan
                DB::table('detail_nota_jalan')->insert([
                    'no_rawat' => $no_rawat,
                    'nama_bayar' => $pay['nama_bayar'],
                    'besarppn' => $besarppn,
                    'besar_bayar' => $besar_bayar
                ]);

                // Add to Jurnal Debet
                $addJurnal($akunBayar->kd_rek, $pay['nama_bayar'], $besar_bayar, 0);
            }

            // 2. Debets: Piutang
            $nama_piutang = '';
            if ($piutang > 0) {
                $kd_rek_piutang = $request->kd_rek_piutang;
                if (empty($kd_rek_piutang)) {
                    throw new \Exception('Akun piutang wajib dipilih karena ada sisa tagihan');
                }

                $akunPiutang = DB::table('akun_piutang')
                    ->where('kd_rek', $kd_rek_piutang)
                    ->first();

                $nama_piutang = $akunPiutang ? $akunPiutang->nama_bayar : 'Piutang Pasien';

                // Save to detail_piutang_pasien
                DB::table('detail_piutang_pasien')->insert([
                    'no_rawat' => $no_rawat,
                    'kd_rek' => $kd_rek_piutang,
                    'nama_bayar' => $nama_piutang,
                    'totalpiutang' => $piutang,
                    'uangmuka' => $total_bayar,
                    'sisapiutang' => $piutang,
                    'tgltempo' => $tgl_bayar
                ]);

                // Add to Jurnal Debet
                $addJurnal($kd_rek_piutang, $nama_piutang, $piutang, 0);
            }

            // 3. Debets: Potongan
            if ($potongan > 0) {
                $addJurnal($setAkunRalan->Potongan_Ralan, 'Potongan Ralan', $potongan, 0);
            }

            // 4. Credits: Tindakan (via Suspen Piutang Tindakan)
            if ($total_tindakan > 0) {
                $addJurnal($setAkunRalan->Suspen_Piutang_Tindakan_Ralan, 'Suspen Piutang Tindakan Ralan', 0, $total_tindakan);
            }

            // 5. Credits: Laborat (via Suspen Piutang Laborat)
            if ($total_lab > 0) {
                $addJurnal($setAkunRalan->Suspen_Piutang_Laborat_Ralan, 'Suspen Piutang Laborat Ralan', 0, $total_lab);
            }

            // 6. Credits: Radiologi (via Suspen Piutang Radiologi)
            if ($total_rad > 0) {
                $addJurnal($setAkunRalan->Suspen_Piutang_Radiologi_Ralan, 'Suspen Piutang Radiologi Ralan', 0, $total_rad);
            }

            // 7. Credits: Obat (via Suspen Piutang Obat)
            if ($total_obat > 0) {
                $addJurnal($setAkunRalan->Suspen_Piutang_Obat_Ralan, 'Suspen Piutang Obat Ralan', 0, $total_obat);
            }

            // 8. Credits: Registrasi (langsung ke pendapatan — tidak ada suspen untuk registrasi)
            if ($biaya_reg > 0) {
                $addJurnal($setAkunRalan->Registrasi_Ralan, 'Registrasi Ralan', 0, $biaya_reg);
            }

            // 9. Credits: Tambahan (langsung ke pendapatan — tidak ada suspen untuk tambahan)
            if ($tambahan > 0) {
                $addJurnal($setAkunRalan->Tambahan_Ralan, 'Tambahan Ralan', 0, $tambahan);
            }

            // Save Piutang Pasien & Tagihan Sadewa
            $pegawai = session()->get('pegawai');
            $petugas_nama = $pegawai ? $pegawai->nama : 'Admin';
            $alamat = $reg->almt_pj ?? '-';

            if ($piutang > 0) {
                // Save to piutang_pasien
                DB::table('piutang_pasien')->insert([
                    'no_rawat' => $no_rawat,
                    'tgl_piutang' => $tgl_bayar,
                    'no_rkm_medis' => $reg->no_rkm_medis,
                    'status' => 'Belum Lunas',
                    'totalpiutang' => $grand_total,
                    'uangmuka' => $total_bayar,
                    'sisapiutang' => $piutang,
                    'tgltempo' => $tgl_bayar
                ]);

                // Save to tagihan_sadewa (Uang Muka)
                DB::table('tagihan_sadewa')->insert([
                    'no_nota' => $no_rawat,
                    'no_rkm_medis' => $reg->no_rkm_medis,
                    'nama_pasien' => $reg->nm_pasien,
                    'alamat' => $alamat,
                    'tgl_bayar' => $tgl_bayar . ' ' . $jam_bayar,
                    'jenis_bayar' => 'Uang Muka',
                    'jumlah_tagihan' => $grand_total,
                    'jumlah_bayar' => $total_bayar,
                    'status' => 'Belum',
                    'petugas' => $petugas_nama
                ]);
            } else {
                // Save to tagihan_sadewa (Pelunasan)
                DB::table('tagihan_sadewa')->insert([
                    'no_nota' => $no_rawat,
                    'no_rkm_medis' => $reg->no_rkm_medis,
                    'nama_pasien' => $reg->nm_pasien,
                    'alamat' => $alamat,
                    'tgl_bayar' => $tgl_bayar . ' ' . $jam_bayar,
                    'jenis_bayar' => 'Pelunasan',
                    'jumlah_tagihan' => $grand_total,
                    'jumlah_bayar' => $grand_total,
                    'status' => 'Sudah',
                    'petugas' => $petugas_nama
                ]);
            }

            // Write Jurnal
            $no_jurnal = $this->generateNoJurnal();
            $jurnal_desc = ($piutang > 0 ? 'PIUTANG' : 'PEMBAYARAN') . ' PASIEN RAWAT JALAN ' . $no_rawat . ' ' . $reg->no_rkm_medis . ' ' . $reg->nm_pasien . ', DIPOSTING OLEH ' . $petugas_nama;

            DB::table('jurnal')->insert([
                'no_jurnal' => $no_jurnal,
                'tgl_jurnal' => $tgl_bayar,
                'jam_jurnal' => $jam_bayar,
                'no_bukti' => $no_rawat,
                'jenis' => 'U',
                'keterangan' => $jurnal_desc
            ]);

            // Save Jurnal details
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
                'message' => 'Billing Rawat Jalan berhasil disimpan dan ditutup',
                'no_nota' => $no_nota,
                'no_jurnal' => $no_jurnal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menutup billing: ' . $e->getMessage()
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
