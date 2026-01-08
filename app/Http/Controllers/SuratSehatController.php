<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Setting;
use App\Models\SuratSehat;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;

class SuratSehatController extends Controller
{
    use Track;

    function get(Request $request)
    {
        $surat = SuratSehat::with(['regPeriksa' => function ($q) {
            return $q->with('pasien', 'pemeriksaanDokter');
        }]);

        if ($request->tgl_pertama && $request->tgl_kedua) {
            $surat = $surat->whereBetween('tanggalsurat', [$request->tgl_pertama, $request->tgl_kedua]);
        } else {
            $surat = $surat->where('tanggalsurat', date('Y-m-d'));
        }

        if ($request->dataTable) {
            return DataTables::of($surat)->make(true);
        }
        $surat = $surat->get();
        return response()->json($surat, 200);
    }

    function getSurat($noSurat)
    {
        $surat = SuratSehat::where('no_surat', $noSurat)->with(['regPeriksa' => function ($q) {
            return $q->with(['pasien' => function ($q) {
                return $q->with('kel', 'kec', 'kab', 'prop');
            }, 'pemeriksaanDokter', 'dokter.pegawai']);
        }])->first();

        return response()->json($surat, 200);
    }

    function setNoSurat(Request $request)
    {
        $tgl_surat = $request->tgl_surat ? $request->tgl_surat :  date('Y-m-d');
        $surat = SuratSehat::select('no_surat')
        ->where('tanggalsurat', date('Y-m-d', strtotime($tgl_surat)))
        ->orderBy('no_surat', 'DESC')->first();
        $strTanggal = date('Ymd', strtotime($tgl_surat));
        if (!$surat) {
            $no = '001';
        } else {
            $noAkhir = substr($surat->no_surat, -3);
            $no = (int)$noAkhir + 1;
            $no = sprintf('%03d', $no);
        }
        $no = "SKD{$strTanggal}{$no}";

        return response()->json($no);
    }


    function print($noSurat)
    {
        $surat = SuratSehat::where('no_surat', $noSurat)->with(['regPeriksa' => function ($q) {
            return $q->with('pasien', 'pemeriksaanDokter', 'dokter.pegawai');
        }])->first();
        $setting = Setting::first();
        $data = [
            'no_surat' => $surat->no_surat,
            'nm_pasien' => $surat->regPeriksa->pasien->nm_pasien,
            'umur' => "{$surat->regPeriksa->umurdaftar} {$surat->regPeriksa->sttsumur}",
            'jk' => $surat->regPeriksa->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan',
            'alamat' => "{$surat->regPeriksa->pasien->alamat}, {$surat->regPeriksa->pasien->kel->nm_kel}, {$surat->regPeriksa->pasien->kec->nm_kec}, {$surat->regPeriksa->pasien->kab->nm_kab}",
            'tanggal' => $surat->tanggalsurat,
            'dokter' => $surat->regPeriksa->dokter->nm_dokter,
            'jabatan' => $surat->regPeriksa->dokter->pegawai->jbtn,
            'sip' => $surat->regPeriksa->dokter->no_ijn_praktek,
            'berat' => $surat->berat,
            'tinggi' => $surat->tinggi,
            'tensi' => $surat->tensi,
            'suhu' => $surat->suhu,
            'butawarna' => $surat->butawarna,
            'kesimpulan' => $surat->kesimpulan,
            'keperluan' => $surat->keperluan,
            'nama_instansi' => $setting->nama_instansi,
            'kabupaten' => $setting->kabupaten,
            'alamat_instansi' => "{$setting->alamat_instansi}, {$setting->kabupaten}, {$setting->propinsi}",
            'kontak' => $setting->kontak,
            'email' => $setting->email,
            'logo' => base64_encode($setting->logo),
        ];

        $pdf = Pdf::loadView('content.print.suratSehat', ['data' => $data])
            ->setPaper('a5', 'potrait')->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);

        return $pdf->stream("{$data['no_surat']}.pdf");
    }

    function create(Request $request)
    {
        $data = [
            'no_surat' => $request->no_surat,
            'no_rawat' => $request->no_rawat,
            'tanggalsurat' => date('Y-m-d', strtotime($request->tanggalsurat)),
            'berat' => $request->berat,
            'tinggi' => $request->tinggi,
            'tensi' => $request->tensi,
            'suhu' => $request->suhu,
            'butawarna' => $request->butawarna,
            'keperluan' => $request->keperluan,
            'kesimpulan' => $request->kesimpulan,
        ];
        $surat = SuratSehat::where('no_surat', $request->no_surat)
            ->orWhere('no_rawat', $request->no_rawat)->first();
        if ($surat) {
            return $this->update($request);
        }
        try {
            $surat = SuratSehat::create($data);
            if ($surat) {
                $this->insertSql(new SuratSehat(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
    function update(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'tanggalsurat' => date('Y-m-d', strtotime($request->tanggalsurat)),
            'berat' => $request->berat,
            'tinggi' => $request->tinggi,
            'tensi' => $request->tensi,
            'suhu' => $request->suhu,
            'butawarna' => $request->butawarna,
            'keperluan' => $request->keperluan,
            'kesimpulan' => $request->kesimpulan,
        ];
        try {
            $surat = SuratSehat::where('no_surat', $request->no_surat)
                ->orWhere('no_rawat', $request->no_rawat)->update($data);
            if ($surat) {
                $this->updateSql(new SuratSehat(), $data, ['no_surat' => $request->no_surat, 'no_rawat' => $request->no_rawat]);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function delete($noSurat)
    {
        try {
            $surat = SuratSehat::where('no_surat', $noSurat)->delete();
            if ($surat) {
                $this->deleteSql(new SuratSehat(), ['no_surat' => $noSurat]);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
}
