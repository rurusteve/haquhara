<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;

class TransactionsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    protected $warehouse_id;

    public function __construct($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    // $table->date('transaction_date');
    // $table->integer('transaction_type');
    // $table->string('transaction_number');
    // $table->string('memo')->nullable();
    // $table->date('due_date')->nullable();
    // $table->integer('platform');
    // $table->integer('warehouse_id');
    // $table->integer('customer_id')->nullable();
    public function model(array $row)
    {
        $parts = explode(",", $row[3]);
        $name = trim($parts[0]);
        $phone_number = trim($parts[1]);
        return new Transaction(
            [
                'transaction_date'  => $row[0],
                'transction_type' => $row[1],
                'transaction_number'  => $row[2],
                'memo'  => $row[5],
                'due_date' => $row[6],
                'platform' => $row[11],
                'warehouse_id' => $this->warehouse_id]
        );
    }
}
