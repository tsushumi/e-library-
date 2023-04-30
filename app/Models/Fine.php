<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'loan_id',
        'fined_at',
        'fine_amount'
    ];

    protected $casts = [
        'fined_at' => 'datetime'
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
