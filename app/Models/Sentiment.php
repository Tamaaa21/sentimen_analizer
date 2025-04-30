<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'sentiment',
        'probability'
    ];

    protected $casts = [
        'probability' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}