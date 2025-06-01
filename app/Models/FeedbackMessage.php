<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    protected $fillable = ['name', 'email', 'message', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
