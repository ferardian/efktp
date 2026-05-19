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
                $itemCount += count($cat['items']) + 1; // items + category header
            }
        }
        
        $baseHeight = ($size == '58') ? 350 : 400;
        $itemHeight = ($size == '58') ? 18 : 22;
        $height = $baseHeight + ($itemCount * $itemHeight);

        $width = ($size == '58') ? 164.4 : 226.7;

        $pdf = PDF::loadView('content.print.billing', [
            'data' => $billingData,
            'setting' => $setting,
            'size' => $size
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
            ->select('reg_periksa.biaya_reg', 'pasien.nm_pasien', 'reg_periksa.tgl_registrasi', 'reg_periksa.no_rkm_medis', 'poliklinik.nm_poli')
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
}
