<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'name', 'description', 'category', 'quantity',
        'available_quantity', 'unit', 'cost_per_unit', 'status'
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}

