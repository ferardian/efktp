<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        self::ensureMenusExist();
        return view('content.master.menu');
    }

    public function getPermissions(Request $request)
    {
        self::ensureMenusExist();
        $request->validate([
            'role' => 'required|string',
        ]);

        $role = $request->role;

        // Fetch all root menus grouped by position
        $menus = Menu::whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->orderBy('order_num', 'asc')
            ->with([
                'submenus' => function($q) {
                    $q->orderBy('order_num', 'asc');
                },
                'submenus.submenus' => function($q) {
                    $q->orderBy('order_num', 'asc');
                }
            ])
            ->get();

        // Get currently assigned menu IDs for the role
        $assignedMenuIds = MenuRole::where('role', $role)
            ->pluck('menu_id')
            ->toArray();

        return response()->json([
            'menus' => $menus,
            'assigned' => $assignedMenuIds
        ]);
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'integer|exists:menus,id',
        ]);

        $role = $request->role;
        $menuIds = $request->menu_ids ?? [];

        try {
            DB::transaction(function () use ($role, $menuIds) {
                // Delete existing mappings
                MenuRole::where('role', $role)->delete();

                // Insert new mappings
                $data = [];
                foreach ($menuIds as $menuId) {
                    $data[] = [
                        'menu_id' => $menuId,
                        'role' => $role
                    ];
                }

                if (!empty($data)) {
                    MenuRole::insert($data);
                }
            });

            return response()->json([
                'message' => 'Hak akses menu berhasil diperbarui untuk role: ' . ucfirst($role)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui hak akses menu: ' . $e->getMessage()
            ], 500);
        }
    }

    public static function ensureMenusExist()
    {
        try {
            $pcareParent = Menu::where('name', 'Pcare')->whereNull('parent_id')->first();
            if ($pcareParent) {
                $existing = Menu::where('url', 'pcare/kelompok')->first();
                if (!$existing) {
                    $newMenu = Menu::create([
                        'name'      => 'Kegiatan & Club Prolanis',
                        'url'       => 'pcare/kelompok',
                        'icon'      => null,
                        'parent_id' => $pcareParent->id,
                        'order_num' => 3,
                        'target'    => '_self',
                        'position'  => 'navbar',
                    ]);

                    $roles = ['admin', 'dokter', 'petugas', 'owner'];
                    foreach ($roles as $r) {
                        MenuRole::firstOrCreate(['menu_id' => $newMenu->id, 'role' => $r]);
                    }
                }
            }

            // Ensure Farmasi submenus: Penjualan Bebas & Data Supplier
            $farmasiParent = Menu::where('name', 'Farmasi')->whereNull('parent_id')->first();
            if ($farmasiParent) {
                $existingPenjualan = Menu::where('url', 'farmasi/penjualan')->first();
                if (!$existingPenjualan) {
                    $newPenjualan = Menu::create([
                        'id'        => 50,
                        'name'      => 'Penjualan Bebas',
                        'url'       => 'farmasi/penjualan',
                        'icon'      => null,
                        'parent_id' => $farmasiParent->id,
                        'order_num' => 3,
                        'target'    => '_self',
                        'position'  => 'navbar',
                    ]);

                    $roles = ['admin', 'apoteker', 'petugas', 'owner'];
                    foreach ($roles as $r) {
                        MenuRole::firstOrCreate(['menu_id' => $newPenjualan->id, 'role' => $r]);
                    }
                }

                $existingSuplier = Menu::where('url', 'farmasi/suplier')->first();
                if (!$existingSuplier) {
                    $newSuplier = Menu::create([
                        'id'        => 49,
                        'name'      => 'Data Supplier',
                        'url'       => 'farmasi/suplier',
                        'icon'      => null,
                        'parent_id' => $farmasiParent->id,
                        'order_num' => 4,
                        'target'    => '_self',
                        'position'  => 'navbar',
                    ]);

                    $roles = ['admin', 'apoteker', 'owner'];
                    foreach ($roles as $r) {
                        MenuRole::firstOrCreate(['menu_id' => $newSuplier->id, 'role' => $r]);
                    }
                }

                $existingSetHarga = Menu::where('url', 'farmasi/set-harga')->first();
                if (!$existingSetHarga) {
                    $newSetHarga = Menu::create([
                        'name'      => 'Set Harga Obat',
                        'url'       => 'farmasi/set-harga',
                        'icon'      => null,
                        'parent_id' => $farmasiParent->id,
                        'order_num' => 6,
                        'target'    => '_self',
                        'position'  => 'navbar',
                    ]);

                    $roles = ['admin', 'apoteker', 'owner'];
                    foreach ($roles as $r) {
                        MenuRole::firstOrCreate(['menu_id' => $newSetHarga->id, 'role' => $r]);
                    }
                }

                $existingLapPenjualan = Menu::where('url', 'farmasi/laporan-penjualan-item')->first();
                if (!$existingLapPenjualan) {
                    $newLapPenjualan = Menu::create([
                        'name'      => 'Laporan Penjualan Obat',
                        'url'       => 'farmasi/laporan-penjualan-item',
                        'icon'      => null,
                        'parent_id' => $farmasiParent->id,
                        'order_num' => 7,
                        'target'    => '_self',
                        'position'  => 'navbar',
                    ]);

                    $roles = ['admin', 'apoteker', 'owner'];
                    foreach ($roles as $r) {
                        MenuRole::firstOrCreate(['menu_id' => $newLapPenjualan->id, 'role' => $r]);
                    }
                }
            }

            // Ensure Laporan parent & Kunjungan Rawat Jalan menu
            $laporanParent = Menu::where('name', 'Laporan')->whereNull('parent_id')->first();
            if (!$laporanParent) {
                $laporanParent = Menu::create([
                    'name'      => 'Laporan',
                    'url'       => null,
                    'icon'      => '<i class="ti ti-report-medical fs-2"></i>',
                    'parent_id' => null,
                    'order_num' => 9,
                    'target'    => '_self',
                    'position'  => 'navbar',
                ]);

                $roles = ['admin', 'dokter', 'petugas', 'owner', 'apoteker'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $laporanParent->id, 'role' => $r]);
                }
            }

            $existingKunjunganRalan = Menu::where('url', 'laporan/kunjungan-ralan')->first();
            if (!$existingKunjunganRalan && $laporanParent) {
                $newKunjunganRalan = Menu::create([
                    'name'      => 'Kunjungan Rawat Jalan',
                    'url'       => 'laporan/kunjungan-ralan',
                    'icon'      => null,
                    'parent_id' => $laporanParent->id,
                    'order_num' => 1,
                    'target'    => '_self',
                    'position'  => 'navbar',
                ]);

                $roles = ['admin', 'dokter', 'petugas', 'owner'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $newKunjunganRalan->id, 'role' => $r]);
                }
            }

            // Ensure Keuangan parent next to Farmasi (order_num = 5)
            $keuanganParent = Menu::where('name', 'Keuangan')->whereNull('parent_id')->first();
            if (!$keuanganParent) {
                $keuanganParent = Menu::create([
                    'name'      => 'Keuangan',
                    'url'       => 'keuangan',
                    'icon'      => '<i class="ti ti-report-money fs-2"></i>',
                    'parent_id' => null,
                    'order_num' => 5,
                    'target'    => '_self',
                    'position'  => 'navbar',
                ]);
                $roles = ['admin', 'petugas', 'owner'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $keuanganParent->id, 'role' => $r]);
                }
            } else {
                $keuanganParent->update(['order_num' => 5]);
            }

            // Adjust menus after Farmasi (4): Keuangan (5), Pcare (6), KYC (7), Antrean (8), Laporan (9)
            Menu::where('name', 'Pcare')->whereNull('parent_id')->update(['order_num' => 6]);
            Menu::where('name', 'KYC')->whereNull('parent_id')->update(['order_num' => 7]);
            Menu::where('name', 'Antrean')->whereNull('parent_id')->update(['order_num' => 8]);
            Menu::where('name', 'Laporan')->whereNull('parent_id')->update(['order_num' => 9]);

            $existingKasirRalan = Menu::where('url', 'kasir/ralan')->first();
            if (!$existingKasirRalan && $keuanganParent) {
                $newKasirRalan = Menu::create([
                    'name'      => 'Kasir Rawat Jalan',
                    'url'       => 'kasir/ralan',
                    'icon'      => '<i class="ti ti-cash me-1"></i>',
                    'parent_id' => $keuanganParent->id,
                    'order_num' => 1,
                    'target'    => '_self',
                    'position'  => 'navbar',
                ]);

                $roles = ['admin', 'petugas', 'owner', 'dokter', 'kasir'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $newKasirRalan->id, 'role' => $r]);
                }
            } else if ($existingKasirRalan) {
                $existingKasirRalan->update(['order_num' => 1]);
                $roles = ['admin', 'petugas', 'owner', 'dokter', 'kasir'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $existingKasirRalan->id, 'role' => $r]);
                }
            }

            $existingKasirRanap = Menu::where('url', 'kasir/ranap')->first();
            if (!$existingKasirRanap && $keuanganParent) {
                $newKasirRanap = Menu::create([
                    'name'      => 'Kasir Rawat Inap',
                    'url'       => 'kasir/ranap',
                    'icon'      => '<i class="ti ti-bed me-1"></i>',
                    'parent_id' => $keuanganParent->id,
                    'order_num' => 2,
                    'target'    => '_self',
                    'position'  => 'navbar',
                ]);

                $roles = ['admin', 'petugas', 'owner', 'dokter', 'kasir'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $newKasirRanap->id, 'role' => $r]);
                }
            } else if ($existingKasirRanap) {
                $existingKasirRanap->update(['order_num' => 2]);
                $roles = ['admin', 'petugas', 'owner', 'dokter', 'kasir'];
                foreach ($roles as $r) {
                    MenuRole::firstOrCreate(['menu_id' => $existingKasirRanap->id, 'role' => $r]);
                }
            }

            $pembayaranRalan = Menu::where('url', 'keuangan/pembayaran-ralan')->first();
            if ($pembayaranRalan) {
                $pembayaranRalan->update([
                    'order_num' => 3,
                    'name'      => 'Rekap Pembayaran Rawat Jalan',
                ]);
            }

            // Seed default menu mappings for new roles if not yet initialized
            $newRolesDefaults = [
                'perawat' => [
                    Menu::where('url', '/')->value('id'),
                    Menu::where('url', '/registrasi')->value('id'),
                    Menu::where('url', '/ranap')->value('id'),
                    Menu::where('name', 'Antrean')->whereNull('parent_id')->value('id'),
                    Menu::where('url', 'antrean/poliklinik')->value('id'),
                    Menu::where('url', 'antrean/poliklinik/v2')->value('id'),
                ],
                'laborat' => [
                    Menu::where('url', '/')->value('id'),
                    Menu::where('url', '/registrasi')->value('id'),
                    Menu::where('name', 'Penunjang Medis')->whereNull('parent_id')->value('id'),
                    Menu::where('name', 'Laboratorium')->value('id'),
                    Menu::where('url', '/lab/permintaan')->value('id'),
                    Menu::where('name', 'Pemeriksaan PK')->value('id'),
                ],
                'kasir' => [
                    Menu::where('url', '/')->value('id'),
                    Menu::where('url', '/registrasi')->value('id'),
                    Menu::where('name', 'Keuangan')->whereNull('parent_id')->value('id'),
                    Menu::where('url', 'kasir/ralan')->value('id'),
                    Menu::where('url', 'kasir/ranap')->value('id'),
                    Menu::where('url', 'keuangan/pembayaran-ralan')->value('id'),
                ],
                'rekam_medis' => [
                    Menu::where('url', '/')->value('id'),
                    Menu::where('url', '/registrasi')->value('id'),
                    Menu::where('url', '/ranap')->value('id'),
                    Menu::where('name', 'Laporan')->whereNull('parent_id')->value('id'),
                    Menu::where('url', 'laporan/kunjungan-ralan')->value('id'),
                ],
            ];

            foreach ($newRolesDefaults as $roleName => $menuIds) {
                $exists = MenuRole::where('role', $roleName)->exists();
                if (!$exists) {
                    $insertData = [];
                    foreach (array_filter($menuIds) as $mId) {
                        $insertData[] = [
                            'menu_id' => $mId,
                            'role'    => $roleName,
                        ];
                    }
                    if (!empty($insertData)) {
                        MenuRole::insert($insertData);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
