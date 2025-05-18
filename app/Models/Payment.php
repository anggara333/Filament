<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_method',
        'amount',
        'payment_status',
        'payment_date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
