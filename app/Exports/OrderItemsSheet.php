<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\OrderItem;

class OrderItemsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithEvents
{
    protected $rowCount = 0;

    public function collection()
    {
        $items = OrderItem::with(['order.customer'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->rowCount = $items->count();
        return $items;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Customer',
            'Product Name',
            'SKU',
            'Qty',
            'Points/Unit',
            'Total Points',
        ];
    }

    public function map($item): array
    {
        return [
            $item->order->order_id,
            $item->order->created_at->format('d M Y H:i'),
            $item->order->customer->name ?? '-',
            $item->product_name,
            $item->sku,
            $item->qty,
            $item->points_per_unit,
            $item->total_points,
        ];
    }

    public function title(): string
    {
        return 'Order Items Detail';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->rowCount + 1;

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Green
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            "A2:H{$lastRow}" => [
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

                // Auto-size columns
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->getColumnDimension('D')->setWidth(35); // Product Name

                // Zebra striping
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0FDF4'] // Light green
                            ]
                        ]);
                    }
                }

                // Center align quantity and points columns
                $sheet->getStyle("F2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format numbers
                $sheet->getStyle("G2:H{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');

                // Freeze header
                $sheet->freezePane('A2');

                // Add filter
                $sheet->setAutoFilter("A1:H1");
            },
        ];
    }
}
