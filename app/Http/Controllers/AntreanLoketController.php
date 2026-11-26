<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AntreanLoketController extends Controller
{
    private static bool $columnsChecked = false;

    public function __construct()
    {
        $this->ensureCallColumns();
    }

    /**
     * Pastikan kolom tracking panggilan (called_at, call_count) tersedia di tb_list
     * agar kompatibel meski migrasi belum sempat dijalankan manual.
     */
    private function ensureCallColumns(): void
    {
        if (self::$columnsChecked) {
            return;
        }
        self::$columnsChecked = true;

        try {
            if (Schema::hasTable('tb_list')) {
                $missingCalledAt = !Schema::hasColumn('tb_list', 'called_at');
                $missingCallCount = !Schema::hasColumn('tb_list', 'call_count');

                if ($missingCalledAt || $missingCallCount) {
                    Schema::table('tb_list', function (Blueprint $table) use ($missingCalledAt, $missingCallCount) {
                        if ($missingCalledAt) {
                            $table->timestamp('called_at')->nullable()->after('status');
                        }
                        if ($missingCallCount) {
                            $table->unsignedInteger('call_count')->default(0)->after('called_at');
                        }
                    });
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika tidak punya hak DDL; query tetap aman selama kolom ada
        }
    }

    protected function setting()
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->logo = $setting->logo ? 'data:image/jpeg;base64,' . base64_encode($setting->logo) : null;
            $setting->wallpaper = $setting->wallpaper ? 'data:image/jpeg;base64,' . base64_encode($setting->wallpaper) : null;
        }
        return $setting;
    }

    /**
     * Halaman Display TV Antrean Loket (Publik, tanpa login)
     */
    public function display()
    {
        return view('antrean.loket', [
            'data' => $this->setting(),
        ]);
    }

    /**
     * Halaman Panel Pemanggil Antrean Loket (Petugas Pendaftaran)
     */
    public function caller()
    {
        return view('content.antrean.loket_panggil', [
            'data' => $this->setting(),
        ]);
    }

    /**
     * Data real-time antrean loket hari ini (digunakan oleh Panel Pemanggil & Display TV)
     */
    public function getData(Request $request): JsonResponse
    {
        $today = date('Y-m-d');
        $kdLayanan = $request->input('kd_layanan');

        $allToday = DB::table('tb_list')
            ->where('date_list', $today)
            ->orderBy('kd_list', 'asc')
            ->get();

        $waitingQuery = $allToday->where('status', 'Print');
        if (in_array($kdLayanan, ['A', 'B'], true)) {
            $waitingList = $waitingQuery->where('kd_layanan', $kdLayanan)->values();
        } else {
            $waitingList = $waitingQuery->values();
        }

        $callingList = $allToday->where('status', 'Call')
            ->sortByDesc(function ($item) {
                return ($item->called_at ?? '') . '|' . $item->kd_list;
            })
            ->values();

        $finishedList = $allToday->where('status', 'Finish')
            ->sortByDesc(function ($item) {
                return ($item->called_at ?? '') . '|' . $item->kd_list;
            })
            ->take(30)
            ->values();

        $latestCall = $callingList->first();

        $loketActive = [];
        for ($i = 1; $i <= 4; $i++) {
            $loketActive[$i] = $callingList->firstWhere('kd_loket', $i);
        }

        return response()->json([
            'success' => true,
            'today' => $today,
            'summary' => [
                'waiting_a'      => $allToday->where('status', 'Print')->where('kd_layanan', 'A')->count(),
                'waiting_b'      => $allToday->where('status', 'Print')->where('kd_layanan', 'B')->count(),
                'waiting_total'  => $allToday->where('status', 'Print')->count(),
                'calling_total'  => $allToday->where('status', 'Call')->count(),
                'finished_total' => $allToday->where('status', 'Finish')->count(),
                'total'          => $allToday->count(),
            ],
            'latest_call'  => $latestCall,
            'loket_active' => $loketActive,
            'waiting'      => $waitingList,
            'calling'      => $callingList,
            'finished'     => $finishedList,
        ]);
    }

    /**
     * Panggil antrean berikutnya secara transaksional (aman dari race condition antar loket)
     */
    public function callNext(Request $request): JsonResponse
    {
        $request->validate([
            'kd_loket'   => 'required|integer|min:1|max:20',
            'kd_layanan' => 'nullable|in:ALL,A,B',
        ]);

        $kdLoket = (int) $request->input('kd_loket');
        $kdLayanan = $request->input('kd_layanan', 'ALL');
        $today = date('Y-m-d');

        $result = DB::transaction(function () use ($kdLoket, $kdLayanan, $today) {
            // 1. Selesaikan antrean yang sedang aktif (Call) di loket ini
            $finishedCount = DB::table('tb_list')
                ->where('date_list', $today)
                ->where('kd_loket', $kdLoket)
                ->where('status', 'Call')
                ->lockForUpdate()
                ->update(['status' => 'Finish']);

            // 2. Ambil & kunci baris antrean menunggu paling awal (FIFO)
            $query = DB::table('tb_list')
                ->where('date_list', $today)
                ->where('status', 'Print');

            if (in_array($kdLayanan, ['A', 'B'], true)) {
                $query->where('kd_layanan', $kdLayanan);
            }

            $next = $query->orderBy('kd_list', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$next) {
                return [
                    'found' => false,
                    'finished_previous' => $finishedCount > 0,
                ];
            }

            $now = now();
            $newCount = ((int) ($next->call_count ?? 0)) + 1;

            DB::table('tb_list')
                ->where('kd_list', $next->kd_list)
                ->update([
                    'status'     => 'Call',
                    'kd_loket'   => $kdLoket,
                    'called_at'  => $now,
                    'call_count' => $newCount,
                ]);

            $updated = DB::table('tb_list')->where('kd_list', $next->kd_list)->first();

            return [
                'found' => true,
                'ticket' => $updated,
            ];
        }, 5);

        if (!$result['found']) {
            $msg = $result['finished_previous']
                ? "Antrean sebelumnya telah diselesaikan. Tidak ada antrean menunggu di antrean " . ($kdLayanan === 'ALL' ? 'Loket' : "Layanan {$kdLayanan}") . "."
                : "Tidak ada nomor antrean yang menunggu.";

            return response()->json([
                'success' => false,
                'empty'   => true,
                'message' => $msg,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Memanggil nomor {$result['ticket']->antrian} ke Loket {$kdLoket}.",
            'data'    => $result['ticket'],
        ]);
    }

    /**
     * Panggil ulang nomor tertentu (atau panggil langsung nomor spesifik dari daftar antrean)
     */
    public function recall(Request $request): JsonResponse
    {
        $request->validate([
            'kd_list'  => 'required|string',
            'kd_loket' => 'required|integer|min:1|max:20',
        ]);

        $kdList = $request->input('kd_list');
        $kdLoket = (int) $request->input('kd_loket');
        $today = date('Y-m-d');

        $result = DB::transaction(function () use ($kdList, $kdLoket, $today) {
            $ticket = DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->where('date_list', $today)
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                return ['error' => 'Nomor antrean tidak ditemukan untuk hari ini.'];
            }

            // Jika nomor sedang dipanggil di loket lain, tolak agar tidak bentrok
            if ($ticket->status === 'Call' && (int) $ticket->kd_loket !== $kdLoket && $ticket->kd_loket !== null) {
                return ['error' => "Nomor {$ticket->antrian} sedang dipanggil di Loket {$ticket->kd_loket}."];
            }

            // Jika petugas memanggil nomor baru (dari Print/Finish) atau beda nomor, selesaikan Call aktif di loket ini
            DB::table('tb_list')
                ->where('date_list', $today)
                ->where('kd_loket', $kdLoket)
                ->where('status', 'Call')
                ->where('kd_list', '!=', $kdList)
                ->lockForUpdate()
                ->update(['status' => 'Finish']);

            $now = now();
            $newCount = ((int) ($ticket->call_count ?? 0)) + 1;

            DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->update([
                    'status'     => 'Call',
                    'kd_loket'   => $kdLoket,
                    'called_at'  => $now,
                    'call_count' => $newCount,
                ]);

            return [
                'ticket' => DB::table('tb_list')->where('kd_list', $kdList)->first(),
            ];
        }, 5);

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => "Memanggil {$result['ticket']->antrian} ke Loket {$kdLoket}.",
            'data'    => $result['ticket'],
        ]);
    }

    /**
     * Tandai antrean selesai dilayani (Finish)
     */
    public function finish(Request $request): JsonResponse
    {
        $request->validate([
            'kd_list' => 'required|string',
        ]);

        $kdList = $request->input('kd_list');

        $updated = DB::transaction(function () use ($kdList) {
            $ticket = DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                return null;
            }

            DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->update(['status' => 'Finish']);

            return DB::table('tb_list')->where('kd_list', $kdList)->first();
        }, 5);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Data antrean tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Antrean {$updated->antrian} ditandai selesai.",
            'data'    => $updated,
        ]);
    }

    /**
     * Kembalikan antrean ke daftar tunggu (Print) jika pasien belum hadir (Lewati)
     */
    public function skip(Request $request): JsonResponse
    {
        $request->validate([
            'kd_list' => 'required|string',
        ]);

        $kdList = $request->input('kd_list');

        $updated = DB::transaction(function () use ($kdList) {
            $ticket = DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                return null;
            }

            DB::table('tb_list')
                ->where('kd_list', $kdList)
                ->update([
                    'status'   => 'Print',
                    'kd_loket' => null,
                ]);

            return DB::table('tb_list')->where('kd_list', $kdList)->first();
        }, 5);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Data antrean tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Antrean {$updated->antrian} dikembalikan ke daftar menunggu.",
            'data'    => $updated,
        ]);
    }
}
