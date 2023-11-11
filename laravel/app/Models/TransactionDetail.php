<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $lazy = true;
    protected $table = 'transaction_details';
    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'discount_per_line', 'tax_amount', 'discount_amount', 'discount_percentage', 'deduction_amount', 'shipping_cost'];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
