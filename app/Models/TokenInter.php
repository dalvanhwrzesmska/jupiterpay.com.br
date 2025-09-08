<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenInter extends Model
{
    protected $fillable = [
        "access_token",
        "refresh_token",
        "expires_at",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tokens()
    {
        return $this->hasMany(TokenInter::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
