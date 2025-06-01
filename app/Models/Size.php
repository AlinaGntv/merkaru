<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'user_id',
        'height',
        'chest',
        'waist',
        'hips'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}