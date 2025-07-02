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
            'stock' => 80,
            'minimum_stock' => 50,
        ]);
        Material::create([
            'material_code' => '004',
            'material_name' => 'kopyor panggang',
            'type' => 'finish_good',
            'unit' => 'kg',
            'stock' => 100,
            'minimum_stock' => 10,
        ]);
        Material::create([
            'material_code' => '002',
            'material_name' => 'Pia Kacang',
            'type' => 'finish_good',
            'unit' => 'pcs',
            'stock' => 100,
            'minimum_stock' => 20,
        ]);
        Material::create([
            'material_code' => '003',
            'material_name' => 'odading goreng',
            'type' => 'finish_good',
            'unit' => 'pcs',
            'stock' => 100,
            'minimum_stock' => 40,
        ]);

        Material::create([
            'material_code' => 'RAW003',
            'material_name' => 'Kacang Tanah',
            'type' => 'raw',
            'unit' => 'kg',
            'stock' => 25,
            'minimum_stock' => 5,
        ]);

        Material::create([
            'material_code' => 'RAW004',
            'material_name' => 'Minyak Goreng',
            'type' => 'raw',
            'unit' => 'liter',
            'stock' => 20,
            'minimum_stock' => 5,
        ]);
        Material::create([
            'material_code' => 'RAW005',
            'material_name' => 'Garam',
            'type' => 'raw',
            'unit' => 'kg',
            'stock' => 5,
            'minimum_stock' => 1,
        ]);

        Material::create([
            'material_code' => 'RAW006',
            'material_name' => 'Air',
            'type' => 'raw',
            'unit' => 'liter',
            'stock' => 50,
            'minimum_stock' => 10,
        ]);

        Material::create([
            'material_code' => 'RAW007',
            'material_name' => 'Margarine',
            'type' => 'raw',
            'unit' => 'kg',
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        Material::create([
            'material_code' => 'RAW008',
            'material_name' => 'Telur',
            'type' => 'raw',
            'unit' => 'pcs',
            'stock' => 100,
            'minimum_stock' => 20,
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
