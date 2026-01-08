<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Models\EfktpTindakanResikoJatuh;
use App\Models\PenilaianAwalKeperawatanRalan;
use App\Models\Setting;

class EfktpTindakanResikoJatuhController extends Controller
{
    use Track;

    function get(Request $request)
    {
        $tindakan = EfktpTindakanResikoJatuh::where('no_rawat', $request->no_rawat)->first();
        return response()->json($tindakan);
    }

    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'nip' => session()->get('pegawai')->nik,
            'berjalan_a' => $request->berjalan_a,
            'berjalan_b' => $request->berjalan_b,
            'hasil' => $request->hasil,
            'ket_hasil' => $request->ket_hasil,
            'tindakan' => $request->tindakan,
            'tanggal' => date('Y-m-d H:i:s'),
        ];
        $dataPenilaian = [
            'berjalan_a' => $request->berjalan_a,
            'berjalan_b' => $request->berjalan_b,
            'hasil' => $request->hasil,
        ];

        $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat);
        if ($penilaian->count() == 0) {
            return response()->json('Mohon lakukan penilaian awal rawat jalan', 500);
        }

        $tindakan = EfktpTindakanResikoJatuh::where('no_rawat', $request->no_rawat)->first();
        if ($tindakan) {
            return $this->update($request);
        }

        try {
            $tindakan = EfktpTindakanResikoJatuh::create($data);
            if ($penilaian->first()) {
                $penilaian = $penilaian->update($dataPenilaian);
                $this->insertSql(new EfktpTindakanResikoJatuh(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function update(Request $request)
    {
        $data = [
            'berjalan_a' => $request->berjalan_a,
            'berjalan_b' => $request->berjalan_b,
            'hasil' => $request->hasil,
            'ket_hasil' => $request->ket_hasil,
            'tindakan' => $request->tindakan,
        ];
        $dataPenilaian = [
            'berjalan_a' => $request->berjalan_a,
            'berjalan_b' => $request->berjalan_b,
            'hasil' => $request->hasil,
        ];

        try {
            $tindakan = EfktpTindakanResikoJatuh::where('no_rawat', $request->no_rawat)->update($data);
            if ($tindakan) {
                $penilaian = PenilaianAwalKeperawatanRalan::where('no_rawat', $request->no_rawat)->update($dataPenilaian);
                $this->updateSql(new EfktpTindakanResikoJatuh(), $data, ['no_rawat' => $request->no_rawat]);
            }
            return response()->json('SUKSES UBAH', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function print(Request $request)
    {
        $tindakan = EfktpTindakanResikoJatuh::where('no_rawat', $request->no_rawat)
            ->with('regPeriksa.pasien', 'pegawai')->first();
        $setting = Setting::first();

        $pdf = PDF::loadView('content.print.skriningJatuh', ['data' => $tindakan, 'setting' => $setting])
            ->setPaper("a4")->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
        return $pdf->stream('skrining-jatuh.pdf');
    }
}
