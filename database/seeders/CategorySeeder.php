<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'sku' => 'ACC',
                'specifications' => [
                    'Type',
                    'Material',
                    'Compatibility',
                    'Dimensions',
                    'Connector Type'
                ]
            ],
            [
                'name' => 'Motherboard',
                'slug' => 'motherboard',
                'sku' => 'MB',
                'specifications' => [
                    'Form Factor',
                    'CPU Socket Type',
                    'Chipset',
                    'Memory Slots',
                    'PCIe Slots',
                    'SATA Ports',
                    'M.2 Slots',
                    'Rear I/O Ports',
                    'BIOS/UEFI Features'
                ]
            ],
            [
                'name' => 'Bundle-Board / Procie',
                'slug' => 'bundle-board-procie',
                'sku' => 'BBP',
                'specifications' => [
                    'Motherboard Specifications',
                    'Processor Specifications',
                    'RAM Specifications',
                    'Storage Specifications'
                ]
            ],
            [
                'name' => 'Camera',
                'slug' => 'camera',
                'sku' => 'CAM',
                'specifications' => [
                    'Type',
                    'Resolution',
                    'Lens Mount',
                    'ISO Range',
                    'Connectivity',
                    'Storage Support',
                    'Video Capabilities'
                ]
            ],
            [
                'name' => 'Casing',
                'slug' => 'casing',
                'sku' => 'CSG',
                'specifications' => [
                    'Form Factor Compatibility',
                    'Material',
                    'Cooling Support',
                    'Drive Bays',
                    'I/O Panel Ports',
                    'Dimensions'
                ]
            ],
            [
                'name' => 'CCTV',
                'slug' => 'cctv',
                'sku' => 'CCTV',
                'specifications' => [
                    'Camera Resolution',
                    'Field of View',
                    'Night Vision',
                    'Power Source',
                    'Storage Options',
                    'Connectivity',
                    'Motion Detection'
                ]
            ],
            [
                'name' => 'Desktop',
                'slug' => 'desktop',
                'sku' => 'DST',
                'specifications' => [
                    'Processor',
                    'Motherboard',
                    'Graphics Card',
                    'Memory',
                    'Storage',
                    'Operating System',
                    'Power Supply Unit',
                    'Cooling System',
                    'Case',
                    'Monitor'
                ]
            ],
            [
                'name' => 'Video Card',
                'slug' => 'video-card',
                'sku' => 'VCD',
                'specifications' => [
                    'Chipset Manufacturer',
                    'Core Clock Speed',
                    'Memory Type',
                    'Memory Capacity',
                    'Power Requirements',
                    'Ports'
                ]
            ],
            [
                'name' => 'Graphics Processing Unit',
                'slug' => 'graphics-processing-unit',
                'sku' => 'GPU',
                'specifications' => [
                    'Core Clock Speed',
                    'Memory Type',
                    'Memory Capacity',
                    'DirectX/OpenGL Version',
                    'Ports'
                ]
            ],
            [
                'name' => 'Hard Disk Drive',
                'slug' => 'hard-disk-drive',
                'sku' => 'HDD',
                'specifications' => [
                    'Capacity',
                    'RPM',
                    'Cache Size',
                    'Interface',
                    'Form Factor',
                    'Warranty'
                ]
            ],
            [
                'name' => 'Headset/Speaker',
                'slug' => 'headset-speaker',
                'sku' => 'HSP',
                'specifications' => [
                    'Type',
                    'Connectivity',
                    'Driver Size',
                    'Frequency Range',
                    'Microphone',
                    'Battery Life'
                ]
            ],
            [
                'name' => 'Liquid Cooler',
                'slug' => 'liquid-cooler',
                'sku' => 'LQC',
                'specifications' => [
                    'Radiator Size',
                    'Fan Speed',
                    'Noise Level',
                    'Pump Speed',
                    'RGB Lighting',
                    'Compatibility'
                ]
            ],
            [
                'name' => 'Keyboard',
                'slug' => 'keyboard',
                'sku' => 'KBD',
                'specifications' => [
                    'Switch Type',
                    'Layout',
                    'Connectivity',
                    'Backlight',
                    'Key Rollover'
                ]
            ],
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'sku' => 'LPT',
                'specifications' => [
                    'Processor',
                    'Graphics Card',
                    'Memory',
                    'Storage',
                    'Display',
                    'Battery Life',
                    'Connectivity Ports',
                    'Operating System'
                ]
            ],
            [
                'name' => 'LCD Monitor',
                'slug' => 'lcd-monitor',
                'sku' => 'LCD',
                'specifications' => [
                    'Screen Size',
                    'Resolution',
                    'Panel Type',
                    'Refresh Rate',
                    'Response Time',
                    'Ports'
                ]
            ],
            [
                'name' => 'LED Monitor',
                'slug' => 'led-monitor',
                'sku' => 'LED',
                'specifications' => [
                    'Screen Size',
                    'Resolution',
                    'Panel Type',
                    'Backlight Type',
                    'Refresh Rate',
                    'Ports'
                ]
            ],
            [
                'name' => 'Memory',
                'slug' => 'memory',
                'sku' => 'MEM',
                'specifications' => [
                    'Type',
                    'Capacity',
                    'Frequency',
                    'Latency'
                ]
            ],
            [
                'name' => 'PISONET Accessories',
                'slug' => 'pisonet-accessories',
                'sku' => 'PSN',
                'specifications' => [
                    'Type',
                    'Material',
                    'Voltage Requirements',
                    'Compatibility'
                ]
            ],
            [
                'name' => 'Printer',
                'slug' => 'printer',
                'sku' => 'PRT',
                'specifications' => [
                    'Type',
                    'Resolution',
                    'Printing Speed',
                    'Connectivity',
                    'Paper Size'
                ]
            ],
            [
                'name' => 'Processor',
                'slug' => 'processor',
                'sku' => 'CPU',
                'specifications' => [
                    'Core Count',
                    'Thread Count',
                    'Base Clock',
                    'Turbo Boost',
                    'Socket Compatibility'
                ]
            ],
            [
                'name' => 'Power Supply Unit',
                'slug' => 'power-supply-unit',
                'sku' => 'PSU',
                'specifications' => [
                    'Wattage',
                    'Efficiency Rating',
                    'Modularity',
                    'Connector Types'
                ]
            ],
            [
                'name' => 'RGB Fans',
                'slug' => 'rgb-fans',
                'sku' => 'RGB',
                'specifications' => [
                    'Fan Size',
                    'Lighting Type',
                    'Speed',
                    'Noise Level'
                ]
            ],
            [
                'name' => 'Router',
                'slug' => 'router',
                'sku' => 'RTR',
                'specifications' => [
                    'Wi-Fi Standards',
                    'Bands',
                    'Ports',
                    'Antenna Count',
                    'Speed'
                ]
            ],
            [
                'name' => 'Solid State Drive',
                'slug' => 'solid-state-drive',
                'sku' => 'SSD',
                'specifications' => [
                    'Capacity',
                    'Read/Write Speeds',
                    'Interface',
                    'Form Factor'
                ]
            ],
            [
                'name' => 'USB',
                'slug' => 'usb',
                'sku' => 'USB',
                'specifications' => [
                    'Type',
                    'Capacity',
                    'Connector Type',
                    'Data Transfer Speed'
                ]
            ]
        ];

        foreach ($categories as $category) {
            // Insert category
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'sku' => $category['sku'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert specifications for this category
            foreach ($category['specifications'] as $specName) {
                DB::table('specifications')->insert([
                    'category_id' => $categoryId,
                    'name' => $specName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
