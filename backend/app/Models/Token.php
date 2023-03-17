<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'refresh_token',
        'expires_in',
        'expires_at',
        'account_id'
    ];

    public function isExpired()
    {
        return Carbon::parse($this->expires_at)->isPast();
    }
}
