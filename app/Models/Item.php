<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'total',
        'repair',
        'lending',
    ];

    protected $appends = [
        'available',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getAvailableAttribute()
    {
        $total = $this->total ?? 0;
        $repair = $this->repair ?? 0;
        $lending = $this->lending ?? 0;

        return max(0, $total - $repair - $lending);
    }

    public function lendings()
    {
         return $this->belongsToMany(Lending::class, 'lending_items')
                ->withPivot('total')
                ->withTimestamps();
    }
    public function getLendingTotalAttribute()
    {
        return $this->lendings
            ->where('returned', false)
            ->sum('pivot.total');
    }
}


