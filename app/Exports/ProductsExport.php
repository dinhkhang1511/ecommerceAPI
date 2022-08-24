<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select(
            'name',
            'price',
            'quantity',
            'discount',
            'category_id',
            'created_at',
            'updated_at'

        )->latest()->get();
    }

    public function map($item) : array
    {
        return [
            $item->name,
            $item->price,
            $item->quantity,
            $item->discount,
            $item->category->name,
            Date::dateTimeToExcel($item->created_at),
            Date::dateTimeToExcel($item->updated_at)
        ] ;
    }

    public function headings(): array
    {
        return [
            'NAME', 'PRICE', 'QUANTITY', 'DISCOUNT', 'CATEGORY', 'CREATED_AT', 'UPDATED_AT'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => 'yyyy-mm-dd hh:MM:ss',
            'G' => 'yyyy-mm-dd hh:MM:ss',
        ];
    }
}
