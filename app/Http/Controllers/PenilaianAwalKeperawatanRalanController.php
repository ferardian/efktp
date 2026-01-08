<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\PenilaianAwalKeperawatanRalan;

class PenilaianAwalKeperawatanRalanController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)
            ->with('regPeriksa', 'pegawai')->first();
        return response()->json($penilaian);
    }

    function createPenilaian(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'tanggal' => date('Y-m-d H:i:s'),
            'td' => $request->td,
            'nadi' => $request->nadi,
            'rr' => $request->rr,
            'suhu' => $request->suhu,
            'gcs' => '-',
            'bb' => $request->bb,
            'tb' => $request->tb,
            'bmi' => $request->bmi,
            'keluhan_utama' => $request->keluhan_utama,
            'rpd' => '-',
            'rpk' => '-',
            'rpo' => $request->rpo,
            'alergi' => $request->alergi,
            'alat_bantu' => $request->alat_bantu,
            'ket_bantu' => $request->ket_bantu,
            'prothesa' => '-',
            'ket_pro' => '-',
            'adl' => $request->adl,
            'status_psiko' => $request->status_psiko,
            'ket_psiko' => '-',
            'hub_keluarga' => $request->hub_keluarga,
            'tinggal_dengan' => '-',
            'ekonomi' => $request->ekonomi,
            'budaya' => '-',
            'ket_budaya' => $request->ket_budaya,
            'edukasi' => '-',
            'ket_edukasi' => 'Pasien',
            'berjalan_a' => 'Ya',
            'berjalan_b' => 'Ya',
            'berjalan_c' => 'Ya',
            'hasil' => 'Tidak beresiko (tidak ditemukan a dan b)',
            'lapor' => 'Ya',
            'ket_lapor' => '-',
            'sg1' => $request->sg1,
            'nilai1' => $request->n1,
            'sg2' => $request->sg2,
            'nilai2' => $request->n2,
            'total_hasil' => $request->total_hasil,
            'nyeri' => 'Tidak Ada Nyeri',
            'provokes' => 'Lain-lain',
            'ket_provokes' => '-',
            'quality' => 'Lain-lain',
            'ket_quality' => '-',
            'lokasi' => '-',
            'menyebar' => 'Tidak',
            'skala_nyeri' => '0',
            'durasi' => '-',
            'nyeri_hilang' => 'Istirahat',
            'ket_nyeri' => '-',
            'pada_dokter' => 'Ya',
            'ket_dokter' => '-',
            'rencana' => '-',
            'nip' => session()->get('pegawai')->nik,

        ];
        $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)->first();
        if ($penilaian) {
            return $this->updatePenilaian($request);
        }

        try {
            $penilaian = PenilaianAwalKeperawatanRalan::create($data);
            if ($penilaian) {
                $this->insertSql(new PenilaianAwalKeperawatanRalan(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function updatePenilaian(Request $request)
    {
        $data = [
            'td' => $request->td,
            'nadi' => $request->nadi,
            'rr' => $request->rr,
            'suhu' => $request->suhu,
            'bb' => $request->bb,
            'tb' => $request->tb,
            'bmi' => $request->bmi,
            'keluhan_utama' => $request->keluhan_utama,
            'rpo' => $request->rpo,
            'alergi' => $request->alergi,
            'alat_bantu' => $request->alat_bantu,
            'ket_bantu' => $request->ket_bantu,
            'adl' => $request->adl,
            'status_psiko' => $request->status_psiko,
            'hub_keluarga' => $request->hub_keluarga,
            'ekonomi' => $request->ekonomi,
            'ket_budaya' => $request->ket_budaya,
            'sg1' => $request->sg1,
            'nilai1' => $request->n1,
            'sg2' => $request->sg2,
            'nilai2' => $request->n2,
            'total_hasil' => $request->total_hasil,
        ];
        try {
            $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)->update($data);
            if ($penilaian) {
                $this->updateSql(new PenilaianAwalKeperawatanRalan(), $data, ['no_rawat' => $request->no_rawat]);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function print(Request $request)
    {
        $data = PenilaianAwalKeperawatanRalan::with('regPeriksa.pasien', 'pegawai', 'skrining')->where('no_rawat', $request->no_rawat)->first();
        $setting = Setting::first();

        $pdf = PDF::loadView('content.print.penilaianAwal', ['data' => $data, 'setting' => $setting])
            ->setPaper("a4")->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        return $pdf->stream('penilaian-awal-keperawatan.pdf');
    }
}
