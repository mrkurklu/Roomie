<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['hotel_id', 'room_type_id', 'room_number', 'image_path', 'status', 'assigned_staff_id'];

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

    // Bir oda, bir personele atanabilir.
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    // Bir odada birden fazla misafir kalabilir (geçmiş ve mevcut)
    public function guestStays()
    {
        return $this->hasMany(GuestStay::class);
    }

    // Aktif misafir kalışı
    public function activeGuestStay()
    {
        return $this->hasOne(GuestStay::class)->where('status', 'checked_in');
    }
}
