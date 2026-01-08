<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Pasien;
use Illuminate\Http\Request;
use App\Models\setNoRkmMedis;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PasienController extends Controller
{
    use Track;
    protected $setNoRkmMedis;

    public function __construct()
    {
        $this->setNoRkmMedis = new setNoRkmMedisController();
    }

    function getByNoka($noKartu)
    {
        $pasien = Pasien::where('no_peserta', $noKartu)->with('regPeriksa', function ($q) use ($noKartu) {
            $q->where('tgl_registrasi', date('Y-m-d'));
        })->first();
        return response()->json($pasien);
    }

    function getRiwayat(Request $request)
    {
        $riwayat = Pasien::where(['no_rkm_medis' => $request->no_rkm_medis])
            ->with(['regPeriksa' => function ($query) {
                return $query->whereIn('stts', ['Sudah', 'Dirujuk'])
                    ->with(['diagnosa.penyakit', 'prosedur.icd9'])
                    ->with(['gigi.hasil'])
                    ->orderBy('tgl_registrasi', 'DESC');
            }])->first();
        return response()->json($riwayat);
    }
    function get(Request $request)
    {
        $pasien = Pasien::where('no_rkm_medis', '!=', '-')
            ->with(['kel', 'kec', 'kab', 'prop', 'sukuBangsa', 'penjab', 'regPeriksa', 'cacatFisik', 'bahasaPasien', 'perusahaanPasien']);
        if ($request->no_rkm_medis) {
            $pasien = $pasien->where('no_rkm_medis', $request->no_rkm_medis)->first();
        }
        if ($request->datatable) {
            return DataTables::of($pasien)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->get('search')['value']) {
                        return $query->where('nm_pasien', 'like', '%'.$request->get('search')['value'].'%')
                            ->orWhere('no_rkm_medis', 'like', "{$request->get('search')['value']}%")
                            ->orWhere('no_peserta', $request->get('search')['value'])
                            ->orWhere('no_ktp', 'like', $request->get('search')['value'].'%')
                            ->orWhereHas('penjab', function ($query) use ($request) {
                                return $query->where('png_jawab', 'like', '%'.$request->get('search')['value'].'%');
                            });
                    }
                })->make(true);
        }
        return response()->json($pasien);
    }
    function create(Request $request)
    {


        $data = [
            'no_rkm_medis' => $request->no_rkm_medis,
            'nm_pasien' => $request->nm_pasien,
            'no_ktp' => $request->no_ktp,
            'jk' => $request->jk,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => date('Y-m-d', strtotime($request->tgl_lahir)),
            'nm_ibu' => $request->nm_ibu,
            'alamat' => $request->alamat,
            'gol_darah' => $request->gol_darah,
            'pekerjaan' => $request->pekerjaan,
            'stts_nikah' => $request->stts_nikah,
            'agama' => $request->agama,
            'tgl_daftar' => date('Y-m-d', strtotime($request->tgl_daftar)),
            'no_tlp' => $request->no_tlp,
            'umur' => $request->umur,
            'pnd' => $request->pnd,
            'keluarga' => $request->keluarga,
            'namakeluarga' => $request->namakeluarga,
            'kd_pj' => $request->kd_pj,
            'no_peserta' => $request->no_peserta,
            'kd_kel' => $request->kd_kel,
            'kd_kec' => $request->kd_kec,
            'kd_kab' => $request->kd_kab,
            'pekerjaanpj' => $request->pekerjaanpj,
            'alamatpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[0] : '-',
            'kelurahanpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[1] : '-',
            'kecamatanpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[2] : '-',
            'kabupatenpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[3] : '-',
            'propinsipj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[3] : '-',
            'perusahaan_pasien' => $request->perusahaan_pasien,
            'suku_bangsa' => $request->suku_bangsa,
            'bahasa_pasien' => $request->bahasa_pasien,
            'cacat_fisik' => $request->cacat_fisik,
            'email' => $request->email,
            'nip' => $request->nip,
            'kd_prop' => $request->kd_prop,
        ];

        $pasien = Pasien::where('no_rkm_medis', $request->no_rkm_medis)->first();
        if ($pasien) {
            return $this->update($request);
        }
        if ($this->isNoPesertaExist($request)) {
            return response()->json('Pasien sudah terdaftar sebelumnya', 409);
        }

        try {
            $pasien = Pasien::create($data);
            if ($pasien) {
                $this->insertSql(new Pasien(), $data);
                $this->setNoRkmMedis->create($request);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }
    function update(Request $request)
    {
        $key = ['no_rkm_medis' => $request->no_rkm_medis];
        $data = [
            'nm_pasien' => $request->nm_pasien,
            'no_ktp' => $request->no_ktp,
            'jk' => $request->jk,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => date('Y-m-d', strtotime($request->tgl_lahir)),
            'nm_ibu' => $request->nm_ibu,
            'alamat' => $request->alamat,
            'gol_darah' => $request->gol_darah,
            'pekerjaan' => $request->pekerjaan,
            'stts_nikah' => $request->stts_nikah,
            'agama' => $request->agama,
            'tgl_daftar' => date('Y-m-d', strtotime($request->tgl_daftar)),
            'no_tlp' => $request->no_tlp,
            'umur' => $request->umur,
            'pnd' => $request->pnd,
            'keluarga' => $request->keluarga,
            'namakeluarga' => $request->namakeluarga,
            'kd_pj' => $request->kd_pj,
            'no_peserta' => $request->no_peserta,
            'kd_kel' => $request->kd_kel,
            'kd_kec' => $request->kd_kec,
            'kd_kab' => $request->kd_kab,
            'pekerjaanpj' => $request->pekerjaanpj,
            'alamatpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[0] : '-',
            'kelurahanpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[1] : '-',
            'kecamatanpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[2] : '-',
            'kabupatenpj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[3] : '-',
            'propinsipj' => $request->alamatpj != '-' ? explode(', ', $request->alamatpj)[3] : '-',
            'perusahaan_pasien' => $request->perusahaan_pasien,
            'suku_bangsa' => $request->suku_bangsa,
            'bahasa_pasien' => $request->bahasa_pasien,
            'cacat_fisik' => $request->cacat_fisik,
            'email' => $request->email,
            'nip' => $request->nip,
            'kd_prop' => $request->kd_prop,
        ];


        try {
            $pasien = Pasien::where($key)->update($data);
            if ($pasien) {
                $this->updateSql(new Pasien(), $key, $data);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
    function updateUmur(Request $request)
    {
        $key = ['no_rkm_medis', $request->no_rkm_medis];
        $umur = ['umur' => $request->umur];
        try {
            $pasien = Pasien::where($key)->update($umur);
            if ($pasien) {
                $this->updateSql(new Pasien(), $umur, $key);
            }
            return response()->json('SUKSES');
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
    function isNoPesertaExist(Request $request)
    {
        if ($request->kd_pj == 'BPJ' || $request->kd_pj == 'BPJS') {
            $pasien = Pasien::where(['no_peserta' => $request->no_peserta]);

            if ($request->no_ktp != '-') {
                $pasien = $pasien->orWhere('no_ktp', $request->no_ktp);
            }

            $pasien = $pasien->first();

            if ($pasien) {
                return true;
            }
        }
    }

    function dataKecamatan(Request $request)
    {
        $kecamatan = Pasien::select('kd_kec', DB::raw('count(*) as count'))
            ->groupBy('kd_kec')
            ->with('kec');
        if ($request->tgl1 || $request->tgl2) {
            $kecamatan = $kecamatan->whereBetween('tgl_daftar', [$request->tgl1, $request->tgl2]);
        } else {
            $kecamatan = $kecamatan->whereMonth('tgl_daftar', date('m'))
                ->whereYear('tgl_daftar', date('Y'));
        }
        $kecamatan = $kecamatan->orderBy('count', 'DESC')->limit(10)->get();
        return response()->json($kecamatan);
    }
    function dataKelurahan(Request $request)
    {
        $data = Pasien::select('kd_kel', DB::raw('count(*) as count'))
            ->groupBy('kd_kel')
            ->with('kel');

        if ($request->tgl1 || $request->tgl2) {
            $data = $data->whereBetween('tgl_daftar', [$request->tgl1, $request->tgl2]);
        } else {
            $data = $data->whereMonth('tgl_daftar', date('m'))
                ->whereYear('tgl_daftar', date('Y'));
        }
        $data = $data->orderBy('count', 'DESC')->limit(10)->get();
        return response()->json($data);
    }
}
