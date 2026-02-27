<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'date',
        'category_id',
        'payer_id',
        'colocation_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeForColocation($query, $colocationId)
    {
        return $query->where('colocation_id', $colocationId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function getFormattedAmountAttribute()
    {
        return '€' . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('M d, Y');
    }

    public function isPaidBy(User $user): bool
    {
        return $this->payer_id === $user->id;
    }

    public function getSharePerPersonAttribute()
    {
        $activeMembers = $this->colocation->activeMembers()->count();
        return $activeMembers > 0 ? $this->amount / $activeMembers : 0;
    }
}
