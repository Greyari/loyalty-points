<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrdersExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Statistics' => new OrderStatisticsSheet(),
            'Orders Summary' => new OrdersSummarySheet(),
            'Order Items Detail' => new OrderItemsSheet(),
        ];
    }
}


