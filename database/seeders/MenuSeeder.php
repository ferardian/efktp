<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up tables first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menu_role')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $menus = [
            // position = navbar
            [
                'id' => 1, 'name' => 'Beranda', 'url' => '/', 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>', 
                'parent_id' => null, 'order_num' => 1, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']
            ],
            [
                'id' => 2, 'name' => 'Registrasi', 'url' => '/registrasi', 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M10 18l5 -5a1.414 1.414 0 0 0 -2 -2l-5 5v2h2z"></path></svg>', 
                'parent_id' => null, 'order_num' => 2, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'petugas', 'owner']
            ],
            [
                'id' => 3, 'name' => 'Rawat Inap', 'url' => '/ranap', 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bed-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 6a1 1 0 0 1 .993 .883l.007 .117v6h6v-5a1 1 0 0 1 .883 -.993l.117 -.007h8a3 3 0 0 1 2.995 2.824l.005 .176v8a1 1 0 0 1 -1.993 .117l-.007 -.117v-3h-16v3a1 1 0 0 1 -1.993 .117l-.007 -.117v-11a1 1 0 0 1 1 -1z" stroke-width="0" fill="currentColor"/><path d="M7 8a2 2 0 1 1 -1.995 2.15l-.005 -.15l.005 -.15a2 2 0 0 1 1.995 -1.85z" stroke-width="0" fill="currentColor"/></svg>', 
                'parent_id' => null, 'order_num' => 3, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'petugas', 'owner']
            ],
            [
                'id' => 4, 'name' => 'Farmasi', 'url' => null, 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pill" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.5 12.5l8 -8a4.94 4.94 0 0 1 7 7l-8 8a4.94 4.94 0 0 1 -7 -7"/><path d="M8.5 8.5l7 7"/></svg>', 
                'parent_id' => null, 'order_num' => 4, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'petugas', 'owner']
            ],
            [
                'id' => 5, 'name' => 'Pcare', 'url' => null, 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plug-connected" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12l5 5l-1.5 1.5a3.536 3.536 0 1 1 -5 -5l1.5 -1.5z"/><path d="M17 12l-5 -5l1.5 -1.5a3.536 3.536 0 1 1 5 5l-1.5 1.5z"/><path d="M3 21l2.5 -2.5"/><path d="M18.5 5.5l2.5 -2.5"/><path d="M10 11l-2 2"/><path d="M13 14l-2 2"/></svg>', 
                'parent_id' => null, 'order_num' => 5, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'petugas', 'owner']
            ],
            [
                'id' => 6, 'name' => 'KYC', 'url' => 'kyc', 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z"/><path d="M15 9h.01"/></svg>', 
                'parent_id' => null, 'order_num' => 6, 'target' => '_blank', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']
            ],
            [
                'id' => 7, 'name' => 'Antrean', 'url' => null, 
                'icon' => '<i class="ti ti-screen-share fw-2"></i>', 
                'parent_id' => null, 'order_num' => 7, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']
            ],

            // submenus under Farmasi (parent_id = 4)
            ['id' => 8, 'name' => 'Obat & BHP', 'url' => 'farmasi/obat', 'icon' => null, 'parent_id' => 4, 'order_num' => 1, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'petugas', 'owner']],
            ['id' => 9, 'name' => 'Penerimaan Obat & BHP', 'url' => 'farmasi/penerimaan', 'icon' => null, 'parent_id' => 4, 'order_num' => 2, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'owner']],
            ['id' => 10, 'name' => 'Stok Opname', 'url' => 'farmasi/opname', 'icon' => null, 'parent_id' => 4, 'order_num' => 3, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'owner']],
            ['id' => 11, 'name' => 'Template Racikan', 'url' => 'farmasi/racik/template', 'icon' => null, 'parent_id' => 4, 'order_num' => 4, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'dokter', 'owner']],
            ['id' => 12, 'name' => 'Resep Obat', 'url' => 'farmasi/resep', 'icon' => null, 'parent_id' => 4, 'order_num' => 5, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'dokter', 'petugas', 'owner']],
            ['id' => 13, 'name' => 'Rekap Resep', 'url' => 'farmasi/resep/rekap', 'icon' => null, 'parent_id' => 4, 'order_num' => 6, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'owner']],
            ['id' => 14, 'name' => 'Paket Obat', 'url' => 'master/paket-obat', 'icon' => null, 'parent_id' => 4, 'order_num' => 7, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'apoteker', 'owner']],

            // submenus under Pcare (parent_id = 5)
            ['id' => 15, 'name' => 'Pendaftaran', 'url' => 'pcare/pendaftaran', 'icon' => null, 'parent_id' => 5, 'order_num' => 1, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 16, 'name' => 'Kunjungan', 'url' => 'pcare/kunjungan', 'icon' => null, 'parent_id' => 5, 'order_num' => 2, 'target' => '_self', 'position' => 'navbar', 'roles' => ['admin', 'petugas', 'owner']],

            // submenus under Antrean (parent_id = 7)
            ['id' => 17, 'name' => 'Poliklinik', 'url' => 'antrean/poliklinik', 'icon' => null, 'parent_id' => 7, 'order_num' => 1, 'target' => '_blank', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']],
            ['id' => 18, 'name' => 'Poliklinik v2', 'url' => 'antrean/poliklinik/v2', 'icon' => null, 'parent_id' => 7, 'order_num' => 2, 'target' => '_blank', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']],
            ['id' => 19, 'name' => 'Farmasi', 'url' => 'antrean/farmasi', 'icon' => null, 'parent_id' => 7, 'order_num' => 3, 'target' => '_blank', 'position' => 'navbar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']],

            // position = sidebar (offcanvas)
            [
                'id' => 20, 'name' => 'Home', 'url' => './', 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l-2 0l9 -9l9 9l-2 0"></path><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path></svg>', 
                'parent_id' => null, 'order_num' => 1, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'dokter', 'apoteker', 'petugas', 'owner']
            ],
            [
                'id' => 21, 'name' => 'Penunjang Medis', 'url' => null, 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path><path d="M12 12l8 -4.5"></path><path d="M12 12l0 9"></path><path d="M12 12l-8 -4.5"></path><path d="M16 5.25l-8 4.5"></path></svg>', 
                'parent_id' => null, 'order_num' => 2, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'apoteker', 'petugas', 'dokter', 'owner']
            ],
            [
                'id' => 22, 'name' => 'SATU SEHAT', 'url' => null, 
                'icon' => '<i class="ti ti-heart-rate-monitor"></i>', 
                'parent_id' => null, 'order_num' => 3, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']
            ],
            [
                'id' => 23, 'name' => 'Master', 'url' => null, 
                'icon' => '<i class="ti ti-server-cog"></i>', 
                'parent_id' => null, 'order_num' => 4, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']
            ],

            // submenus under Penunjang Medis (parent_id = 21)
            ['id' => 24, 'name' => 'Obat & BHP', 'url' => 'farmasi/obat', 'icon' => null, 'parent_id' => 21, 'order_num' => 1, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'apoteker', 'petugas', 'owner']],
            ['id' => 25, 'name' => 'Laboratorium', 'url' => null, 'icon' => null, 'parent_id' => 21, 'order_num' => 2, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'dokter', 'owner']],
            ['id' => 26, 'name' => 'Radiologi', 'url' => '#', 'icon' => null, 'parent_id' => 21, 'order_num' => 3, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'dokter', 'owner']],

            // submenus under Laboratorium (parent_id = 25)
            ['id' => 27, 'name' => 'Permintaan Lab PK', 'url' => '/lab/permintaan', 'icon' => null, 'parent_id' => 25, 'order_num' => 1, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'dokter', 'owner']],
            ['id' => 28, 'name' => 'Pemeriksaan PK', 'url' => '#', 'icon' => null, 'parent_id' => 25, 'order_num' => 2, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'dokter', 'owner']],

            // submenus under SATU SEHAT (parent_id = 22)
            ['id' => 29, 'name' => 'Get Pasien', 'url' => 'satusehat/pasien', 'icon' => null, 'parent_id' => 22, 'order_num' => 1, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 30, 'name' => 'Mapping Organisasi', 'url' => 'satusehat/mapping/organisasi', 'icon' => null, 'parent_id' => 22, 'order_num' => 2, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 31, 'name' => 'Mapping Lokasi', 'url' => 'satusehat/mapping/lokasi', 'icon' => null, 'parent_id' => 22, 'order_num' => 3, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 32, 'name' => 'Medication', 'url' => 'satusehat/medication', 'icon' => null, 'parent_id' => 22, 'order_num' => 4, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 33, 'name' => 'Encounter', 'url' => 'satusehat/encounter', 'icon' => null, 'parent_id' => 22, 'order_num' => 5, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 34, 'name' => 'Condition', 'url' => 'satusehat/condition', 'icon' => null, 'parent_id' => 22, 'order_num' => 6, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 35, 'name' => 'Observation TTV', 'url' => 'satusehat/observation-ttv', 'icon' => null, 'parent_id' => 22, 'order_num' => 7, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],

            // submenus under Master (parent_id = 23)
            ['id' => 36, 'name' => 'Paket Obat', 'url' => 'master/paket-obat', 'icon' => null, 'parent_id' => 23, 'order_num' => 1, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'apoteker', 'owner']],
            ['id' => 37, 'name' => 'Jadwal Praktek', 'url' => 'master/jadwal', 'icon' => null, 'parent_id' => 23, 'order_num' => 2, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'dokter', 'owner']],
            ['id' => 38, 'name' => 'Data Dokter', 'url' => 'master/dokter', 'icon' => null, 'parent_id' => 23, 'order_num' => 3, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 39, 'name' => 'Data Petugas', 'url' => 'master/petugas', 'icon' => null, 'parent_id' => 23, 'order_num' => 4, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 40, 'name' => 'Tarif Rawat Jalan', 'url' => 'master/tarif-ralan', 'icon' => null, 'parent_id' => 23, 'order_num' => 5, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 41, 'name' => 'Poliklinik', 'url' => 'master/poliklinik', 'icon' => null, 'parent_id' => 23, 'order_num' => 6, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'petugas', 'owner']],
            ['id' => 42, 'name' => 'Set User', 'url' => 'master/user', 'icon' => null, 'parent_id' => 23, 'order_num' => 7, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'owner']],
            ['id' => 43, 'name' => 'Hak Akses Menu', 'url' => 'master/menu', 'icon' => null, 'parent_id' => 23, 'order_num' => 8, 'target' => '_self', 'position' => 'sidebar', 'roles' => ['admin', 'owner']],
        ];

        // Seed menus and menu_role relations
        foreach ($menus as $m) {
            $roles = $m['roles'];
            unset($m['roles']);

            Menu::create($m);

            foreach ($roles as $r) {
                MenuRole::create([
                    'menu_id' => $m['id'],
                    'role' => $r
                ]);
            }
        }

        // Auto-mapping existing users to user_roles
        // We check if the user table exists and has rows
        if (\Illuminate\Support\Facades\Schema::hasTable('user')) {
            $oldUsers = DB::table('user')
                ->select(DB::raw("AES_DECRYPT(id_user, 'nur') as username"))
                ->get();

            foreach ($oldUsers as $user) {
                $username = $user->username;
                if (empty($username)) continue;

                // Lookup NIK in pegawai
                $pegawai = DB::table('pegawai')
                    ->where('nik', $username)
                    ->first();

                $role = 'petugas'; // default fallback

                if ($username === 'admin') {
                    $role = 'admin';
                } elseif ($pegawai) {
                    if ($pegawai->dokter ?? false || DB::table('dokter')->where('kd_dokter', $username)->exists()) {
                        $role = 'dokter';
                    } else {
                        $jabatan = strtolower($pegawai->jbtn ?? '');
                        $departemen = strtolower($pegawai->departemen ?? '');

                        if (
                            str_contains($jabatan, 'apotek') || 
                            str_contains($jabatan, 'farmasi') || 
                            str_contains($departemen, 'apotek') || 
                            str_contains($departemen, 'farmasi')
                        ) {
                            $role = 'apoteker';
                        }
                    }
                }

                // If user doesn't already have a role mapped, insert it
                UserRole::updateOrCreate(
                    ['username' => $username],
                    ['role' => $role]
                );
            }
        }
    }
}
