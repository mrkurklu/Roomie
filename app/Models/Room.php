<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Bir oda, bir oda tipine aittir.
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Bir oda, bir otele aittir.
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
