<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\PeriksaLab;
use App\Models\RegPeriksa;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriksaLabController extends Controller
{
    protected $periksa;

    public function __construct()
    {
        $this->periksa = new PeriksaLab();
    }

    public function index()
    {
        return view('content.laboratorium.periksa');
    }

    public function listDataTable(Request $request): JsonResponse
    {
        $filter = $request->input('dataTable', []);
        $tglRange = $filter['tgl_periksa'] ?? [date('Y-m-d'), date('Y-m-d')];
        $tglAwal = !empty($tglRange[0]) ? date('Y-m-d', strtotime($tglRange[0])) : date('Y-m-d');
        $tglAkhir = !empty($tglRange[1]) ? date('Y-m-d', strtotime($tglRange[1])) : $tglAwal;
        $status = $filter['status'] ?? '';

        $query = DB::table('periksa_lab as pl')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'pl.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('jns_perawatan_lab as jpl', 'jpl.kd_jenis_prw', '=', 'pl.kd_jenis_prw')
            ->leftJoin('poliklinik as pol', 'pol.kd_poli', '=', 'rp.kd_poli')
            ->leftJoin('penjab as pj', 'pj.kd_pj', '=', 'rp.kd_pj')
            ->leftJoin('petugas as pt', 'pt.nip', '=', 'pl.nip')
            ->leftJoin('dokter as dpj', 'dpj.kd_dokter', '=', 'pl.kd_dokter')
            ->leftJoin('dokter as dpr', 'dpr.kd_dokter', '=', 'pl.dokter_perujuk')
            ->whereBetween('pl.tgl_periksa', [$tglAwal, $tglAkhir]);

        if (!empty($status)) {
            $query->where('pl.status', ucfirst(strtolower($status)));
        }

        $rows = $query->select(
            'pl.no_rawat',
            'pl.tgl_periksa',
            'pl.jam',
            'pl.status',
            'pl.biaya',
            'pl.kd_jenis_prw',
            'jpl.nm_perawatan',
            'rp.no_rkm_medis',
            'rp.umurdaftar',
            'rp.sttsumur',
            'p.nm_pasien',
            'p.jk',
            'pol.nm_poli',
            'pj.png_jawab',
            'pt.nama as nm_petugas',
            'dpj.nm_dokter as nm_dokter_pj',
            'dpr.nm_dokter as nm_dokter_perujuk'
        )
            ->orderByDesc('pl.tgl_periksa')
            ->orderByDesc('pl.jam')
            ->get();

        // Lookup matching noorder if available
        $noRawatList = $rows->pluck('no_rawat')->unique()->values()->all();
        $orderMap = [];
        if (!empty($noRawatList)) {
            $orders = DB::table('permintaan_lab')
                ->whereIn('no_rawat', $noRawatList)
                ->select('no_rawat', 'tgl_hasil', 'noorder')
                ->get();
            foreach ($orders as $ord) {
                $orderMap[$ord->no_rawat . '|' . $ord->tgl_hasil] = $ord->noorder;
                if (!isset($orderMap[$ord->no_rawat])) {
                    $orderMap[$ord->no_rawat] = $ord->noorder;
                }
            }
        }

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row->no_rawat . '|' . $row->tgl_periksa . '|' . $row->jam;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'no_rawat' => $row->no_rawat,
                    'no_rkm_medis' => $row->no_rkm_medis,
                    'nm_pasien' => $row->nm_pasien,
                    'jk' => $row->jk,
                    'umurdaftar' => $row->umurdaftar,
                    'sttsumur' => $row->sttsumur,
                    'nm_poli' => $row->nm_poli ?: '-',
                    'png_jawab' => $row->png_jawab ?: '-',
                    'tgl_periksa' => $row->tgl_periksa,
                    'jam' => $row->jam,
                    'status' => $row->status,
                    'nm_petugas' => $row->nm_petugas ?: '-',
                    'nm_dokter_pj' => $row->nm_dokter_pj ?: '-',
                    'nm_dokter_perujuk' => $row->nm_dokter_perujuk ?: '-',
                    'noorder' => $orderMap[$row->no_rawat . '|' . $row->tgl_periksa] ?? ($orderMap[$row->no_rawat] ?? null),
                    'biaya_total' => 0,
                    'paket_list' => [],
                ];
            }
            $grouped[$key]['biaya_total'] += (float) $row->biaya;
            $grouped[$key]['paket_list'][] = $row->nm_perawatan;
        }

        return response()->json(['data' => array_values($grouped)]);
    }

    public function get(Request $request)
    {

        $clause = [
            'no_rawat' => $request->no_rawat,
        ];

        $request->has('tgl') ?  $clause['tgl_periksa'] = date('Y-m-d', strtotime($request->tgl)) : null;
        $request->has('kode') ?  $clause['kd_jenis_prw'] = $request->kode : null;

        $periksa = $this->periksa->where($clause);

        $periksa = $periksa->select('no_rawat', 'nip', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'kd_dokter', 'dokter_perujuk', 'status')
            ->with([
                'pegawai',
                'jenis' => function ($q) {
                    return $q->select(['nm_perawatan', 'kd_jenis_prw']);
                },
                'detail' => function ($q) {
                    return $q->select(['no_rawat', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'id_template', 'nilai', 'nilai_rujukan', 'keterangan'])
                        ->with('template', function ($q) {
                            return $q->select(['kd_jenis_prw', 'id_template', 'Pemeriksaan as nama', 'satuan']);
                        });
                }
            ])->get();

        return $this->success(['count' => count($periksa), 'result' => $periksa]);
    }

    public function cetakHasilPeriksa(Request $request)
    {
        $noRawat = $request->query('no_rawat');
        $tglPeriksa = $request->query('tgl_periksa');
        $jam = $request->query('jam');

        abort_if(!$noRawat || !$tglPeriksa, 404);

        $reg = RegPeriksa::where('no_rawat', $noRawat)
            ->with(['pasien', 'poliklinik', 'penjab'])
            ->firstOrFail();

        $periksaQuery = DB::table('periksa_lab')
            ->join('dokter', 'periksa_lab.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('petugas', 'periksa_lab.nip', '=', 'petugas.nip')
            ->leftJoin('dokter as perujuk', 'periksa_lab.dokter_perujuk', '=', 'perujuk.kd_dokter')
            ->where('periksa_lab.no_rawat', $noRawat)
            ->where('periksa_lab.tgl_periksa', $tglPeriksa);

        if ($jam) {
            $periksaQuery->where('periksa_lab.jam', $jam);
        }

        $periksa = $periksaQuery->select(
            'dokter.nm_dokter',
            'dokter.no_ijn_praktek',
            'petugas.nama as nm_petugas',
            'perujuk.nm_dokter as nm_dokter_perujuk',
            'periksa_lab.tgl_periksa',
            'periksa_lab.jam'
        )->first();

        $permintaanExist = DB::table('permintaan_lab')
            ->where('no_rawat', $noRawat)
            ->where('tgl_hasil', $tglPeriksa)
            ->first();

        $detailQuery = DB::table('detail_periksa_lab')
            ->join('jns_perawatan_lab', 'detail_periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->join('template_laboratorium', 'detail_periksa_lab.id_template', '=', 'template_laboratorium.id_template')
            ->where('detail_periksa_lab.no_rawat', $noRawat)
            ->where('detail_periksa_lab.tgl_periksa', $tglPeriksa);

        if ($jam) {
            $detailQuery->where('detail_periksa_lab.jam', $jam);
        }

        $detailRaw = $detailQuery->select(
            'jns_perawatan_lab.nm_perawatan as paket',
            'template_laboratorium.Pemeriksaan as item_nama',
            'template_laboratorium.satuan',
            'detail_periksa_lab.nilai',
            'detail_periksa_lab.nilai_rujukan',
            'detail_periksa_lab.keterangan',
            'template_laboratorium.urut'
        )
            ->orderBy('jns_perawatan_lab.kd_jenis_prw')
            ->orderBy('template_laboratorium.urut')
            ->get();

        $grouped = [];
        foreach ($detailRaw as $row) {
            $grouped[$row->paket][] = $row;
        }

        $permintaan = (object) [
            'noorder'        => $permintaanExist->noorder ?? '-',
            'no_rawat'       => $noRawat,
            'tgl_permintaan' => $permintaanExist->tgl_permintaan ?? $tglPeriksa,
            'jam_permintaan' => $permintaanExist->jam_permintaan ?? ($periksa->jam ?? $jam ?? ''),
            'tgl_sampel'     => $permintaanExist->tgl_sampel ?? $tglPeriksa,
            'jam_sampel'     => $permintaanExist->jam_sampel ?? ($periksa->jam ?? $jam ?? ''),
            'tgl_hasil'      => $permintaanExist->tgl_hasil ?? $tglPeriksa,
            'jam_hasil'      => $permintaanExist->jam_hasil ?? ($periksa->jam ?? $jam ?? ''),
            'registrasi'     => $reg,
            'pasien'         => $reg->pasien,
            'poliklinik'     => $reg->poliklinik,
            'penjab'         => $reg->penjab,
            'perujuk'        => (object) ['nm_dokter' => $periksa->nm_dokter_perujuk ?? '-'],
        ];

        $setting = Setting::first()
            ?? (object) ['nama_instansi' => config('app.name'), 'alamat_instansi' => '-', 'kabupaten' => '', 'propinsi' => '', 'kontak' => '-', 'email' => '-', 'logo' => null];

        $pdf = Pdf::loadView('content.print.hasilLaboratorium', [
            'permintaan' => $permintaan,
            'periksa'    => $periksa,
            'grouped'    => $grouped,
            'setting'    => $setting,
        ])->setPaper('a4', 'portrait')
          ->setOptions(['defaultFont' => 'Arial', 'isRemoteEnabled' => true, 'isPhpEnabled' => true]);

        return $pdf->stream("HasilLab-{$noRawat}.pdf");
    }
}
