<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Lending extends Model
{
    protected $fillable = [
        'name',
        'keterangan',
        'date',
        'returned',
        'return_date',
        'edited_by',
    ];

    protected $casts = [
        'returned' => 'boolean',
        'date' => 'date',
        'return_date' => 'date',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'lending_items')
                ->withPivot('total')
                ->withTimestamps();
    }
}
