<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'from_user_id', 'to_user_id', 'subject', 'content',
        'type', 'priority', 'is_read', 'read_at',
        'original_content', 'original_language', 'translated_content',
        'sender_id', // Veritabanında sender_id kolonu var
        'message' // Veritabanında message kolonu var
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * from_user_id attribute'unu sender_id'ye map et
     */
    public function setFromUserIdAttribute($value)
    {
        $this->attributes['sender_id'] = $value;
    }

    /**
     * sender_id'den from_user_id'yi oku
     */
    public function getFromUserIdAttribute()
    {
        return isset($this->attributes['sender_id']) ? $this->attributes['sender_id'] : null;
    }

    /**
     * content attribute'unu message kolonuna map et
     */
    public function setContentAttribute($value)
    {
        $this->attributes['message'] = $value;
    }

    /**
     * message kolonundan content'i oku
     */
    public function getContentAttribute()
    {
        return isset($this->attributes['message']) ? $this->attributes['message'] : null;
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}

