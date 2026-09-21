<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Setting;
use App\Models\PengkajianPrimerAbcde;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PengkajianPrimerAbcdeController extends Controller
{
    use Track;

    public function get(Request $request)
    {
        $data = PengkajianPrimerAbcde::where('no_rawat', $request->no_rawat)
            ->with([
                'regPeriksa.pasien',
                'regPeriksa.dokter',
                'regPeriksa.poliklinik',
                'petugas',
                'pegawai',
            ])
            ->first();

        return response()->json($data);
    }

    public function save(Request $request)
    {
        $columns = Schema::getColumnListing('pengkajian_primer_abcde');
        $data = array_intersect_key($request->all(), array_flip($columns));

        if (empty($data['no_rawat'])) {
            return response()->json([
                'message' => 'Gagal menyimpan: No. Rawat tidak boleh kosong.'
            ], 422);
        }

        if (empty($data['tgl_pengkajian'])) {
            $data['tgl_pengkajian'] = date('Y-m-d H:i:s');
        }

        $nip = !empty($data['nip']) ? $data['nip'] : (session()->get('pegawai')->nik ?? '-');
        $data['nip'] = $nip;
        $this->ensurePetugasExists($nip);

        // Encode array fields if received as array
        $arrayFields = [
            'airway_tindakan',
            'breathing_tindakan',
            'circulation_tindakan',
            'disability_tindakan',
            'exposure_tindakan'
        ];

        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }

        try {
            DB::beginTransaction();

            $record = PengkajianPrimerAbcde::updateOrCreate(
                ['no_rawat' => $data['no_rawat']],
                $data
            );

            DB::commit();

            return response()->json([
                'message' => 'Pengkajian Primer A-B-C-D-E berhasil disimpan.',
                'data' => $record,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan data pengkajian primer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        if (empty($request->no_rawat)) {
            return response()->json(['message' => 'No. Rawat tidak valid.'], 422);
        }

        try {
            PengkajianPrimerAbcde::where('no_rawat', $request->no_rawat)->delete();
            return response()->json(['message' => 'Pengkajian Primer A-B-C-D-E berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function print(Request $request)
    {
        $penilaian = PengkajianPrimerAbcde::where('no_rawat', $request->no_rawat)
            ->with([
                'regPeriksa.pasien',
                'regPeriksa.dokter',
                'regPeriksa.poliklinik',
                'petugas',
                'pegawai',
            ])
            ->firstOrFail();

        $setting = Setting::first();

        $pdf = Pdf::loadView('content.print.pengkajianPrimerAbcde', [
            'penilaian' => $penilaian,
            'setting' => $setting,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Pengkajian_Primer_ABCDE_' . str_replace('/', '_', $penilaian->no_rawat) . '.pdf');
    }

    private function ensurePetugasExists($nip)
    {
        if (empty($nip) || $nip === '-') return;

        $exists = DB::table('petugas')->where('nip', $nip)->exists();
        if (!$exists) {
            $pegawai = DB::table('pegawai')->where('nik', $nip)->first();
            if (!$pegawai) {
                DB::table('pegawai')->insert([
                    'id' => DB::table('pegawai')->max('id') + 1,
                    'nik' => $nip,
                    'nama' => 'Petugas ' . $nip,
                    'jk' => 'L',
                    'jbtn' => 'Perawat / Petugas UGD',
                    'jnj_jabatan' => '-',
                    'departemen' => '-',
                    'bidang' => '-',
                    'stts_wp' => '-',
                    'stts_kerja' => '-',
                    'npwp' => '-',
                    'pendidikan' => '-',
                    'gapok' => 0,
                    'tmp_lahir' => '-',
                    'tgl_lahir' => date('Y-m-d'),
                    'alamat' => '-',
                    'kota' => '-',
                    'mulai_kerja' => date('Y-m-d'),
                    'ms_kerja' => '-',
                    'indexins' => '-',
                    'bpd' => '-',
                    'rekening' => '-',
                    'stts_aktif' => 'AKTIF',
                    'wajibmasuk' => 0,
                    'pengurang' => 0,
                    'indek' => 0,
                    'mulai_kontrak' => date('Y-m-d'),
                    'cuti_diambil' => 0,
                    'dankes' => 0,
                    'photo' => '-',
                    'no_ktp' => '-'
                ]);
                $pegawai = DB::table('pegawai')->where('nik', $nip)->first();
            }

            if ($pegawai) {
                DB::table('petugas')->insert([
                    'nip' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                    'jk' => in_array($pegawai->jk, ['L', 'P']) ? $pegawai->jk : 'L',
                    'tmp_lahir' => $pegawai->tmp_lahir ?: '-',
                    'tgl_lahir' => $pegawai->tgl_lahir ?: date('Y-m-d'),
                    'gol_darah' => '-',
                    'agama' => '-',
                    'stts_nikah' => '-',
                    'alamat' => $pegawai->alamat ?: '-',
                    'kd_jbtn' => 'J001',
                    'no_telp' => '-',
                    'status' => '1'
                ]);
            }
        }
    }
}
