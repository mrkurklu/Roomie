<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'hotel_id', 'user_id', 'guest_name', 'guest_email',
        'rating', 'title', 'comment', 'category', 'is_public', 'is_responded'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_responded' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

