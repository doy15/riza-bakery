<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Material;
use App\Models\Line;
use App\Models\Shift;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nik' => '1',
            'name' => 'Operator Produksi',
            'role' => 'produksi',
            'password' => bcrypt('1'),
        ]);

        User::create([
            'nik' => '2',
            'name' => 'Quality Control',
            'role' => 'qc',
            'password' => bcrypt('2'),
        ]);

        User::create([
            'nik' => '3',
            'name' => 'Gudang',
            'role' => 'gudang',
            'password' => bcrypt('3'),
        ]);

        User::create([
            'nik' => '4',
            'name' => 'Manager',
            'role' => 'admin',
            'password' => bcrypt('4'),
        ]);

        Material::create([
            'material_code' => '001',
            'material_name' => 'Tepung',
            'type' => 'raw',
            'unit' => 'kg',
            'stock' => 100,
            'minimum_stock' => 50,
        ]);

        Line::create([
            'line_code' => '001',
            'line_name' => 'ROTI001',
            'cycle_time' => 20,
            'target' => 1440,
        ]);

        Shift::create([
            'name' => 'Non Shift',
            'start_time' => '05:00',
            'end_time' => '14:00',
        ]);
    }
}
