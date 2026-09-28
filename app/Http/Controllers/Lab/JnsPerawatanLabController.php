<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\JnsPerawatanLab;
use App\Models\Lab\TemplateLaboratorium;
use App\Models\Penjab;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JnsPerawatanLabController extends Controller
{
    protected JnsPerawatanLab $jenis;

    public function __construct()
    {
        $this->jenis = new JnsPerawatanLab();
    }

    /**
     * Halaman Utama Master Tarif & Tindakan Laboratorium
     */
    public function index()
    {
        $penjab = Penjab::where('status', '1')
            ->orderBy('png_jawab', 'asc')
            ->get();

        return view('content.master.tarif_lab', compact('penjab'));
    }

    /**
     * Server-side DataTables untuk Master Tarif Lab
     */
    public function dataTable(Request $request)
    {
        $query = JnsPerawatanLab::with('penjab')
            ->withCount('template');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('kd_pj')) {
            $query->where('kd_pj', $request->kd_pj);
        }

        return datatables()->of($query)
            ->addColumn('template_count', function ($row) {
                return (int) ($row->template_count ?? 0);
            })
            ->addColumn('total_byr_formatted', function ($row) {
                return 'Rp ' . number_format($row->total_byr ?? 0, 0, ',', '.');
            })
            ->addColumn('penjab_nama', function ($row) {
                return $row->penjab ? $row->penjab->png_jawab : ($row->kd_pj ?: '-');
            })
            ->addColumn('action', function ($row) {
                return $row->kd_jenis_prw;
            })
            ->make(true);
    }

    /**
     * Generate Next Kode Jenis Perawatan Lab otomatis (e.g. PK0001, PA0001, MB0001)
     */
    public function getNextKode(Request $request): JsonResponse
    {
        $kategori = in_array($request->kategori, ['PK', 'PA', 'MB']) ? $request->kategori : 'PK';
        $prefix = $kategori;

        // Cari kode dengan prefix kategori
        $latest = JnsPerawatanLab::where('kd_jenis_prw', 'like', "{$prefix}%")
            ->selectRaw("MAX(CAST(SUBSTRING(kd_jenis_prw, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) as max_num")
            ->first();

        $num = ($latest && $latest->max_num) ? ($latest->max_num + 1) : 1;
        $nextKode = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

        // Pastikan unik jika kebetulan sudah terpakai
        while (JnsPerawatanLab::where('kd_jenis_prw', $nextKode)->exists()) {
            $num++;
            $nextKode = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
        }

        return response()->json(['next_kode' => $nextKode]);
    }

    /**
     * Ambil 1 data jenis perawatan lab beserta templatenya
     */
    public function show($kd_jenis_prw): JsonResponse
    {
        $lab = JnsPerawatanLab::with(['penjab', 'template'])
            ->where('kd_jenis_prw', $kd_jenis_prw)
            ->first();

        if (!$lab) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $lab
        ]);
    }

    /**
     * Simpan Master Tarif Lab Baru
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kd_jenis_prw' => 'required|string|max:15|unique:jns_perawatan_lab,kd_jenis_prw',
            'nm_perawatan' => 'required|string|max:80',
            'kategori'     => 'required|in:PK,PA,MB',
            'kd_pj'        => 'required|string|exists:penjab,kd_pj',
            'kelas'        => 'required|string',
        ]);

        $bagian_rs               = (float) ($request->bagian_rs ?? 0);
        $bhp                     = (float) ($request->bhp ?? 0);
        $tarif_perujuk           = (float) ($request->tarif_perujuk ?? 0);
        $tarif_tindakan_dokter   = (float) ($request->tarif_tindakan_dokter ?? 0);
        $tarif_tindakan_petugas  = (float) ($request->tarif_tindakan_petugas ?? 0);
        $kso                     = (float) ($request->kso ?? 0);
        $menejemen               = (float) ($request->menejemen ?? 0);
        $total_byr               = $bagian_rs + $bhp + $tarif_perujuk + $tarif_tindakan_dokter + $tarif_tindakan_petugas + $kso + $menejemen;

        $data = [
            'kd_jenis_prw'           => trim($request->kd_jenis_prw),
            'nm_perawatan'           => trim($request->nm_perawatan),
            'kategori'               => $request->kategori,
            'kd_pj'                  => $request->kd_pj,
            'kelas'                  => $request->kelas ?: 'Rawat Jalan',
            'bagian_rs'              => $bagian_rs,
            'bhp'                    => $bhp,
            'tarif_perujuk'          => $tarif_perujuk,
            'tarif_tindakan_dokter'  => $tarif_tindakan_dokter,
            'tarif_tindakan_petugas' => $tarif_tindakan_petugas,
            'kso'                    => $kso,
            'menejemen'              => $menejemen,
            'total_byr'              => $total_byr,
            'status'                 => $request->status !== null ? (string)$request->status : '1',
        ];

        try {
            $created = JnsPerawatanLab::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Tarif pemeriksaan lab berhasil ditambahkan.',
                'data'    => $created,
            ]);
        } catch (\Exception $e) {
            Log::error('Error store JnsPerawatanLab: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Master Tarif Lab
     */
    public function update(Request $request, $kd_jenis_prw): JsonResponse
    {
        $lab = JnsPerawatanLab::where('kd_jenis_prw', $kd_jenis_prw)->first();
        if (!$lab) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'nm_perawatan' => 'required|string|max:80',
            'kategori'     => 'required|in:PK,PA,MB',
            'kd_pj'        => 'required|string|exists:penjab,kd_pj',
            'kelas'        => 'required|string',
        ]);

        $bagian_rs               = (float) ($request->bagian_rs ?? 0);
        $bhp                     = (float) ($request->bhp ?? 0);
        $tarif_perujuk           = (float) ($request->tarif_perujuk ?? 0);
        $tarif_tindakan_dokter   = (float) ($request->tarif_tindakan_dokter ?? 0);
        $tarif_tindakan_petugas  = (float) ($request->tarif_tindakan_petugas ?? 0);
        $kso                     = (float) ($request->kso ?? 0);
        $menejemen               = (float) ($request->menejemen ?? 0);
        $total_byr               = $bagian_rs + $bhp + $tarif_perujuk + $tarif_tindakan_dokter + $tarif_tindakan_petugas + $kso + $menejemen;

        $data = [
            'nm_perawatan'           => trim($request->nm_perawatan),
            'kategori'               => $request->kategori,
            'kd_pj'                  => $request->kd_pj,
            'kelas'                  => $request->kelas ?: 'Rawat Jalan',
            'bagian_rs'              => $bagian_rs,
            'bhp'                    => $bhp,
            'tarif_perujuk'          => $tarif_perujuk,
            'tarif_tindakan_dokter'  => $tarif_tindakan_dokter,
            'tarif_tindakan_petugas' => $tarif_tindakan_petugas,
            'kso'                    => $kso,
            'menejemen'              => $menejemen,
            'total_byr'              => $total_byr,
            'status'                 => $request->status !== null ? (string)$request->status : $lab->status,
        ];

        try {
            $lab->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Tarif pemeriksaan lab berhasil diperbarui.',
                'data'    => $lab,
            ]);
        } catch (\Exception $e) {
            Log::error('Error update JnsPerawatanLab: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Master Tarif Lab (beserta templatenya jika belum pernah dipakai transaksi)
     */
    public function destroy($kd_jenis_prw): JsonResponse
    {
        $lab = JnsPerawatanLab::where('kd_jenis_prw', $kd_jenis_prw)->first();
        if (!$lab) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Cek apakah pernah dipakai dalam transaksi periksa_lab atau permintaan_pemeriksaan_lab
        $isUsedInPeriksa = DB::table('periksa_lab')->where('kd_jenis_prw', $kd_jenis_prw)->exists();
        $isUsedInPermintaan = DB::table('permintaan_pemeriksaan_lab')->where('kd_jenis_prw', $kd_jenis_prw)->exists();

        if ($isUsedInPeriksa || $isUsedInPermintaan) {
            return response()->json([
                'success' => false,
                'is_used' => true,
                'message' => 'Tindakan lab ini sudah pernah memiliki histori transaksi pasien dan tidak dapat dihapus. Anda dapat menonaktifkan statusnya agar tidak muncul di form permintaan baru.'
            ], 422);
        }

        try {
            $lab->delete(); // ON DELETE CASCADE di database akan otomatis menghapus template_laboratorium terkait
            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan lab dan template sub-pemeriksaannya berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error destroy JnsPerawatanLab: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status aktif / non-aktif
     */
    public function toggleStatus($kd_jenis_prw): JsonResponse
    {
        $lab = JnsPerawatanLab::where('kd_jenis_prw', $kd_jenis_prw)->first();
        if (!$lab) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $newStatus = $lab->status === '1' ? '0' : '1';
        $lab->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status'  => $newStatus,
            'message' => $newStatus === '1' ? 'Tindakan lab berhasil diaktifkan.' : 'Tindakan lab berhasil dinonaktifkan.'
        ]);
    }

    // =========================================================================
    // SUB-PEMERIKSAAN & TEMPLATE LABORATORIUM (template_laboratorium)
    // =========================================================================

    /**
     * Ambil daftar template sub-pemeriksaan dari jenis pemeriksaan lab tertentu
     */
    public function getTemplates($kd_jenis_prw): JsonResponse
    {
        $templates = TemplateLaboratorium::where('kd_jenis_prw', $kd_jenis_prw)
            ->orderBy('urut', 'asc')
            ->orderBy('id_template', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $templates
        ]);
    }

    /**
     * Simpan / Update Sub-Pemeriksaan (Template Laboratorium)
     */
    public function storeTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'kd_jenis_prw'     => 'required|exists:jns_perawatan_lab,kd_jenis_prw',
            'Pemeriksaan'      => 'required|string|max:200',
            'satuan'           => 'nullable|string|max:20',
            'nilai_rujukan_ld' => 'nullable|string|max:30',
            'nilai_rujukan_la' => 'nullable|string|max:30',
            'nilai_rujukan_pd' => 'nullable|string|max:30',
            'nilai_rujukan_pa' => 'nullable|string|max:30',
            'urut'             => 'nullable|integer',
        ]);

        $urut = $request->urut;
        if ($urut === null || $urut === '') {
            $maxUrut = TemplateLaboratorium::where('kd_jenis_prw', $request->kd_jenis_prw)->max('urut');
            $urut = ($maxUrut !== null) ? ($maxUrut + 1) : 1;
        }

        $data = [
            'kd_jenis_prw'     => $request->kd_jenis_prw,
            'Pemeriksaan'      => trim($request->Pemeriksaan),
            'satuan'           => $request->satuan ?: '',
            'nilai_rujukan_ld' => $request->nilai_rujukan_ld ?: '',
            'nilai_rujukan_la' => $request->nilai_rujukan_la ?: '',
            'nilai_rujukan_pd' => $request->nilai_rujukan_pd ?: '',
            'nilai_rujukan_pa' => $request->nilai_rujukan_pa ?: '',
            'bagian_rs'        => (float)($request->bagian_rs ?? 0),
            'bhp'              => (float)($request->bhp ?? 0),
            'bagian_perujuk'   => (float)($request->bagian_perujuk ?? 0),
            'bagian_dokter'    => (float)($request->bagian_dokter ?? 0),
            'bagian_laborat'   => (float)($request->bagian_laborat ?? 0),
            'kso'              => (float)($request->kso ?? 0),
            'menejemen'        => (float)($request->menejemen ?? 0),
            'biaya_item'       => (float)($request->biaya_item ?? 0),
            'urut'             => (int)$urut,
        ];

        try {
            if ($request->filled('id_template') && (int)$request->id_template > 0) {
                // Update
                $template = TemplateLaboratorium::findOrFail($request->id_template);
                $template->update($data);
                $msg = 'Item pemeriksaan berhasil diperbarui.';
            } else {
                // Create
                $template = TemplateLaboratorium::create($data);
                $msg = 'Item pemeriksaan baru berhasil ditambahkan.';
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
                'data'    => $template
            ]);
        } catch (\Exception $e) {
            Log::error('Error storeTemplate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan item pemeriksaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Sub-Pemeriksaan (Template Laboratorium)
     */
    public function destroyTemplate($id_template): JsonResponse
    {
        $template = TemplateLaboratorium::find($id_template);
        if (!$template) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        // Cek apakah sudah ada hasil di detail_periksa_lab
        $isUsed = DB::table('detail_periksa_lab')->where('id_template', $id_template)->exists();
        if ($isUsed) {
            return response()->json([
                'success' => false,
                'message' => 'Item pemeriksaan ini sudah memiliki riwayat hasil pemeriksaan pasien dan tidak dapat dihapus.'
            ], 422);
        }

        try {
            $template->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item pemeriksaan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error destroyTemplate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salin template sub-pemeriksaan dari paket lab lain
     */
    public function copyTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'from_kd_jenis_prw' => 'required|exists:jns_perawatan_lab,kd_jenis_prw',
            'to_kd_jenis_prw'   => 'required|exists:jns_perawatan_lab,kd_jenis_prw',
        ]);

        if ($request->from_kd_jenis_prw === $request->to_kd_jenis_prw) {
            return response()->json([
                'success' => false,
                'message' => 'Paket asal dan tujuan tidak boleh sama.'
            ], 422);
        }

        $sourceTemplates = TemplateLaboratorium::where('kd_jenis_prw', $request->from_kd_jenis_prw)
            ->orderBy('urut', 'asc')
            ->orderBy('id_template', 'asc')
            ->get();

        if ($sourceTemplates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Paket asal tidak memiliki item sub-pemeriksaan untuk disalin.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $maxUrut = TemplateLaboratorium::where('kd_jenis_prw', $request->to_kd_jenis_prw)->max('urut') ?? 0;
            $copiedCount = 0;

            foreach ($sourceTemplates as $item) {
                $maxUrut++;
                TemplateLaboratorium::create([
                    'kd_jenis_prw'     => $request->to_kd_jenis_prw,
                    'Pemeriksaan'      => $item->Pemeriksaan,
                    'satuan'           => $item->satuan,
                    'nilai_rujukan_ld' => $item->nilai_rujukan_ld,
                    'nilai_rujukan_la' => $item->nilai_rujukan_la,
                    'nilai_rujukan_pd' => $item->nilai_rujukan_pd,
                    'nilai_rujukan_pa' => $item->nilai_rujukan_pa,
                    'bagian_rs'        => $item->bagian_rs,
                    'bhp'              => $item->bhp,
                    'bagian_perujuk'   => $item->bagian_perujuk,
                    'bagian_dokter'    => $item->bagian_dokter,
                    'bagian_laborat'   => $item->bagian_laborat,
                    'kso'              => $item->kso,
                    'menejemen'        => $item->menejemen,
                    'biaya_item'       => $item->biaya_item,
                    'urut'             => $maxUrut,
                ]);
                $copiedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyalin {$copiedCount} item sub-pemeriksaan.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error copyTemplate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyalin item: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    // LEGACY METHODS (Untuk Kompatibilitas Form Order Lab)
    // =========================================================================

    public function get(Request $request): JsonResponse
    {
        $jenis = $this->jenis;
        $data = $jenis->where('status', '1')
            ->where('nm_perawatan', 'like', "%{$request->nm_perawatan}%")->get();
        return response()->json($data);
    }

    public function getTemplate(Request $request): JsonResponse
    {
        $data = $this->jenis->select(['kd_jenis_prw', 'nm_perawatan'])->whereIn('kd_jenis_prw', $request->kode)
            ->with('template', function ($query) use ($request) {
                if ($request->nm_perawatan) {
                    return $query = $query->where('nm_perawatan', 'like', "%{$request->nm_perawatan}%");
                }
                return $query;
            })->get();
        return response()->json($data);
    }
}
