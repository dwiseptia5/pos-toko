<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer_id',
        'subtotal',
        'discount',
        'tax',
        'other_fee',
        'grand_total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
