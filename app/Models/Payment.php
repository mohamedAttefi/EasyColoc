<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'payer_id',
        'receiver_id',
        'expense_id',
        'colocation_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function getFormattedAmountAttribute()
    {
        return '€' . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->paid_at->format('M d, Y');
    }

    public function markAsPaid()
    {
        $this->update([
            'paid_at' => now(),
        ]);
    }

    public function isPaid(): bool
    {
        return !is_null($this->paid_at);
    }
}
