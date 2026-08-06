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
        $this->ensureProlanisMenu();
        return view('content.master.menu');
    }

    public function getPermissions(Request $request)
    {
        $this->ensureProlanisMenu();
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

    private function ensureProlanisMenu()
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
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
