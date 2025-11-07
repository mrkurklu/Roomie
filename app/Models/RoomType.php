<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    // Bir oda tipinin birden çok odası olabilir.
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
