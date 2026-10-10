<?php

namespace App\Console\Commands;

use App\Services\SatuSehat\SatuSehatServiceRequestLabService;
use Illuminate\Console\Command;

class SatuSehatSyncLabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'satusehat:sync-lab {--date= : Tanggal pemeriksaan lab Y-m-d (default: hari ini)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim otomatis ServiceRequest Laboratorium ke SatuSehat Kemenkes';

    /**
     * Execute the console command.
     */
    public function handle(SatuSehatServiceRequestLabService $service)
    {
        $date = $this->option('date') ?: date('Y-m-d');
        $this->info("Memulai auto-sync ServiceRequest Lab SatuSehat untuk tanggal: {$date}");

        $result = $service->sendBatch($date, $date);

        $this->info("Selesai diproses.");
        $this->line("Total Diperiksa: {$result['total']}");
        $this->info("Berhasil Terkirim: {$result['success']}");
        if ($result['error'] > 0) {
            $this->warn("Belum Lengkap / Gagal: {$result['error']}");
        }

        return 0;
    }
}
