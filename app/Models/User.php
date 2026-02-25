<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'reputation',
        'is_super_admin',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'is_banned' => 'boolean',
    ];

    public function ownedColocations()
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }

    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'colocation_members')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function activeColocation()
    {
        return $this->colocations()
            ->wherePivotNull('left_at')
            ->where('status', 'active')
            ->first();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payer_id');
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'receiver_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function isGlobalAdmin(): bool
    {
        return $this->is_super_admin;
    }

    public function isOwnerOf(Colocation $colocation): bool
    {
        return $colocation->owner_id === $this->id;
    }

    public function isMemberOf(Colocation $colocation): bool
    {
        return $colocation->members()->where('user_id', $this->id)->exists();
    }

    public function canJoinColocation(): bool
    {
        return !$this->activeColocation();
    }
}
