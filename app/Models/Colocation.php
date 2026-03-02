<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'owner_id',
        'cancelled_at',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'colocation_members')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivotNull('left_at');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function debtTransfers()
    {
        return $this->hasMany(DebtTransfer::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isOwnerOf(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function addMember(User $user, $role = 'member')
    {
        $this->members()->attach($user->id, [
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(User $user, User $removedBy = null): bool
    {
        // Check if user is a member
        if (!$this->hasActiveMember($user)) {
            return false;
        }

        // Calculate user's balance before removal
        $balanceCalculator = new \App\Services\BalanceCalculator();
        $balances = $balanceCalculator->calculateColocationBalances($this);
        $userBalance = $balances[$user->id]['balance'] ?? 0;

        // If user has debt (negative balance), apply reputation penalty
        if ($userBalance < 0) {
            $penalty = abs($userBalance) * 0.1; // 10% of debt as penalty
            $user->update([
                'reputation' => max(0, $user->reputation - $penalty)
            ]);

            // If removed by owner, transfer debt to owner
            if ($removedBy && $removedBy->isOwnerOf($this)) {
                // Create debt transfer record
                \App\Models\DebtTransfer::create([
                    'debtor_id' => $this->owner_id,
                    'creditor_id' => null, // System debt
                    'colocation_id' => $this->id,
                    'amount' => abs($userBalance),
                    'date' => now(),
                ]);
            }
        }

        // Mark user as left
        $this->members()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return true;
    }

    public function hasMember(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivotNull('left_at')
            ->exists();
    }

    public function hasActiveMember(User $user): bool
    {
        $result = DB::selectOne(
            'SELECT EXISTS(
            select 1
            from colocation_members m join colocations c on m.colocation_id = c.id
            join users u on m.user_id = u.id WHERE c.id = ? and u.id = ? and m.left_at IS NULL
        ) AS has_member',
            [$this->id, $user->id]
        );

        return (bool) $result->has_member;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}
