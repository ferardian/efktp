<?php

namespace App\Action;

use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\RawatInapDr;
use App\Models\RawatInapPr;
use App\Models\RawatInapDrPr;
use App\Models\JenisPerawatanInap;
use App\Traits\Track;
use DB;
use Exception;

class TindakanRanapAction
{
    use Track;

    public function handleCreate(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $tindakan = $this->createTindakan($data);
                $this->createTampJurnal($this->getRekeningMapping(), $tindakan['totals'], $data['pelaksana']);
                $this->writeOnJurnal($data);
                return $tindakan;
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleDelete(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $totals = $this->deleteTindakan($data);
                $this->createTampJurnal($this->getRekeningMapping(), $totals, $data['pelaksana'], true);
                $this->writeOnJurnal($data, true);
                return true;
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function getRekeningMapping()
    {
        return DB::table('set_akun_ranap')->first();
    }

    private function createTindakan(array $data)
    {
        $no_rawat = $data['no_rawat'];
        $kd_jenis_prw = $data['kd_jenis_prw'];
        $pelaksana = $data['pelaksana'];
        $tgl_perawatan = $data['tgl_perawatan'];
        $jam_rawat = $data['jam_rawat'];

        $jns = JenisPerawatanInap::where('kd_jenis_prw', $kd_jenis_prw)->first();
        if (!$jns) throw new Exception("Tindakan tidak ditemukan");

        $totals = [
            'material' => $jns->material,
            'bhp' => $jns->bhp,
            'tarif_dr' => ($pelaksana == 'pr') ? 0 : $jns->tarif_tindakandr,
            'tarif_pr' => ($pelaksana == 'dr') ? 0 : $jns->tarif_tindakanpr,
            'kso' => $jns->kso,
            'menejemen' => $jns->menejemen,
            'total' => 0
        ];

        $insertData = [
            'no_rawat' => $no_rawat,
            'kd_jenis_prw' => $kd_jenis_prw,
            'tgl_perawatan' => $tgl_perawatan,
            'jam_rawat' => $jam_rawat,
            'material' => $totals['material'],
            'bhp' => $totals['bhp'],
            'kso' => $totals['kso'],
            'menejemen' => $totals['menejemen'],
        ];

        if ($pelaksana == 'dr') {
            $totals['total'] = $jns->total_byrdr;
            $insertData['kd_dokter'] = $data['kd_dokter'];
            $insertData['tarif_tindakandr'] = $totals['tarif_dr'];
            $insertData['biaya_rawat'] = $totals['total'];
            RawatInapDr::create($insertData);
        } elseif ($pelaksana == 'pr') {
            $totals['total'] = $jns->total_byrpr;
            $insertData['nip'] = $data['nip'];
            $insertData['tarif_tindakanpr'] = $totals['tarif_pr'];
            $insertData['biaya_rawat'] = $totals['total'];
            RawatInapPr::create($insertData);
        } elseif ($pelaksana == 'drpr') {
            $totals['total'] = $jns->total_byrdrpr;
            $insertData['kd_dokter'] = $data['kd_dokter'];
            $insertData['nip'] = $data['nip'];
            $insertData['tarif_tindakandr'] = $totals['tarif_dr'];
            $insertData['tarif_tindakanpr'] = $totals['tarif_pr'];
            $insertData['biaya_rawat'] = $totals['total'];
            RawatInapDrPr::create($insertData);
        }

        return ['totals' => $totals];
    }

    private function deleteTindakan(array $data)
    {
        $where = [
            'no_rawat' => $data['no_rawat'],
            'kd_jenis_prw' => $data['kd_jenis_prw'],
            'tgl_perawatan' => $data['tgl_perawatan'],
            'jam_rawat' => $data['jam_rawat'],
        ];

        $pelaksana = $data['pelaksana'];
        $row = null;

        if ($pelaksana == 'Dokter') {
            $row = RawatInapDr::where($where)->first();
            $data['pelaksana'] = 'dr'; // normalize for createTampJurnal
        } elseif ($pelaksana == 'Petugas') {
            $row = RawatInapPr::where($where)->first();
            $data['pelaksana'] = 'pr';
        } elseif ($pelaksana == 'Dokter & Petugas') {
            $row = RawatInapDrPr::where($where)->first();
            $data['pelaksana'] = 'drpr';
        }

        if (!$row) throw new Exception("Data tindakan tidak ditemukan");

        $totals = [
            'material' => $row->material,
            'bhp' => $row->bhp,
            'tarif_dr' => $row->tarif_tindakandr ?? 0,
            'tarif_pr' => $row->tarif_tindakanpr ?? 0,
            'kso' => $row->kso,
            'menejemen' => $row->menejemen,
            'total' => $row->biaya_rawat
        ];

        // Delete using query builder to avoid 'id' column issue with composite keys
        $tableName = '';
        if ($pelaksana == 'Dokter') $tableName = 'rawat_inap_dr';
        elseif ($pelaksana == 'Petugas') $tableName = 'rawat_inap_pr';
        elseif ($pelaksana == 'Dokter & Petugas') $tableName = 'rawat_inap_drpr';

        DB::table($tableName)->where($where)->delete();
        
        return $totals;
    }

    private function createTampJurnal($rek, $totals, $pelaksana, $reverse = false)
    {
        DB::table('tampjurnal')->delete();

        $insertJurnal = function ($kd, $nm, $debet, $kredit) use ($reverse) {
            if ($reverse) [$debet, $kredit] = [$kredit, $debet];
            DB::table('tampjurnal')->insert([
                'kd_rek' => $kd, 'nm_rek' => $nm, 'debet' => $debet, 'kredit' => $kredit
            ]);
        };

        if ($totals['total'] > 0) {
            $insertJurnal($rek->Suspen_Piutang_Tindakan_Ranap, 'Suspen Piutang Tindakan Ranap', $totals['total'], 0);
            $insertJurnal($rek->Tindakan_Ranap, 'Pendapatan Tindakan Rawat Inap', 0, $totals['total']);
        }

        if ($totals['tarif_dr'] > 0) {
            $insertJurnal($rek->Beban_Jasa_Medik_Dokter_Tindakan_Ranap, 'Beban Jasa Medik Dokter Tindakan Ranap', $totals['tarif_dr'], 0);
            $insertJurnal($rek->Utang_Jasa_Medik_Dokter_Tindakan_Ranap, 'Utang Jasa Medik Dokter Tindakan Ranap', 0, $totals['tarif_dr']);
        }

        if ($totals['tarif_pr'] > 0) {
            $insertJurnal($rek->Beban_Jasa_Medik_Paramedis_Tindakan_Ranap, 'Beban Jasa Medik Paramedis Tindakan Ranap', $totals['tarif_pr'], 0);
            $insertJurnal($rek->Utang_Jasa_Medik_Paramedis_Tindakan_Ranap, 'Utang Jasa Medik Paramedis Tindakan Ranap', 0, $totals['tarif_pr']);
        }

        if ($totals['kso'] > 0) {
            $insertJurnal($rek->Beban_KSO_Tindakan_Ranap, 'Beban KSO Tindakan Ranap', $totals['kso'], 0);
            $insertJurnal($rek->Utang_KSO_Tindakan_Ranap, 'Utang KSO Tindakan Ranap', 0, $totals['kso']);
        }

        if ($totals['material'] > 0) {
            $insertJurnal($rek->Beban_Jasa_Sarana_Tindakan_Ranap, 'Beban Jasa Sarana Tindakan Ranap', $totals['material'], 0);
            $insertJurnal($rek->Utang_Jasa_Sarana_Tindakan_Ranap, 'Utang Jasa Sarana Tindakan Ranap', 0, $totals['material']);
        }

        if ($totals['bhp'] > 0) {
            $insertJurnal($rek->HPP_BHP_Tindakan_Ranap, 'HPP BHP Tindakan Ranap', $totals['bhp'], 0);
            $insertJurnal($rek->Persediaan_BHP_Tindakan_Ranap, 'Persediaan BHP Tindakan Ranap', 0, $totals['bhp']);
        }

        if ($totals['menejemen'] > 0) {
            $insertJurnal($rek->Beban_Jasa_Menejemen_Tindakan_Ranap, 'Beban Jasa Menejemen Tindakan Ranap', $totals['menejemen'], 0);
            $insertJurnal($rek->Utang_Jasa_Menejemen_Tindakan_Ranap, 'Utang Jasa Menejemen Tindakan Ranap', 0, $totals['menejemen']);
        }
    }

    private function writeOnJurnal(array $data, bool $isRevert = false)
    {
        $keterangan = ($isRevert ? 'PEMBATALAN ' : '') . 'TINDAKAN RAWAT INAP PASIEN ' . ($data['no_rkm_medis'] ?? '') . ' ' . ($data['nm_pasien'] ?? '') . ' DIPOSTING OLEH ' . (session()->get('pegawai')->nama ?? 'ADMIN');

        $no_jurnal = $this->generateNoJurnal();
        
        DB::table('jurnal')->insert([
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => date('Y-m-d'),
            'jam_jurnal' => date('H:i:s'),
            'no_bukti' => $data['no_rawat'],
            'jenis' => 'U',
            'keterangan' => $keterangan,
        ]);

        $tamp = DB::table('tampjurnal')->get();
        foreach ($tamp as $item) {
            DB::table('detailjurnal')->insert([
                'no_jurnal' => $no_jurnal,
                'kd_rek' => $item->kd_rek,
                'debet' => $item->debet,
                'kredit' => $item->kredit,
            ]);
        }
    }

    private function generateNoJurnal()
    {
        $date = date('Y-m-d');
        $count = DB::table('jurnal')->whereDate('tgl_jurnal', $date)->count();
        return 'JR' . date('Ymd') . str_pad($count + 1, 6, '0', STR_PAD_LEFT);
    }
}
