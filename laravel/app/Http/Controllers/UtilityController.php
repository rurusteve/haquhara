<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Imports\SalesImport;
use App\Imports\TransactionsImport;
use Maatwebsite\Excel\Facades\Excel;
class UtilityController extends Controller
{

    /**
     * Invoice
     */
    public function transaction()
    {
        $customers = Customer::with(['transactions.payment', 'transactions.details'])->get();

        $tableData = [];

        foreach ($customers as $customer) {
            $tenure = $customer->tenure();

            $total_quantity = $customer->transactions->flatMap(function ($transaction) {
                return $transaction->details;
            })->sum('quantity');

            $total_payment_amount = $customer->transactions->sum(function ($transaction) {
                return $transaction->payment->payment_amount;
            });

            $total_transaction = $customer->transactions->count();

            $totals = [
                'customer' => $customer,
                'tenure' => $tenure,
                'total_quantity' => $total_quantity,
                'total_payment_amount' => $total_payment_amount,
                'total_transaction' => $total_transaction,
                // 'platform' => $customer->transactions['transaction']
            ];

            $tableData[] = $totals;
        }


        $breadcrumbsItems = [
            [
                'name' => 'Transaction',
                'url' => '/transaction',
                'active' => true
            ],

        ];
        return view('utility.transaction', [
            'tableData' => $tableData,
            'pageTitle' => 'Transactions',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }

/**
     * Invoice
     */
    public function product()
    {
        $products = Product::with(['unit'])->get();

        $breadcrumbsItems = [
            [
                'name' => 'Products,',
                'url' => '/product',
                'active' => true
            ],

        ];
        return view('utility.product', [
            'tableData' => $products,
            'pageTitle' => 'Product',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }

    public function customer()
    {
        $customers = Customer::with('transactions.details', 'transactions.payment')->get();

        $breadcrumbsItems = [
            [
                'name' => 'Customers,',
                'url' => '/customer',
                'active' => true
            ],

        ];
        return view('utility.customer', compact('customers'), [
            'customers' => $customers,
            'pageTitle' => 'Customer',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }

    public function uploadDataForm()
    {
        $breadcrumbsItems = [
            [
                'name' => 'Upload Data Form',
                'url' => '/upload-data-form',
                'active' => true
            ],

        ];
        return view('utility.upload-data-form', [
            'pageTitle' => 'Upload Data Form',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }
    /**
     * Pricing
     */
    public function pricing()
    {
        $breadcrumbsItems = [
            [
                'name' => 'Pricing',
                'url' => '/pricing',
                'active' => true
            ],

        ];
        return view('utility.pricing', [
            'pageTitle' => 'Pricing',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }

    /**
     * Blog
     */
    public function blog()
    {
        $breadcrumbsItems = [
            [
                'name' => 'Blog',
                'url' => '/blog',
                'active' => true
            ],

        ];
        return view('utility.blog', [
            'pageTitle' => 'Blog',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }

    /**
     * Blog Details
     */
    public function blogDetails()
    {
        $breadcrumbsItems = [
            [
                'name' => 'Blog Details',
                'url' => '/blog-details',
                'active' => true
            ],

        ];
        return view('utility.blog-details', [
            'pageTitle' => 'Blog Details',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }



    /**
     * Blog Details
     */
    public function blank()
    {
        $breadcrumbsItems = [
            [
                'name' => 'Blank',
                'url' => '/blank',
                'active' => true
            ],

        ];
        return view('utility.blank', [
            'pageTitle' => 'Blank',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }



    /**
     * Profile
     */
    public function profile($id)
    {
        $customer = Customer::with('transactions.payment', 'postal_code.city')
    ->find($id);

    $total_spending = $customer->total_spending();
    $avg_basket_size = $customer->average_basket_size();
    $purchased_products = $customer->purchased_products();
        $data = [
            'customer' => $customer,
            'total_spending' => 'IDR '.number_format($total_spending, 2, '.', ','),
            'avg_basket_size' => $avg_basket_size,
            'tenure' => $customer->tenure(),
            'purchased_products' => $purchased_products
        ];
        $breadcrumbsItems = [
            [
                'name' => 'Profile',
                'url' => '/profile',
                'active' => true
            ],

        ];

        $tableData = [
            [
              "id"=> 1,
              "order"=> 951,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/26/2022",
              "quantity"=> 13,
              "amount"=> "$1779.53",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 2,
              "order"=> 238,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/6/2022",
              "quantity"=> 13,
              "amount"=> "$2215.78",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 3,
              "order"=> 339,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "9/6/2021",
              "quantity"=> 1,
              "amount"=> "$3183.60",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 4,
              "order"=> 365,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/7/2021",
              "quantity"=> 13,
              "amount"=> "$2587.86",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 5,
              "order"=> 513,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "5/6/2022",
              "quantity"=> 12,
              "amount"=> "$3840.73",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 6,
              "order"=> 534,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/14/2022",
              "quantity"=> 12,
              "amount"=> "$4764.18",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 7,
              "order"=> 77,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "7/30/2022",
              "quantity"=> 6,
              "amount"=> "$2875.05",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 8,
              "order"=> 238,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "6/30/2022",
              "quantity"=> 9,
              "amount"=> "$2491.02",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 9,
              "order"=> 886,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "8/9/2022",
              "quantity"=> 8,
              "amount"=> "$3006.95",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 10,
              "order"=> 3,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "8/4/2022",
              "quantity"=> 12,
              "amount"=> "$2160.32",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 11,
              "order"=> 198,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "4/5/2022",
              "quantity"=> 8,
              "amount"=> "$1272.66",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 12,
              "order"=> 829,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "8/9/2022",
              "quantity"=> 2,
              "amount"=> "$4327.86",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 13,
              "order"=> 595,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/10/2022",
              "quantity"=> 11,
              "amount"=> "$3671.81",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 14,
              "order"=> 374,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/10/2022",
              "quantity"=> 2,
              "amount"=> "$3401.82",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 15,
              "order"=> 32,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "5/20/2022",
              "quantity"=> 4,
              "amount"=> "$2387.49",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 16,
              "order"=> 89,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "5/3/2022",
              "quantity"=> 15,
              "amount"=> "$4236.61",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 17,
              "order"=> 912,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "10/31/2021",
              "quantity"=> 11,
              "amount"=> "$2975.66",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 18,
              "order"=> 621,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "1/13/2022",
              "quantity"=> 5,
              "amount"=> "$4576.13",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 19,
              "order"=> 459,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "6/14/2022",
              "quantity"=> 5,
              "amount"=> "$1276.56",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 20,
              "order"=> 108,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "10/8/2021",
              "quantity"=> 4,
              "amount"=> "$1078.64",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 21,
              "order"=> 492,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/17/2021",
              "quantity"=> 9,
              "amount"=> "$1678.19",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 22,
              "order"=> 42,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "4/4/2022",
              "quantity"=> 9,
              "amount"=> "$1822.02",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 23,
              "order"=> 841,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/21/2022",
              "quantity"=> 5,
              "amount"=> "$1578.39",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 24,
              "order"=> 561,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "6/18/2022",
              "quantity"=> 12,
              "amount"=> "$2130.49",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 25,
              "order"=> 720,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "8/15/2022",
              "quantity"=> 8,
              "amount"=> "$3721.11",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 26,
              "order"=> 309,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "4/28/2022",
              "quantity"=> 8,
              "amount"=> "$4683.45",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 27,
              "order"=> 24,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "9/6/2021",
              "quantity"=> 7,
              "amount"=> "$2863.71",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 28,
              "order"=> 518,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "9/11/2021",
              "quantity"=> 4,
              "amount"=> "$3879.41",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 29,
              "order"=> 98,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "1/27/2022",
              "quantity"=> 5,
              "amount"=> "$4660.81",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 30,
              "order"=> 940,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "9/16/2021",
              "quantity"=> 6,
              "amount"=> "$4800.75",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 31,
              "order"=> 925,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "1/8/2022",
              "quantity"=> 1,
              "amount"=> "$2299.05",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 32,
              "order"=> 122,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/18/2021",
              "quantity"=> 1,
              "amount"=> "$3578.02",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 33,
              "order"=> 371,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/30/2022",
              "quantity"=> 13,
              "amount"=> "$1996.06",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 34,
              "order"=> 296,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/13/2021",
              "quantity"=> 5,
              "amount"=> "$2749.00",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 35,
              "order"=> 887,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "12/7/2021",
              "quantity"=> 11,
              "amount"=> "$4353.01",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 36,
              "order"=> 30,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "9/9/2021",
              "quantity"=> 15,
              "amount"=> "$3252.37",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 37,
              "order"=> 365,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/12/2022",
              "quantity"=> 5,
              "amount"=> "$4044.10",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 38,
              "order"=> 649,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/6/2022",
              "quantity"=> 5,
              "amount"=> "$3859.92",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 39,
              "order"=> 923,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "7/25/2022",
              "quantity"=> 14,
              "amount"=> "$1652.47",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 40,
              "order"=> 423,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/2/2022",
              "quantity"=> 8,
              "amount"=> "$2700.12",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 41,
              "order"=> 703,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "12/8/2021",
              "quantity"=> 8,
              "amount"=> "$4508.13",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 42,
              "order"=> 792,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/22/2021",
              "quantity"=> 11,
              "amount"=> "$4938.04",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 43,
              "order"=> 400,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "4/6/2022",
              "quantity"=> 1,
              "amount"=> "$3471.32",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 44,
              "order"=> 718,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "2/4/2022",
              "quantity"=> 4,
              "amount"=> "$4011.60",
              "status"=> "paid",
              "action"=> null
            ],
            [
              "id"=> 45,
              "order"=> 970,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "3/30/2022",
              "quantity"=> 15,
              "amount"=> "$3723.64",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 46,
              "order"=> 786,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/20/2021",
              "quantity"=> 2,
              "amount"=> "$2441.15",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 47,
              "order"=> 925,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "10/24/2021",
              "quantity"=> 11,
              "amount"=> "$1196.76",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 48,
              "order"=> 929,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "6/30/2022",
              "quantity"=> 10,
              "amount"=> "$3579.57",
              "status"=> "cancled",
              "action"=> null
            ],
            [
              "id"=> 49,
              "order"=> 377,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "11/16/2021",
              "quantity"=> 4,
              "amount"=> "$2657.84",
              "status"=> "due",
              "action"=> null
            ],
            [
              "id"=> 50,
              "order"=> 661,
              "customer"=> [
                "name"=> "Jenny Wilson",
                "image"=> "customer_1.png"
              ],
              "date"=> "8/15/2022",
              "quantity"=> 6,
              "amount"=> "$2905.94",
              "status"=> "paid",
              "action"=> null
            ]
        ];

        return view('utility.profile', [
            'pageTitle' => 'Profile',
            'breadcrumbItems' => $breadcrumbsItems,
            'data' => $data,
            'tableData' => $tableData
        ]);
    }




    /**
     * error404
     */
    public function error404()
    {
        $breadcrumbsItems = [
            [
                'name' => 'error404',
                'url' => '/utility-404',
                'active' => true
            ],

        ];
        return view('utility.404', [
            'pageTitle' => 'Error 404',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }




    /**
     * Coming soon
     */
    public function comingSoon()
    {
        $breadcrumbsItems = [
            [
                'name' => 'comingSoon',
                'url' => '/coming-soon',
                'active' => true
            ],

        ];
        return view('utility.coming-soon', [
            'pageTitle' => 'Coming Soon',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }



    /**
     * Under maintenance
     */
    public function underMaintenance()
    {
        $breadcrumbsItems = [
            [
                'name' => 'underMaintenance',
                'url' => '/under-maintenance',
                'active' => true
            ],

        ];
        return view('utility.under-maintenance', [
            'pageTitle' => 'Under Maintenance',
            'breadcrumbItems' => $breadcrumbsItems,
        ]);
    }
    // public function importSales(Request $request)
    public function importSales(Request $request)
    {
        try{
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                Excel::import(new SalesImport(), $file);
            }
            return to_route('utility.upload-data-form')->with('success', 'Data successfully uploaded');
        }catch (\Exception $e){
            return to_route('utility.upload-data-form')->with('failed', 'Failed to upload data');
        }
    }
}

