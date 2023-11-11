<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Platform;
use App\Models\PostalCode;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Unit;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;
use App\Events\ImportProgressEvent;
use Illuminate\Support\Facades\DB;

class SalesImport implements ToCollection, WithStartRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {

        $otherUnit = Unit::firstWhere('unit_code', 'OTHER');

        foreach ($rows as $row) {
            try {
                $name = '';
                $phone_number = '';

                if (isset($row[3])) {
                    $parts = explode(",", $row[3]);
                    $name = isset($parts[0]) ? trim($parts[0]) : '';
                    $phone_number = isset($parts[1]) ? trim($parts[1]) : '';
                }

                $clean_number = $phone_number;

                if (substr($phone_number, 0, 2) !== '62') {
                    if (substr($phone_number, 0, 1) === '0') {
                        $clean_number = '62' . substr($phone_number, 1);
                    } else {
                        $clean_number = '62' . $phone_number;
                    }
                }

                $clean_number = preg_replace('/[^0-9]/', '', $clean_number);


                $customer = Customer::updateOrCreate(
                    ['phone_number' => $clean_number],
                    [
                        'name' => $name,
                        'email' => $row[7],
                        'billing_address' => $row[8],
                        'shipping_address' => $row[9],
                        'ref_no' => $row[2],
                        'postal_id' => optional($row[8] && is_string($row[8]) ? PostalCode::where('postal_code', substr($row[8], -5))->first() : null)->postal_id,
                        'company_name' => $row[28],
                        'company_tax_number' => $row[29],
                    ]
                );

                $transaction_date = Carbon::createFromFormat('m/d/Y', $row[0])->format('Y-m-d');
                $due_date = Carbon::createFromFormat('m/d/Y', $row[6])->format('Y-m-d');
                $platform = Platform::firstWhere('platform_code', $row[11]);
                $warehouse = Warehouse::firstWhere('warehouse_name', $row[17]);

                $transaction = Transaction::updateOrCreate(
                    ['transaction_number' => $row[2]],
                    [
                        'transaction_date' => $transaction_date,
                        'transaction_type' => $row[1],
                        'memo' => $row[5],
                        'due_date' => $due_date,
                        'platform_id' => optional($platform)->id,
                        'warehouse_id' => optional($warehouse)->id,
                        'customer_id' => optional($customer)->id,
                    ]
                );

                $payment_status = $row[4] == 'Lunas' ? 'PAID' : 'UNPAID';

                $payment = Payment::updateOrCreate(
                    ['transaction_id' => $transaction->id],
                    [
                        'payment_status' => $payment_status,
                        'payment_date' => $transaction_date,
                        'payment_amount' => $transaction ? $row[22] : null,
                    ]
                );

                $unit = Unit::firstWhere('unit_code', $row[17]) ?? $otherUnit->id;

                $product = Product::updateOrCreate(
                    ['product_code' => $row[14]],
                    [
                        'product_name' => $row[13],
                        'description' => $row[15],
                        'unit_id' => optional($unit)->id,
                        'price_per_unit' => $row[18],
                    ]
                );

                $dpl_decimal = (float) str_replace('%', '', $row[19]) / 100;
                $transactionDetail = TransactionDetail::updateOrCreate(
                    ['transaction_id' => $transaction->id, 'product_id' => $product->id],
                    [
                        'quantity' => $row[16],
                        'discount_per_line' => $dpl_decimal,
                        'tax_amount' => $row[21],
                        'discount_amount' => $row[24],
                        'discount_percentage' => $row[32],
                        'deduction_amount' => $row[27],
                        'shipping_cost' => $row[26],
                    ]
                );
            } catch (\Exception $e) {
                continue;
            }
        }
    }





    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
