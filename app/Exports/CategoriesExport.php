<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CategoriesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Category::select('name','category_id', 'created_at', 'updated_at')->latest()->get();
    }

    public function map($item) : array
    {
        return [
            $item->name,
            $item->parent->name ?? '',
            Date::dateTimeToExcel($item->created_at),
            Date::dateTimeToExcel($item->updated_at)
        ] ;
    }

    public function columnFormats(): array
    {
        return [
            'C' => 'yyyy-mm-dd hh:MM:ss',
            'D' => 'yyyy-mm-dd hh:MM:ss',
        ];
    }

    public function headings(): array
    {
        return [
            'NAME', 'PARENT_ID', 'CREATED_AT','UPDATED_AT',
        ];
    }
}
