<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'PT Keamanan Nusantara', 'phone' => '081234567890'],
            ['name' => 'CV Surya Security', 'phone' => '081234567891'],
            ['name' => 'Toko CCTV Jaya Abadi', 'phone' => '081234567892'],
            ['name' => 'PT Rafka Security System', 'phone' => '081234567893'],
            ['name' => 'UD Berkah CCTV', 'phone' => '081234567894'],
            ['name' => 'Hotel Grand Indonesia', 'phone' => '081234567895'],
            ['name' => 'Mall Sentosa Plaza', 'phone' => '081234567896'],
            ['name' => 'Rumah Sakit Harapan Sehat', 'phone' => '081234567897'],
            ['name' => 'PT Mandiri Security', 'phone' => '081234567898'],
            ['name' => 'Toko Elektronik Cahaya', 'phone' => '081234567899'],
            ['name' => 'CV Teknologi Keamanan', 'phone' => '081234567800'],
            ['name' => 'UD Maju Makmur CCTV', 'phone' => '081234567801'],
            ['name' => 'PT Digital Security Indonesia', 'phone' => '081234567802'],
            ['name' => 'Budi Santoso - Perumahan Elite', 'phone' => '081234567803'],
            ['name' => 'Restoran Padang Minang', 'phone' => '081234567804'],
            ['name' => 'Pabrik Tekstil Jaya', 'phone' => '081234567805'],
            ['name' => 'Sekolah SMA Negeri 1', 'phone' => '081234567806'],
            ['name' => 'PT Prima Security Solutions', 'phone' => '081234567807'],
            ['name' => 'Toko Bangunan Sumber Makmur', 'phone' => '081234567808'],
            ['name' => 'CV Permata Security System', 'phone' => '081234567809'],
            ['name' => 'Minimarket Indomaret Cabang 5', 'phone' => '081234567810'],
            ['name' => 'Kantor Bank BCA Cabang Utara', 'phone' => '081234567811'],
            ['name' => 'Apartemen Green Park Residence', 'phone' => '081234567812'],
            ['name' => 'Gudang Logistik PT Cepat Kirim', 'phone' => '081234567813'],
            ['name' => 'Villa Bali Sejahtera', 'phone' => '081234567814'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('25 customers created successfully!');
    }
}
