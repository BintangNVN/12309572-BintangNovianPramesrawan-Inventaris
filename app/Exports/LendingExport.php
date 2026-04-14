<?php

namespace App\Exports;

use App\Models\Lending;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LendingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Lending::with('items')->get()->flatMap(function ($lending) {
            return $lending->items->map(function ($item) use ($lending) {
                return [
                    'Item' => $item->name,
                    'Total' => $item->pivot->total,
                    'Name' => $lending->name,
                    'Ket' => $lending->keterangan ?? '-',
                    'Date' => $lending->date
                        ? $lending->date->format('M d, Y')
                        : '-',

                    // 🔥 bagian penting
                    'Return Date' => $lending->returned
                        ? ($lending->return_date
                            ? $lending->return_date->format('M d, Y')
                            : 'returned')
                        : '-',

                    'Edited By' => $lending->edited_by ?? '-',
                ];
            });
        });
    }

    public function headings(): array
    {
        return [
            'Item',
            'Total',
            'Name',
            'Ket',
            'Date',
            'Return Date',
            'Edited By',
        ];
    }
}
