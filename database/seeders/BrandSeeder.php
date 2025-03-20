<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'ASUS', 'slug' => 'asus'],
            ['name' => 'BIOSTAR', 'slug' => 'biostar'],
            ['name' => 'AORUS', 'slug' => 'aorus'],
            ['name' => 'ASROCK', 'slug' => 'asrock'],
            ['name' => 'GIGABYTE', 'slug' => 'gigabyte'],
            ['name' => 'MSI', 'slug' => 'msi'],
            ['name' => 'RUIX', 'slug' => 'ruix'],
            ['name' => 'CYBORG', 'slug' => 'cyborg'],
            ['name' => 'DARTH', 'slug' => 'darth'],
            ['name' => 'INPLAY', 'slug' => 'inplay'],
            ['name' => 'KEYTECH', 'slug' => 'keytech'],
            ['name' => 'LENOVO', 'slug' => 'lenovo'],
            ['name' => 'GEFORCE', 'slug' => 'geforce'],
            ['name' => 'NEXION', 'slug' => 'nexion'],
            ['name' => 'DEEPCOOL', 'slug' => 'deepcool'],
            ['name' => 'TOSHIBA', 'slug' => 'toshiba'],
            ['name' => 'NVISION', 'slug' => 'nvision'],
            ['name' => 'VIEWPOINT', 'slug' => 'viewpoint'],
            ['name' => 'YGT', 'slug' => 'ygt'],
            ['name' => 'SAMSUNG', 'slug' => 'samsung'],
            ['name' => 'EPSON', 'slug' => 'epson'],
            ['name' => 'AMD', 'slug' => 'amd'],
            ['name' => 'CORSAIR', 'slug' => 'corsair'],
            ['name' => 'RAMSTA', 'slug' => 'ramsta'],
            ['name' => 'ROG', 'slug' => 'rog'],
            ['name' => 'DELTA FORCE', 'slug' => 'delta-force'],
            ['name' => 'KINGBANK', 'slug' => 'kingbank'],
            ['name' => 'KINGSPEC', 'slug' => 'kingspec'],
            ['name' => 'TEAMELITE', 'slug' => 'teamelite'],
            ['name' => 'SANDISK', 'slug' => 'sandisk'],
            ['name' => 'DELL', 'slug' => 'dell'],
            ['name' => 'VIEWSONIC', 'slug' => 'viewsonic'],
            ['name' => 'HIKVISION', 'slug' => 'hikvision'],
            ['name' => 'WINDOWS', 'slug' => 'windows'],
            ['name' => 'DAHUA', 'slug' => 'dahua'],
            ['name' => 'A4TECH', 'slug' => 'a4tech'],
            ['name' => 'ACER', 'slug' => 'acer'],
            ['name' => 'GENERIC', 'slug' => 'generic'],
            ['name' => 'HUNTKEY', 'slug' => 'huntkey'],
            ['name' => 'KINGSTON', 'slug' => 'kingston']
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
