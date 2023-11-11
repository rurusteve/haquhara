<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;
    protected $lazy = true;
    protected $table = 'customers';
    protected $fillable = ['uuid', 'name', 'phone_number', 'email', 'billing_address', 'shipping_address', 'ref_no', 'tax_number', 'postal_id', 'company_name', 'company_tax_number'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    public function postal_code(): BelongsTo
    {
        return $this->belongsTo(PostalCode::class, 'postal_id');
    }

    public function total_spending()
    {
        return $this->transactions->sum(function ($transaction) {
            return $transaction->payment->payment_amount;
        });
    }

    public function average_basket_size()
    {
        $transactions = $this->transactions;

        if ($transactions->isEmpty()) {
            return 0;
        }

        $totalItems = $transactions->sum(function ($transaction) {
            return $transaction->details->sum('quantity');
        });

        // Calculate the average basket size
        $averageBasketSize = $totalItems / $transactions->count();

        return $averageBasketSize;
    }

    public function products_bought()
    {
        return $this->hasManyThrough(Product::class, Transaction::class)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select('products.*')
            ->distinct();
    }

    public function tenure()
    {
        $smallest_date = $this->transactions->min('transaction_date');
            $currentDate = now(); // Current date and time
            $age_in_days = $currentDate->diffInDays($smallest_date);

            $years = floor($age_in_days / 365);
            $age_in_days -= $years * 365;

            $months = floor($age_in_days / 30);
            $age_in_days -= $months * 30;

            $tenure = '';
            if ($years > 0) {
                $tenure .= $years . ' year' . ($years > 1 ? 's' : '') . ' ';
            }
            if ($months > 0) {
                $tenure .= $months . ' month' . ($months > 1 ? 's' : '') . ' ';
            }
            if ($age_in_days > 0) {
                $tenure .= $age_in_days . ' day' . ($age_in_days > 1 ? 's' : '');
            }

        return $tenure;
    }

    public function purchased_products()
    {
        $customer = $this;
        $products = Product::select(['products.*', DB::raw('COUNT(*) as total_bought')])
    ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
    ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
    ->where('transactions.customer_id', $customer->id)
    ->groupBy('products.id')
    ->get();


        return $products;
    }

}
