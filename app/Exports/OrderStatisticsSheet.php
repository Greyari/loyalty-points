<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Support\Facades\DB;

class OrderStatisticsSheet implements WithTitle, WithStyles, WithEvents
{
    public function title(): string
    {
        return 'Statistics';
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get statistics data
                $totalOrders = Order::count();
                $totalRevenue = Order::sum('price');
                $totalPoints = Order::sum('total_points');
                $totalItems = Order::sum('total_items');
                $avgOrderValue = Order::avg('price');
                $topCustomer = Order::select('customer_id', DB::raw('COUNT(*) as order_count'))
                    ->groupBy('customer_id')
                    ->orderBy('order_count', 'desc')
                    ->with('customer')
                    ->first();

                // Title
                $sheet->setCellValue('A1', '📊 ORDER STATISTICS');
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'] // Yellow
                    ]
                ]);

                $sheet->setCellValue('A2', 'Generated: ' . now()->format('d M Y H:i'));
                $sheet->mergeCells('A2:D2');
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']]
                ]);

                // Stats boxes
                $stats = [
                    ['label' => '📦 Total Orders', 'value' => number_format($totalOrders, 0, ',', '.'), 'color' => 'DBEAFE'],
                    ['label' => '💰 Total Revenue', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'color' => 'D1FAE5'],
                    ['label' => '⭐ Total Points', 'value' => number_format($totalPoints, 0, ',', '.'), 'color' => 'FEF3C7'],
                    ['label' => '📊 Total Items Sold', 'value' => number_format($totalItems, 0, ',', '.'), 'color' => 'E0E7FF'],
                    ['label' => '💵 Avg Order Value', 'value' => 'Rp ' . number_format($avgOrderValue, 0, ',', '.'), 'color' => 'FCE7F3'],
                    ['label' => '👤 Top Customer', 'value' => ($topCustomer->customer->name ?? '-') . ' (' . $topCustomer->order_count . ' orders)', 'color' => 'FAE8FF'],
                ];

                $row = 4;
                foreach ($stats as $stat) {
                    $sheet->setCellValue("A{$row}", $stat['label']);
                    $sheet->setCellValue("C{$row}", $stat['value']);
                    $sheet->mergeCells("A{$row}:B{$row}");
                    $sheet->mergeCells("C{$row}:D{$row}");

                    $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $stat['color']]
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB']
                            ]
                        ]
                    ]);

                    $sheet->getStyle("C{$row}:D{$row}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F2937']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB']
                            ]
                        ]
                    ]);

                    $sheet->getRowDimension($row)->setRowHeight(30);
                    $row++;
                }

                // Column widths
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(5);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(5);

                // Row heights
                $sheet->getRowDimension(1)->setRowHeight(35);
                $sheet->getRowDimension(2)->setRowHeight(20);
            },
        ];
    }
}
