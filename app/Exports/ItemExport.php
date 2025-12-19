<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class ItemExport implements FromCollection, WithHeadings, WithEvents, WithStyles

{
    private int $rowNumber = 0;

    public function collection()
    {
        return MasterItem::with('kategori')->get()->map(function ($item, $index) {
            return [
                'no' => $this->rowNumber++,
                'nama' => $item->nama,
                'kategori' => $item->kategori->pluck('nama_kategori')->implode(', '),
                'harga_beli' => $item->harga_beli,
                'laba' => $item->laba,
                'harga_jual' => $item->harga_beli + ($item->harga_beli * $item->laba / 100),
                'supplier' => $item->supplier,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga',
            'Laba (%)',
            'Harga Jual',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => [ // baris header tabel
                'font' => ['bold' => true],
            ],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Judul
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->setCellValue('A1', 'MASTER DATA ITEM');

                $event->sheet->mergeCells('A2:I2');
                $event->sheet->setCellValue(
                    'A2',
                    'Per Tanggal: ' . Carbon::now()->format('d F Y')
                );

                // Style Judul
                $event->sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Border Header
                $event->sheet->getStyle('A4:I4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EAEAEA'],
                    ],
                ]);

                // Auto width
                foreach (range('A', 'G') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
