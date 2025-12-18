<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class OrdersSummarySheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithEvents
{
    protected $rowCount = 0;

    public function collection()
    {
        $orders = Order::with('customer')
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->rowCount = $orders->count();
        return $orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Customer',
            'Items',
            'Total Qty',
            'Total Points',
            'Total Price (IDR)',
            'Notes',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_id,
            $order->created_at->format('d M Y H:i'),
            $order->customer->name ?? '-',
            $order->items_count,
            $order->total_items,
            $order->total_points,
            $order->price,
            $order->notes ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Orders Summary';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->rowCount + 1;

        return [
            // Header row - Bold white text on dark blue
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Indigo
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Zebra striping for data rows
            "A2:I{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB']
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->rowCount + 1;

                // Auto-size all columns
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Set minimum column widths
                $sheet->getColumnDimension('A')->setWidth(15); // Order ID
                $sheet->getColumnDimension('B')->setWidth(18); // Date
                $sheet->getColumnDimension('C')->setWidth(25); // Customer
                $sheet->getColumnDimension('H')->setWidth(30); // Notes

                // Zebra striping (alternate row colors)
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:I{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB'] // Light gray
                            ]
                        ]);
                    }
                }

                // Center align specific columns
                $sheet->getStyle("D2:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I2:I{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format numbers with thousands separator
                $sheet->getStyle("F2:G{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');

                // Freeze header row
                $sheet->freezePane('A2');

                // Add filter to header
                $sheet->setAutoFilter("A1:I1");
            },
        ];
    }
}
