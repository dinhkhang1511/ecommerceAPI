<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrdersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::select(
            'order_no',
            'customer_name',
            'customer_phone',
            'customer_email',
            'customer_address',
            'province_id',
            'district_id',
            'ward_id',
            'price',
            'status',
            'created_at',
            'updated_at'
        )->latest()->get();
    }

    public function map($item) : array
    {
        return [
            $item->order_no,
            $item->customer_name,
            $item->customer_phone,
            $item->customer_email,
            $item->customer_address,
            $item->province->name,
            $item->district->name,
            $item->ward->name,
            $item->price,
            $item->status,
            Date::dateTimeToExcel($item->created_at),
            Date::dateTimeToExcel($item->updated_at)
        ] ;
    }

    public function headings(): array
    {
        return [
            'ORDER NO', 'CUSTOMER NAME', 'CUSTOMER PHONE', 'CUSTOMER EMAIL', 'CUSTOMER ADDRESS', 'PROVINCE', 'DISTRICT', 'WARD', 'PRICE', 'STATUS', 'CREATED_AT', 'UPDATED_AT'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'K' => 'yyyy-mm-dd hh:MM:ss',
            'L' => 'yyyy-mm-dd hh:MM:ss',
        ];
    }
}
