<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'colocation_id',
        'from_user_id',
        'to_user_id',
        'origin_user_id',
        'amount',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function originUser()
    {
        return $this->belongsTo(User::class, 'origin_user_id');
    }
}
