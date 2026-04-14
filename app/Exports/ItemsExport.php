<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Item::with('category')->get()->map(function ($item) {
            return [
                'Category'      => $item->category->name ?? '-',
                'Name Item'     => $item->name,
                'Total'         => $item->total,
                'Repair Total'  => $item->repair == 0 ? '-' : $item->repair,
                'Last Updated'  => $item->updated_at
                                    ? $item->updated_at->format('M d, Y')
                                    : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category',
            'Name Item',
            'Total',
            'Repair Total',
            'Last Updated'
        ];
    }
}
