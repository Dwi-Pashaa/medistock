<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            // "lihat roles",
            // "tambah roles",
            // "edit roles",
            // "hapus roles",
            // "lihat user",
            // "tambah user",
            // "edit user",
            // "hapus user",
            "lihat supplier",
            "tambah supplier",
            "edit supplier",
            "hapus supplier",
            "lihat purchase",
            "tambah purchase",
            "edit purchase",
            "hapus purchase",
            "lihat customer",
            "tambah customer",
            "edit customer",
            "hapus customer",
            "lihat product",
            "tambah product",
            "edit product",
            "hapus product",
            "lihat received",
            "tambah received",
            "edit received",
            "hapus received",
            "lihat return",
            "tambah return",
            "edit return",
            "hapus return",
            "lihat sale",
            "tambah sale",
            "edit sale",
            "hapus sale",
            "lihat report",
            "unduh report",
        ];

        foreach ($permission as $value) {
            Permission::create(['name' => $value]);
        }
    }
}
