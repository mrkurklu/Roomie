<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'email', 'description', 'amenities', 'welcome_message'];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
