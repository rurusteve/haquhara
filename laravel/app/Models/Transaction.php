<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $lazy = true;
    protected $fillable = ['id', 'uuid', 'platform_id', 'transaction_date', 'transaction_type', 'transaction_number', 'memo', 'due_date', 'platform', 'warehouse_id', 'customer_id'];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'transaction_id');
    }

    public function payment_sum(): HasMany
    {
        return $this->hasMany(Payment::class)
            ->selectRaw('SUM(payment_amount) as sum_payment_amount')
            ->groupBy('transaction_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customer_city()
    {
        return $this->belongsToThrough(City::class, [Customer::class, PostalCode::class], 'customer_id', 'postal_id', 'id');
    }

    public function transaction_type(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function platform(): HasOne
    {
        return $this->hasOne(Platform::class);
    }

}
