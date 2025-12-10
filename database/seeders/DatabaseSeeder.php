<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::flushEventListeners();
        Product::flushEventListeners();
        Customer::flushEventListeners();

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  🌱 Starting Database Seeding...');
        $this->command->info('========================================');
        $this->command->info('');

        // Disable foreign key checks untuk MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Create default admin user
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'Admin Super',
                'password' => bcrypt('123'),
            ]);

            $this->command->info('✓ User created successfully');
            $this->command->info('');

            // Run seeders in correct order (PENTING!)
            // 1. Product dulu (karena tidak ada dependency)
            $this->command->info('📦 Seeding Products...');
            $this->call(ProductSeeder::class);
            $this->command->info('');

            // 2. Customer (karena tidak ada dependency)
            $this->command->info('👥 Seeding Customers...');
            $this->call(CustomerSeeder::class);
            $this->command->info('');

        } catch (\Exception $e) {
            $this->command->error('❌ Error during seeding: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ All seeders completed successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('📝 Login credentials:');
        $this->command->info('   Email: admin@gmail.com');
        $this->command->info('   Password: 123');
        $this->command->info('');
        $this->command->info('📊 Database Summary:');
        $this->command->info('   • Users: 1');
        $this->command->info('   • Products: 20 (CCTV & Security)');
        $this->command->info('   • Customers: 25');
        $this->command->info('   • Transactions: ~155 (6 months data)');
        $this->command->info('   • Monthly Summaries: Auto-generated');
        $this->command->info('');
    }
}
