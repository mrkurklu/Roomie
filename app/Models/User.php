<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [ 'name', 'email', 'password', 'hotel_id', 'language', 'tc_no' ];
    protected $hidden = [ 'password', 'remember_token' ];
    protected $casts = [ 'email_verified_at' => 'datetime' ];

    /**
     * Kullanıcının sahip olduğu rolleri döndürür.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Kullanıcının belirli bir role sahip olup olmadığını kontrol eder.
     */
    public function hasRole($roleName)
    {
        // Roller yüklenmemişse yükle
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        
        foreach ($this->roles as $role) {
            if ($role->name === $roleName) {
                return true;
            }
        }
        return false;
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function guestStays()
    {
        return $this->hasMany(GuestStay::class);
    }

    public function activeGuestStay()
    {
        return $this->hasOne(GuestStay::class)->where('status', 'checked_in');
    }

    public function assignedRooms()
    {
        return $this->hasMany(Room::class, 'assigned_staff_id');
    }
}
