<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ItemExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    private int $rowNumber = 0;

    public function collection()
    {
        return MasterItem::with('kategori')->get();
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

    public function map($item): array
    {
        return [
            ++$this->rowNumber, // NOMOR URUT
            $item->kategori->pluck('nama_kategori')->implode(', '),
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            $item->harga_beli + ($item->harga_beli * $item->laba / 100),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'I'; // karena sekarang ada 9 kolom
        $lastRow = $sheet->getHighestRow();

        // HEADER STYLE
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
            ],
        ]);

        // BORDER SELURUH TABEL
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        return [];
    }
}
