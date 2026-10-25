<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetJamMinimal extends Model
{
    use HasFactory;

    protected $table = 'set_jam_minimal';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Mengambil setting tunggal dari tabel set_jam_minimal (atau default jika kosong)
     */
    public static function getSetting()
    {
        $setting = self::first();
        if (!$setting) {
            try {
                $setting = self::create([
                    'lamajam' => 24,
                    'hariawal' => 'No',
                    'feeperujuk' => 0,
                    'diagnosaakhir' => 'Yes',
                    'bayi' => 0,
                    'aktifkan_hapus_data_salah' => 'No',
                    'kamar_inap_kasir_ralan' => 'No',
                    'ubah_status_kamar' => 'Yes',
                ]);
            } catch (\Exception $e) {
                // Fallback object jika database belum dapat di-insert
                $setting = (object) [
                    'lamajam' => 24,
                    'hariawal' => 'No',
                    'feeperujuk' => 0,
                    'diagnosaakhir' => 'Yes',
                    'bayi' => 0,
                    'aktifkan_hapus_data_salah' => 'No',
                    'kamar_inap_kasir_ralan' => 'No',
                    'ubah_status_kamar' => 'Yes',
                ];
            }
        }
        return $setting;
    }

    /**
     * Hitung lama hari rawat inap berdasarkan aturan set_jam_minimal
     * 
     * @param string $tglMasuk Format Y-m-d
     * @param string|null $jamMasuk Format H:i:s
     * @param string|null $tglKeluar Format Y-m-d
     * @param string|null $jamKeluar Format H:i:s
     * @return int Jumlah hari rawat inap
     */
    public static function hitungLama($tglMasuk, $jamMasuk = null, $tglKeluar = null, $jamKeluar = null)
    {
        if (empty($tglMasuk) || $tglMasuk === '0000-00-00') {
            return 0;
        }

        $setting = self::getSetting();
        $lamaJam = $setting ? (int)$setting->lamajam : 24;
        $hariAwal = $setting ? $setting->hariawal : 'No';

        if ($lamaJam <= 0) {
            $lamaJam = 24;
        }

        $jamMasuk = (!empty($jamMasuk) && $jamMasuk !== '00:00:00') ? $jamMasuk : '00:00:00';
        $masuk = Carbon::parse(date('Y-m-d', strtotime($tglMasuk)) . ' ' . $jamMasuk);

        $isPulang = (!empty($tglKeluar) && $tglKeluar !== '0000-00-00');
        if ($isPulang) {
            $jamKeluar = (!empty($jamKeluar) && $jamKeluar !== '00:00:00') ? $jamKeluar : '00:00:00';
            $keluar = Carbon::parse(date('Y-m-d', strtotime($tglKeluar)) . ' ' . $jamKeluar);
        } else {
            $keluar = Carbon::now();
        }

        if ($keluar->lt($masuk)) {
            $keluar = $masuk->copy();
        }

        $diffHours = $masuk->diffInSeconds($keluar) / 3600;
        $diffDays = $masuk->copy()->startOfDay()->diffInDays($keluar->copy()->startOfDay());

        if ($hariAwal === 'Yes') {
            // Skema 1 (Hitung hari awal langsung dihitung):
            if ($diffDays == 0) {
                return 1;
            } else {
                return (int)($diffDays + 1);
            }
        } else {
            // Skema 2 (Pengakuan hari setelah melewati batas jam minimal):
            if ($diffHours < $lamaJam) {
                return 0;
            } else {
                // Sudah mencapai atau melewati batas jam minimal
                $hari = (int) floor($diffHours / $lamaJam);
                return max(1, $hari);
            }
        }
    }
}
