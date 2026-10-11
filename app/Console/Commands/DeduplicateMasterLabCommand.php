<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeduplicateMasterLabCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lab:deduplicate-master 
                            {--dry-run : Jalankan simulasi tanpa mengubah database}
                            {--no-backup : Lewati pembuatan tabel backup}
                            {--rollback : Kembalikan data dari tabel backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch konsolidasi master tarif lab (jns_perawatan_lab) agar hanya 1 master bersih per jenis pemeriksaan (standar Rawat Jalan FKTP)';

    protected string $backupTableJns = 'jns_perawatan_lab_backup_20261011';
    protected string $backupTableTpl = 'template_laboratorium_backup_20261011';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("============================================================");
        $this->info("   KONSOLIDASI & DEDUPLIKASI MASTER TARIF LAB FKTP");
        $this->info("============================================================");

        // MODE ROLLBACK
        if ($this->option('rollback')) {
            return $this->handleRollback();
        }

        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn(">>> MENJALANKAN DALAM MODE SIMULASI (DRY-RUN) - TIDAK ADA DATA YANG DIUBAH <<<");
        } else {
            // BACKUP OTOMATIS
            if (!$this->option('no-backup')) {
                $this->createBackup();
            }
        }

        // 1. Ambil data transaksi yang ada (Wajib dilindungi)
        $usedInPeriksa = DB::table('periksa_lab')->distinct()->pluck('kd_jenis_prw')->toArray();
        $usedInPermintaan = DB::table('permintaan_pemeriksaan_lab')->distinct()->pluck('kd_jenis_prw')->toArray();
        $protectedCodes = array_values(array_unique(array_merge($usedInPeriksa, $usedInPermintaan)));

        if (!empty($protectedCodes)) {
            $this->info("Kode terlindungi (memiliki riwayat transaksi pasien): " . implode(', ', $protectedCodes));
        }

        // 2. Ambil semua data jns_perawatan_lab
        $allLabs = DB::table('jns_perawatan_lab')->get();
        $totalInitial = $allLabs->count();

        // Hitung template count per kode
        $templateCounts = DB::table('template_laboratorium')
            ->select('kd_jenis_prw', DB::raw('count(*) as count'))
            ->groupBy('kd_jenis_prw')
            ->pluck('count', 'kd_jenis_prw')
            ->toArray();

        // 3. Kelompokkan berdasarkan nama pemeriksaan bersih
        $grouped = [];
        foreach ($allLabs as $lab) {
            $normalizedName = strtoupper(trim(preg_replace('/\s+/', ' ', $lab->nm_perawatan)));
            $grouped[$normalizedName][] = $lab;
        }

        $this->info("Total data master saat ini : {$totalInitial} baris");
        $this->info("Jumlah jenis pemeriksaan unik: " . count($grouped) . " jenis");
        $this->newLine();

        $keepers = [];
        $toDelete = [];
        $templateMigrated = 0;

        foreach ($grouped as $name => $items) {
            // Tentukan pemenang (keeper)
            $keeper = $this->determineKeeper($items, $protectedCodes, $templateCounts);
            $keepers[] = $keeper;

            foreach ($items as $item) {
                if ($item->kd_jenis_prw !== $keeper->kd_jenis_prw) {
                    $toDelete[] = $item;
                }
            }
        }

        $deleteCount = count($toDelete);
        $keepCount = count($keepers);

        $this->info("Hasil Analisis Deduplikasi:");
        $this->info("- Data yang akan DIPERTAHANKAN : {$keepCount} baris");
        $this->warn("- Data duplikat yang akan DIHAPUS : {$deleteCount} baris");
        $this->newLine();

        if ($isDryRun) {
            $this->info("Preview 10 pemeriksaan hasil deduplikasi:");
            $sampleTable = [];
            foreach (array_slice($keepers, 0, 10) as $k) {
                $dupItems = array_filter($toDelete, function ($d) use ($k) {
                    return strtoupper(trim($d->nm_perawatan)) === strtoupper(trim($k->nm_perawatan));
                });
                $dupCodes = array_map(function ($d) { return $d->kd_jenis_prw; }, $dupItems);

                $sampleTable[] = [
                    'Pemeriksaan'   => $k->nm_perawatan,
                    'Kode Dipakai'  => $k->kd_jenis_prw,
                    'Template'      => ($templateCounts[$k->kd_jenis_prw] ?? 0) . ' item',
                    'Tarif'         => 'Rp ' . number_format($k->total_byr, 0, ',', '.'),
                    'Duplikat Dihapus' => count($dupCodes) > 0 ? implode(', ', $dupCodes) : '-',
                ];
            }
            $this->table(['Nama Pemeriksaan', 'Kode Utama', 'Sub-Item', 'Total Tarif', 'Duplikat Dihapus'], $sampleTable);
            $this->info("Simulasi selesai. Jalankan tanpa opsi --dry-run untuk mengeksekusi.");
            return 0;
        }

        // EKSEKUSI DATABASE
        $this->info("Memulai proses pembersihan di database...");

        DB::beginTransaction();
        try {
            $deletedRows = 0;
            $deletedTemplates = 0;

            foreach ($grouped as $name => $items) {
                $keeper = $this->determineKeeper($items, $protectedCodes, $templateCounts);
                $dupList = [];

                foreach ($items as $item) {
                    if ($item->kd_jenis_prw !== $keeper->kd_jenis_prw) {
                        $dupList[] = $item;
                    }
                }

                if (empty($dupList)) {
                    // Update kelas keeper agar seragam Rawat Jalan
                    DB::table('jns_perawatan_lab')
                        ->where('kd_jenis_prw', $keeper->kd_jenis_prw)
                        ->update(['kelas' => 'Rawat Jalan']);
                    continue;
                }

                $dupCodes = array_map(fn($d) => $d->kd_jenis_prw, $dupList);

                // Cek apakah keeper memiliki template, jika 0 cari dari salah satu duplikat yang punya template
                $kCount = $templateCounts[$keeper->kd_jenis_prw] ?? 0;
                if ($kCount == 0) {
                    foreach ($dupList as $dup) {
                        $dupTplCount = $templateCounts[$dup->kd_jenis_prw] ?? 0;
                        if ($dupTplCount > 0) {
                            $this->copyTemplates($dup->kd_jenis_prw, $keeper->kd_jenis_prw);
                            $templateMigrated++;
                            break;
                        }
                    }
                }

                // Hapus mapping SatuSehat jika ada yang terhubung ke template duplikat
                $dupTemplateIds = DB::table('template_laboratorium')
                    ->whereIn('kd_jenis_prw', $dupCodes)
                    ->pluck('id_template')
                    ->toArray();

                if (!empty($dupTemplateIds)) {
                    DB::table('satu_sehat_mapping_lab')
                        ->whereIn('id_template', $dupTemplateIds)
                        ->delete();
                }

                // Hapus template milik kode duplikat
                $tCount = DB::table('template_laboratorium')
                    ->whereIn('kd_jenis_prw', $dupCodes)
                    ->delete();
                $deletedTemplates += $tCount;

                // Hapus jns_perawatan_lab duplikat
                $jCount = DB::table('jns_perawatan_lab')
                    ->whereIn('kd_jenis_prw', $dupCodes)
                    ->delete();
                $deletedRows += $jCount;

                // Normalkan kelas keeper menjadi 'Rawat Jalan'
                DB::table('jns_perawatan_lab')
                    ->where('kd_jenis_prw', $keeper->kd_jenis_prw)
                    ->update(['kelas' => 'Rawat Jalan']);
            }

            DB::commit();

            $totalAfter = DB::table('jns_perawatan_lab')->count();
            $tplAfter = DB::table('template_laboratorium')->count();

            $this->newLine();
            $this->info("============================================================");
            $this->info("   BERHASIL! PROSES KONSOLIDASI MASTER LAB SELESAI");
            $this->info("============================================================");
            $this->info("✓ Data master jns_perawatan_lab tersisa : {$totalAfter} baris (dari {$totalInitial})");
            $this->info("✓ Data master duplikat yang dibersihkan : {$deletedRows} baris");
            $this->info("✓ Template laboratorium tersisa        : {$tplAfter} baris");
            $this->info("✓ Template redundan yang dibersihkan   : {$deletedTemplates} baris");
            if ($templateMigrated > 0) {
                $this->info("✓ Template terselamatkan/disalin       : {$templateMigrated} paket");
            }
            $this->info("✓ Seluruh master aktif distandarkan ke : Rawat Jalan");
            $this->newLine();

            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Gagal melakukan deduplikasi: " . $e->getMessage());
            $this->error("Seluruh perubahan telah di-rollback. Database Anda tetap aman.");
            return 1;
        }
    }

    /**
     * Tentukan record yang dipertahankan sebagai master utama
     */
    protected function determineKeeper(array $items, array $protectedCodes, array $templateCounts)
    {
        // 1. Prioritas Utama: Record yang pernah dipakai dalam transaksi
        foreach ($items as $item) {
            if (in_array($item->kd_jenis_prw, $protectedCodes, true)) {
                return $item;
            }
        }

        // 2. Prioritas Kedua: Record dengan akhiran '-RJ' (Rawat Jalan standar Khanza)
        foreach ($items as $item) {
            if (str_ends_with($item->kd_jenis_prw, '-RJ')) {
                return $item;
            }
        }

        // 3. Prioritas Ketiga: Record dengan kelas 'Rawat Jalan'
        foreach ($items as $item) {
            if (strcasecmp($item->kelas, 'Rawat Jalan') === 0) {
                return $item;
            }
        }

        // 4. Prioritas Keempat: Record dengan template terbanyak
        usort($items, function ($a, $b) use ($templateCounts) {
            $countA = $templateCounts[$a->kd_jenis_prw] ?? 0;
            $countB = $templateCounts[$b->kd_jenis_prw] ?? 0;
            if ($countA !== $countB) {
                return $countB <=> $countA;
            }
            // Jika sama, pilih yang total_byr > 0
            if ($a->total_byr !== $b->total_byr) {
                return $b->total_byr <=> $a->total_byr;
            }
            return strcmp($a->kd_jenis_prw, $b->kd_jenis_prw);
        });

        return $items[0];
    }

    /**
     * Salin template sub-pemeriksaan dari sumber ke target
     */
    protected function copyTemplates(string $sourceKode, string $targetKode)
    {
        $sources = DB::table('template_laboratorium')
            ->where('kd_jenis_prw', $sourceKode)
            ->get();

        foreach ($sources as $tpl) {
            $tplArray = (array) $tpl;
            unset($tplArray['id_template']);
            $tplArray['kd_jenis_prw'] = $targetKode;

            DB::table('template_laboratorium')->insert($tplArray);
        }
    }

    /**
     * Buat backup otomatis
     */
    protected function createBackup()
    {
        $this->info("Membuat tabel cadangan (backup) otomatis...");

        // Backup jns_perawatan_lab
        DB::statement("DROP TABLE IF EXISTS `{$this->backupTableJns}`");
        DB::statement("CREATE TABLE `{$this->backupTableJns}` AS SELECT * FROM `jns_perawatan_lab`");

        // Backup template_laboratorium
        DB::statement("DROP TABLE IF EXISTS `{$this->backupTableTpl}`");
        DB::statement("CREATE TABLE `{$this->backupTableTpl}` AS SELECT * FROM `template_laboratorium`");

        $this->info("✓ Tabel cadangan berhasil dibuat: {$this->backupTableJns} & {$this->backupTableTpl}");
    }

    /**
     * Handle Rollback data dari backup
     */
    protected function handleRollback()
    {
        $hasBackupJns = Schema::hasTable($this->backupTableJns);
        $hasBackupTpl = Schema::hasTable($this->backupTableTpl);

        if (!$hasBackupJns || !$hasBackupTpl) {
            $this->error("Tabel backup tidak ditemukan ({$this->backupTableJns}). Tidak dapat melakukan rollback.");
            return 1;
        }

        if (!$this->confirm("Apakah Anda yakin ingin mengembalikan seluruh data master lab dari tabel backup?")) {
            $this->info("Rollback dibatalkan.");
            return 0;
        }

        DB::beginTransaction();
        try {
            DB::statement("TRUNCATE TABLE `template_laboratorium`");
            DB::statement("INSERT INTO `template_laboratorium` SELECT * FROM `{$this->backupTableTpl}`");

            DB::statement("TRUNCATE TABLE `jns_perawatan_lab`");
            DB::statement("INSERT INTO `jns_perawatan_lab` SELECT * FROM `{$this->backupTableJns}`");

            DB::commit();

            $countJns = DB::table('jns_perawatan_lab')->count();
            $countTpl = DB::table('template_laboratorium')->count();

            $this->info("✓ Rollback berhasil! Data master dikembalikan ke kondisi backup semula.");
            $this->info("✓ jns_perawatan_lab: {$countJns} baris | template_laboratorium: {$countTpl} baris");
            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Gagal melakukan rollback: " . $e->getMessage());
            return 1;
        }
    }
}
