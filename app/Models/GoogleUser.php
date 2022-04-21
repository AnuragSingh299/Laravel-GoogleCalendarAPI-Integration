<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleUser extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'google_id',
        'email',
        'access_token',
        'refresh_token',
        'expires'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}