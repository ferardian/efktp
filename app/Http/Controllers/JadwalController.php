<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Dokter;
use App\Models\Poliklinik;
use App\Traits\Track;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JadwalController extends Controller
{
    use Track;
    public function index()
    {
        return view('content.master.jadwal');
    }

    public function get(Request $request)
    {
        $jadwal = Jadwal::with(['dokter', 'poliklinik']);

        if ($request->kd_dokter) {
            $jadwal->where('kd_dokter', $request->kd_dokter);
        }

        if ($request->kd_poli) {
            $jadwal->where('kd_poli', $request->kd_poli);
        }

        return DataTables::of($jadwal)->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_dokter' => 'required',
            'hari_kerja' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kd_poli' => 'required',
        ]);

        $data = [
            'kd_dokter' => $request->kd_dokter,
            'hari_kerja' => $request->hari_kerja,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kd_poli' => $request->kd_poli,
            'kuota' => $request->kuota ?? 0,
        ];

        try {
            $clause = [
                'kd_dokter' => $request->kd_dokter,
                'hari_kerja' => $request->hari_kerja,
                'jam_mulai' => $request->jam_mulai
            ];

            $exists = Jadwal::where($clause)->first();

            if ($exists) {
                $exists->update($data);
                $this->updateSql(new Jadwal(), $data, $clause);
            } else {
                Jadwal::create($data);
                $this->insertSql(new Jadwal(), $data);
            }

            return response()->json(['message' => 'Berhasil simpan jadwal']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal simpan jadwal', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $clause = [
                'kd_dokter' => $request->kd_dokter,
                'hari_kerja' => $request->hari_kerja,
                'jam_mulai' => $request->jam_mulai
            ];
            
            Jadwal::where($clause)->delete();
            $this->deleteSql(new Jadwal(), $clause);

            return response()->json(['message' => 'Berhasil hapus jadwal']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal hapus jadwal', 'error' => $e->getMessage()], 500);
        }
    }
}
