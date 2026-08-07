<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Permintaan\PermintaanLab;
use App\Traits\ResponseHandlerTrait;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LabProcessController extends Controller
{
    use Track, ResponseHandlerTrait;

    /**
     * Update Tanggal dan Jam Pengambilan Sampel Lab
     */
    public function updateSampel(Request $request): JsonResponse
    {
        $request->validate([
            'noorder'    => 'required|string',
            'tgl_sampel' => 'required|date_format:Y-m-d',
            'jam_sampel' => 'required',
        ]);

        try {
            $affected = DB::table('permintaan_lab')
                ->where('noorder', $request->noorder)
                ->update([
                    'tgl_sampel' => $request->tgl_sampel,
                    'jam_sampel' => $request->jam_sampel,
                ]);

            if ($affected === 0 && !DB::table('permintaan_lab')->where('noorder', $request->noorder)->exists()) {
                return response()->json(['message' => 'Data permintaan lab tidak ditemukan'], 404);
            }

            $permintaan = PermintaanLab::where('noorder', $request->noorder)->first();

            $this->updateSql($permintaan, [
                'tgl_sampel' => $request->tgl_sampel,
                'jam_sampel' => $request->jam_sampel,
            ], ['noorder' => $request->noorder]);

            return response()->json([
                'status'  => true,
                'message' => 'Pengambilan sampel berhasil disimpan',
                'data'    => $permintaan,
            ]);
        } catch (\Throwable $e) {
            Log::error('[LAB PROCESS SAMPEL ERROR] ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get detail order lab & template items for result entry form
     */
    public function getFormHasil(string $noorder): JsonResponse
    {
        $permintaan = PermintaanLab::where('noorder', $noorder)
            ->with(['pasien', 'registrasi', 'poliklinik', 'perujuk', 'penjab'])
            ->first();

        if (!$permintaan) {
            return response()->json(['message' => 'Permintaan lab tidak ditemukan'], 404);
        }

        // Fetch detail items requested for this order
        $details = DB::table('permintaan_detail_permintaan_lab')
            ->where('noorder', $noorder)
            ->join('jns_perawatan_lab', 'permintaan_detail_permintaan_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->join('template_laboratorium', 'permintaan_detail_permintaan_lab.id_template', '=', 'template_laboratorium.id_template')
            ->select(
                'permintaan_detail_permintaan_lab.kd_jenis_prw',
                'jns_perawatan_lab.nm_perawatan',
                'permintaan_detail_permintaan_lab.id_template',
                'template_laboratorium.Pemeriksaan as item_nama',
                'template_laboratorium.satuan',
                'template_laboratorium.nilai_rujukan_la as la',
                'template_laboratorium.nilai_rujukan_pa as pa',
                'template_laboratorium.nilai_rujukan_ld as ld',
                'template_laboratorium.nilai_rujukan_pd as pd',
                'template_laboratorium.urut'
            )
            ->orderBy('jns_perawatan_lab.kd_jenis_prw')
            ->orderBy('template_laboratorium.urut')
            ->get();

        // Calculate age and gender for reference range helper
        $jk = $permintaan->pasien->jk ?? 'L';
        $umur = (int) ($permintaan->registrasi->umurdaftar ?? 20);

        $formattedDetails = $details->map(function ($item) use ($jk, $umur, $permintaan) {
            $rujukan = '';
            if ($jk === 'L') {
                $rujukan = ($umur < 12) ? $item->la : $item->ld;
            } else {
                $rujukan = ($umur < 12) ? $item->pa : $item->pd;
            }
            if (empty($rujukan)) $rujukan = '-';

            // Check if already filled in detail_periksa_lab
            $existing = DB::table('detail_periksa_lab')
                ->where('no_rawat', $permintaan->no_rawat)
                ->where('id_template', $item->id_template)
                ->first();

            $item->nilai_rujukan = trim($rujukan . ' ' . $item->satuan);
            $item->nilai_existing = $existing->nilai ?? '';
            $item->keterangan_existing = $existing->keterangan ?? '';
            return $item;
        });

        // Fetch doctors and lab officers for select options
        $dokters = DB::table('dokter')
            ->where('status', '1')
            ->select('kd_dokter', 'nm_dokter')
            ->orderBy('nm_dokter')
            ->get();

        $petugas = DB::table('petugas')
            ->where('status', '1')
            ->select('nip', 'nama')
            ->orderBy('nama')
            ->get();

        return response()->json([
            'status'     => true,
            'permintaan' => $permintaan,
            'details'    => $formattedDetails,
            'dokters'    => $dokters,
            'petugas'    => $petugas,
        ]);
    }

    /**
     * Simpan Hasil Pemeriksaan Lab, Insert periksa_lab, detail_periksa_lab & Generate Jurnal Accounting
     */
    public function simpanHasil(Request $request): JsonResponse
    {
        $request->validate([
            'noorder'      => 'required|string',
            'no_rawat'     => 'required|string',
            'tgl_hasil'    => 'required|date_format:Y-m-d',
            'jam_hasil'    => 'required',
            'nip'          => 'required|string',
            'kd_dokter'    => 'required|string',
            'detail_hasil' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $noorder = $request->noorder;
            $noRawat = $request->no_rawat;
            $tglHasil = $request->tgl_hasil;
            $jamHasil = $request->jam_hasil;
            $nip = $request->nip;
            $kdDokter = $request->kd_dokter;

            // 1. Update permintaan_lab
            DB::table('permintaan_lab')
                ->where('noorder', $noorder)
                ->update([
                    'tgl_hasil' => $tglHasil,
                    'jam_hasil' => $jamHasil,
                ]);

            // 2. Fetch reg_periksa & penjab
            $reg = DB::table('reg_periksa')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->where('reg_periksa.no_rawat', $noRawat)
                ->select('reg_periksa.*', 'pasien.nm_pasien', 'pasien.jk')
                ->first();

            if (!$reg) {
                throw new \Exception("Data registrasi no_rawat {$noRawat} tidak ditemukan.");
            }

            $kdPj = $reg->kd_pj ?? 'BPJ';
            $statusLanjut = $reg->status_lanjut ?? 'Ralan';

            // Group detail results by kd_jenis_prw
            $groupedResults = [];
            foreach ($request->detail_hasil as $resItem) {
                $kdPrw = $resItem['kd_jenis_prw'];
                if (!isset($groupedResults[$kdPrw])) {
                    $groupedResults[$kdPrw] = [];
                }
                $groupedResults[$kdPrw][] = $resItem;
            }

            $ttlpendapatan = 0;
            $ttljmdokter   = 0;
            $ttljmpetugas  = 0;
            $ttlbhp        = 0;
            $ttlkso        = 0;
            $ttljasasarana = 0;
            $ttljmperujuk  = 0;
            $ttlmenejemen  = 0;

            // 3. Process periksa_lab and detail_periksa_lab
            foreach ($groupedResults as $kdJenisPrw => $templateResults) {
                $jnsTarif = DB::table('jns_perawatan_lab')
                    ->where('kd_jenis_prw', $kdJenisPrw)
                    ->first();

                if (!$jnsTarif) {
                    continue;
                }

                // Check or insert periksa_lab
                DB::table('periksa_lab')->updateOrInsert(
                    [
                        'no_rawat'     => $noRawat,
                        'kd_jenis_prw' => $kdJenisPrw,
                        'tgl_periksa'  => $tglHasil,
                    ],
                    [
                        'nip'                    => $nip,
                        'jam'                    => $jamHasil,
                        'dokter_perujuk'         => $kdDokter,
                        'bagian_rs'              => $jnsTarif->bagian_rs ?? 0,
                        'bhp'                    => $jnsTarif->bhp ?? 0,
                        'tarif_perujuk'          => $jnsTarif->tarif_perujuk ?? 0,
                        'tarif_tindakan_dokter'  => $jnsTarif->tarif_tindakan_dokter ?? 0,
                        'tarif_tindakan_petugas' => $jnsTarif->tarif_tindakan_petugas ?? 0,
                        'kso'                    => $jnsTarif->kso ?? 0,
                        'menejemen'              => $jnsTarif->menejemen ?? 0,
                        'biaya'                  => $jnsTarif->total_byr ?? $jnsTarif->biaya_item ?? 0,
                        'kd_dokter'              => $kdDokter,
                        'status'                 => $statusLanjut,
                        'kategori'               => 'PK',
                    ]
                );

                // Accumulate totals for financial journal
                $ttlpendapatan += (float) ($jnsTarif->total_byr ?? $jnsTarif->biaya_item ?? 0);
                $ttljmdokter   += (float) ($jnsTarif->tarif_tindakan_dokter ?? 0);
                $ttljmpetugas  += (float) ($jnsTarif->tarif_tindakan_petugas ?? 0);
                $ttlbhp        += (float) ($jnsTarif->bhp ?? 0);
                $ttlkso        += (float) ($jnsTarif->kso ?? 0);
                $ttljasasarana += (float) ($jnsTarif->bagian_rs ?? 0);
                $ttljmperujuk  += (float) ($jnsTarif->tarif_perujuk ?? 0);
                $ttlmenejemen  += (float) ($jnsTarif->menejemen ?? 0);

                // Insert detail_periksa_lab items
                foreach ($templateResults as $item) {
                    $tmpl = DB::table('template_laboratorium')
                        ->where('id_template', $item['id_template'])
                        ->first();

                    $nilaiRujukan = $item['nilai_rujukan'] ?? '';
                    if (empty($nilaiRujukan) && $tmpl) {
                        $jk = $reg->jk ?? 'L';
                        $umur = (int) ($reg->umurdaftar ?? 20);
                        $ruj = ($jk === 'L') ? (($umur < 12) ? $tmpl->nilai_rujukan_la : $tmpl->nilai_rujukan_ld) : (($umur < 12) ? $tmpl->nilai_rujukan_pa : $tmpl->nilai_rujukan_pd);
                        $nilaiRujukan = trim($ruj . ' ' . $tmpl->satuan);
                    }

                    DB::table('detail_periksa_lab')->updateOrInsert(
                        [
                            'no_rawat'     => $noRawat,
                            'kd_jenis_prw' => $kdJenisPrw,
                            'tgl_periksa'  => $tglHasil,
                            'id_template'  => $item['id_template'],
                        ],
                        [
                            'jam'                    => $jamHasil,
                            'nilai'                  => $item['nilai'] ?? '',
                            'nilai_rujukan'          => $nilaiRujukan,
                            'keterangan'             => $item['keterangan'] ?? '-',
                            'bagian_rs'              => $jnsTarif->bagian_rs ?? 0,
                            'bhp'                    => $jnsTarif->bhp ?? 0,
                            'bagian_perujuk'         => $jnsTarif->tarif_perujuk ?? 0,
                            'bagian_dokter'          => $jnsTarif->tarif_tindakan_dokter ?? 0,
                            'bagian_laborat'         => $jnsTarif->tarif_tindakan_petugas ?? 0,
                            'kso'                    => $jnsTarif->kso ?? 0,
                            'menejemen'              => $jnsTarif->menejemen ?? 0,
                            'biaya_item'             => $jnsTarif->total_byr ?? $jnsTarif->biaya_item ?? 0,
                        ]
                    );
                }
            }

            // 4. Generate Journal Accounting Entry
            $this->postJournalLaboratorium(
                $noRawat,
                $tglHasil,
                $jamHasil,
                $reg->nm_pasien,
                $reg->no_rkm_medis,
                $ttlpendapatan,
                $ttljmdokter,
                $ttljmpetugas,
                $ttlbhp,
                $ttlkso,
                $ttljasasarana,
                $ttljmperujuk,
                $ttlmenejemen
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Hasil pemeriksaan laboratorium & jurnal akuntansi berhasil disimpan.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[LAB PROCESS SIMPAN HASIL ERROR] ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            return response()->json(['message' => 'Gagal menyimpan hasil lab: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Post Journal entries for Laboratorium
     */
    private function postJournalLaboratorium(
        string $noRawat,
        string $tglHasil,
        string $jamHasil,
        string $nmPasien,
        string $noRkmMedis,
        float $ttlpendapatan,
        float $ttljmdokter,
        float $ttljmpetugas,
        float $ttlbhp,
        float $ttlkso,
        float $ttljasasarana,
        float $ttljmperujuk,
        float $ttlmenejemen
    ): void {
        $setAkun = DB::table('set_akun_ralan')->first();
        if (!$setAkun) {
            Log::warning("[LAB JOURNAL WARN] set_akun_ralan kosong, pengakuan jurnal dilewati.");
            return;
        }

        // Generate No. Jurnal: JR + Ymd + 6-digit counter
        $dateFormatted = date('Ymd', strtotime($tglHasil));
        $count = DB::table('jurnal')->whereDate('tgl_jurnal', $tglHasil)->count() + 1;
        $noJurnal = 'JR' . $dateFormatted . str_pad((string) $count, 6, '0', STR_PAD_LEFT);

        $petugasCode = session('pegawai.nik') ?? auth()->user()->id ?? 'SYSTEM';
        $jurnalDesc = "PEMERIKSAAN LABORAT RAWAT JALAN PASIEN {$noRawat} {$noRkmMedis} {$nmPasien}, DIPOSTING OLEH {$petugasCode}";

        DB::table('jurnal')->insert([
            'no_jurnal'  => $noJurnal,
            'tgl_jurnal' => $tglHasil,
            'jam_jurnal' => $jamHasil,
            'jenis'      => 'U',
            'keterangan' => $jurnalDesc,
        ]);

        $jurnalItems = [];
        $addJurnal = function ($kdRek, $debet, $kredit) use (&$jurnalItems) {
            if (empty($kdRek) || ($debet == 0 && $kredit == 0)) return;
            if (isset($jurnalItems[$kdRek])) {
                $jurnalItems[$kdRek]['debet'] += $debet;
                $jurnalItems[$kdRek]['kredit'] += $kredit;
            } else {
                $jurnalItems[$kdRek] = [
                    'debet'  => $debet,
                    'kredit' => $kredit,
                ];
            }
        };

        // 1. Suspen Piutang Laborat Ralan (Debet) & Laborat Ralan (Kredit Pendapatan)
        $addJurnal($setAkun->Suspen_Piutang_Laborat_Ralan ?? null, $ttlpendapatan, 0);
        $addJurnal($setAkun->Laborat_Ralan ?? null, 0, $ttlpendapatan);

        // 2. Jasa Dokter
        $addJurnal($setAkun->Beban_Jasa_Medik_Dokter_Laborat_Ralan ?? null, $ttljmdokter, 0);
        $addJurnal($setAkun->Utang_Jasa_Medik_Dokter_Laborat_Ralan ?? null, 0, $ttljmdokter);

        // 3. Jasa Petugas
        $addJurnal($setAkun->Beban_Jasa_Medik_Petugas_Laborat_Ralan ?? null, $ttljmpetugas, 0);
        $addJurnal($setAkun->Utang_Jasa_Medik_Petugas_Laborat_Ralan ?? null, 0, $ttljmpetugas);

        // 4. HPP BHP Persediaan
        $addJurnal($setAkun->HPP_Persediaan_Laborat_Rawat_Jalan ?? null, $ttlbhp, 0);
        $addJurnal($setAkun->Persediaan_BHP_Laborat_Rawat_Jalan ?? null, 0, $ttlbhp);

        // 5. KSO
        $addJurnal($setAkun->Beban_Kso_Laborat_Ralan ?? null, $ttlkso, 0);
        $addJurnal($setAkun->Utang_Kso_Laborat_Ralan ?? null, 0, $ttlkso);

        // 6. Jasa Sarana
        $addJurnal($setAkun->Beban_Jasa_Sarana_Laborat_Ralan ?? null, $ttljasasarana, 0);
        $addJurnal($setAkun->Utang_Jasa_Sarana_Laborat_Ralan ?? null, 0, $ttljasasarana);

        // 7. Jasa Perujuk
        $addJurnal($setAkun->Beban_Jasa_Perujuk_Laborat_Ralan ?? null, $ttljmperujuk, 0);
        $addJurnal($setAkun->Utang_Jasa_Perujuk_Laborat_Ralan ?? null, 0, $ttljmperujuk);

        // 8. Manajemen
        $addJurnal($setAkun->Beban_Jasa_Menejemen_Laborat_Ralan ?? null, $ttlmenejemen, 0);
        $addJurnal($setAkun->Utang_Jasa_Menejemen_Laborat_Ralan ?? null, 0, $ttlmenejemen);

        // Bulk insert to detailjurnal
        $insertDetails = [];
        foreach ($jurnalItems as $kdRek => $item) {
            $insertDetails[] = [
                'no_jurnal' => $noJurnal,
                'kd_rek'    => $kdRek,
                'debet'     => $item['debet'],
                'kredit'    => $item['kredit'],
            ];
        }

        if (!empty($insertDetails)) {
            DB::table('detailjurnal')->insert($insertDetails);
        }
    }
}
