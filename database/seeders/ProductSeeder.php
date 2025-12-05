<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'CCTV Indoor 2MP Hikvision',
                'sku' => 'CCT-HKV-001',
                'quantity' => 150,
                'price' => 850000,
                'points_per_unit' => 9,
            ],
            [
                'name' => 'CCTV Outdoor 4MP Dahua',
                'sku' => 'CCT-DAH-002',
                'quantity' => 120,
                'price' => 1500000,
                'points_per_unit' => 15,
            ],
            [
                'name' => 'DVR 8 Channel Hikvision',
                'sku' => 'DVR-HKV-003',
                'quantity' => 80,
                'price' => 3500000,
                'points_per_unit' => 35,
            ],
            [
                'name' => 'NVR 16 Channel Dahua',
                'sku' => 'NVR-DAH-004',
                'quantity' => 60,
                'price' => 5500000,
                'points_per_unit' => 55,
            ],
            [
                'name' => 'CCTV PTZ 5MP',
                'sku' => 'CCT-PTZ-005',
                'quantity' => 45,
                'price' => 4500000,
                'points_per_unit' => 45,
            ],
            [
                'name' => 'Kabel Coaxial RG59 100M',
                'sku' => 'KBL-COX-006',
                'quantity' => 200,
                'price' => 450000,
                'points_per_unit' => 5,
            ],
            [
                'name' => 'Kabel UTP Cat6 305M',
                'sku' => 'KBL-UTP-007',
                'quantity' => 180,
                'price' => 1200000,
                'points_per_unit' => 12,
            ],
            [
                'name' => 'Power Supply 12V 10A',
                'sku' => 'PWR-SPL-008',
                'quantity' => 250,
                'price' => 350000,
                'points_per_unit' => 4,
            ],
            [
                'name' => 'Hard Disk 2TB Surveillance',
                'sku' => 'HDD-SUR-009',
                'quantity' => 100,
                'price' => 1800000,
                'points_per_unit' => 18,
            ],
            [
                'name' => 'Hard Disk 4TB Surveillance',
                'sku' => 'HDD-SUR-010',
                'quantity' => 75,
                'price' => 3200000,
                'points_per_unit' => 32,
            ],
            [
                'name' => 'Bracket CCTV Indoor',
                'sku' => 'BRK-IND-011',
                'quantity' => 300,
                'price' => 75000,
                'points_per_unit' => 1,
            ],
            [
                'name' => 'Bracket CCTV Outdoor',
                'sku' => 'BRK-OUT-012',
                'quantity' => 280,
                'price' => 125000,
                'points_per_unit' => 2,
            ],
            [
                'name' => 'BNC Connector 100pcs',
                'sku' => 'BNC-CON-013',
                'quantity' => 350,
                'price' => 250000,
                'points_per_unit' => 3,
            ],
            [
                'name' => 'PoE Switch 8 Port',
                'sku' => 'POE-SWT-014',
                'quantity' => 90,
                'price' => 2200000,
                'points_per_unit' => 22,
            ],
            [
                'name' => 'CCTV Wireless IP Camera',
                'sku' => 'CCT-WIF-015',
                'quantity' => 110,
                'price' => 1100000,
                'points_per_unit' => 11,
            ],
            [
                'name' => 'Monitor LED 19" CCTV',
                'sku' => 'MON-LED-016',
                'quantity' => 55,
                'price' => 1650000,
                'points_per_unit' => 17,
            ],
            [
                'name' => 'Sensor Gerak PIR',
                'sku' => 'SNS-PIR-017',
                'quantity' => 200,
                'price' => 275000,
                'points_per_unit' => 3,
            ],
            [
                'name' => 'Alarm Siren Outdoor',
                'sku' => 'ALM-SRN-018',
                'quantity' => 140,
                'price' => 450000,
                'points_per_unit' => 5,
            ],
            [
                'name' => 'Access Control Fingerprint',
                'sku' => 'ACC-FGP-019',
                'quantity' => 70,
                'price' => 3800000,
                'points_per_unit' => 38,
            ],
            [
                'name' => 'Video Balun Passive',
                'sku' => 'VID-BAL-020',
                'quantity' => 220,
                'price' => 180000,
                'points_per_unit' => 2,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
