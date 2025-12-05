<?php

namespace Database\Seeders;

use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\Customer;
use App\Services\MonthlySummaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PointTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $summaryService = app(MonthlySummaryService::class);

        $products = Product::all();
        $customers = Customer::all();

        if ($products->isEmpty() || $customers->isEmpty()) {
            $this->command->error('Please run ProductSeeder and CustomerSeeder first!');
            return;
        }

        $totalTransactions = 0;

        // Generate 6 bulan terakhir
        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            $date = Carbon::now()->subMonths($monthsAgo);
            $count = rand(20, 40);

            for ($i = 0; $i < $count; $i++) {
                $product = $products->random();
                $customer = $customers->random();
                $qty = rand(1, 10);
                $day = rand(1, $date->daysInMonth);
                $hour = rand(8, 17);
                $minute = rand(0, 59);

                $trxDate = $date->copy()->day($day)->hour($hour)->minute($minute);

                $trx = PointTransaction::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'qty' => $qty,
                    'points' => $product->points_per_unit,
                    'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                    'created_at' => $trxDate,
                    'updated_at' => $trxDate,
                ]);

                $summaryService->add($trx);
                $totalTransactions++;
            }
        }

        // Generate hari ini
        $today = [
            ['h' => 0, 'm' => 20],
            ['h' => 2, 'm' => 0],
            ['h' => 3, 'm' => 30],
            ['h' => 4, 'm' => 15],
            ['h' => 5, 'm' => 0],
        ];

        foreach ($today as $t) {
            $product = $products->random();
            $customer = $customers->random();
            $qty = rand(1, 5);
            $trxDate = Carbon::now()->subHours($t['h'])->subMinutes($t['m']);

            $trx = PointTransaction::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'sku' => $product->sku,
                'qty' => $qty,
                'points' => $product->points_per_unit,
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'created_at' => $trxDate,
                'updated_at' => $trxDate,
            ]);

            $summaryService->add($trx);
            $totalTransactions++;
        }

        $this->command->info("Total transactions: {$totalTransactions}");
    }
}
