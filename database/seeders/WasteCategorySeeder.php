<?php

namespace Database\Seeders;

use App\Models\WasteCategory;
use Illuminate\Database\Seeder;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Sesuai Katalog Harga Sampah pada dokumen / Figma
        $items = [
            ['name' => 'PET Bersih',     'category' => 'Plastik',             'price' => 2000,  'unit' => 'kg',    'icon' => 'bi-droplet-half'],
            ['name' => 'PET Kotor',      'category' => 'Plastik',             'price' => 500,   'unit' => 'kg',    'icon' => 'bi-droplet'],
            ['name' => 'HDPE',           'category' => 'Plastik',             'price' => 1100,  'unit' => 'kg',    'icon' => 'bi-droplet-fill'],
            ['name' => 'Kerasan',        'category' => 'Plastik',             'price' => 400,   'unit' => 'kg',    'icon' => 'bi-moisture'],
            ['name' => 'Botol Kaca',     'category' => 'Kaca',                'price' => 200,   'unit' => 'kg',    'icon' => 'bi-cup-straw'],
            ['name' => 'Kaleng',         'category' => 'Logam',               'price' => 1100,  'unit' => 'kg',    'icon' => 'bi-cup'],
            ['name' => 'Besi Tipe A',    'category' => 'Logam',               'price' => 3000,  'unit' => 'kg',    'icon' => 'bi-tools'],
            ['name' => 'Besi Tipe B',    'category' => 'Logam',               'price' => 2000,  'unit' => 'kg',    'icon' => 'bi-tools'],
            ['name' => 'Besi Tipe C',    'category' => 'Logam',               'price' => 1500,  'unit' => 'kg',    'icon' => 'bi-tools'],
            ['name' => 'Alumunium',      'category' => 'Logam',               'price' => 8000,  'unit' => 'kg',    'icon' => 'bi-tools'],
            ['name' => 'Elektronik',     'category' => 'Barang Rumah Tangga', 'price' => 10000, 'unit' => 'biji',  'icon' => 'bi-pc-display'],
            ['name' => 'Minyak Jelantah','category' => 'Cairan',              'price' => 5000,  'unit' => 'liter', 'icon' => 'bi-fuel-pump'],
            ['name' => 'Kertas HVS',     'category' => 'Kertas',              'price' => 1500,  'unit' => 'kg',    'icon' => 'bi-file-earmark-text'],
            ['name' => 'Kardus',         'category' => 'Kertas',              'price' => 800,   'unit' => 'kg',    'icon' => 'bi-file-earmark-text'],
            ['name' => 'Duplex',         'category' => 'Kertas',              'price' => 250,   'unit' => 'kg',    'icon' => 'bi-file-earmark-text'],
        ];

        foreach ($items as $item) {
            WasteCategory::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
