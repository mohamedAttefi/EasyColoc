<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'colocation_id',
        'invited_by',
        'accepted_at',
        'declined_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function scopePending($query)
    {
        return $query->whereNull('accepted_at')
                    ->whereNull('declined_at')
                    ->where('expires_at', '>', now());
    }

    public function scopeAccepted($query)
    {
        return $query->whereNotNull('accepted_at');
    }

    public function scopeDeclined($query)
    {
        return $query->whereNotNull('declined_at');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public static function generateToken(): string
    {
        return Str::random(32);
    }

    public function isPending(): bool
    {
        return is_null($this->accepted_at) 
               && is_null($this->declined_at) 
               && $this->expires_at > now();
    }

    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }

    public function isDeclined(): bool
    {
        return !is_null($this->declined_at);
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function accept()
    {
        $this->update([
            'accepted_at' => now(),
        ]);
    }

    public function decline()
    {
        $this->update([
            'declined_at' => now(),
        ]);
    }

    public function getInviteUrlAttribute(): string
    {
        return route('invitations.show', $this->token);
    }
}
