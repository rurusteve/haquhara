<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\SalesImport;
use App\Imports\TransactionsImport;
use App\Models\City;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Analytic Dashboard
     */
    public function analyticDashboard(Request $request)
    {
        $chartData = $this->getDashboardData($request)->original['data'];

        return view('dashboards.analytic', [
            'pageTitle' => 'Analytic Dashboard',
            'data' => collect($chartData),
        ]);
    }

    public function getDashboardData(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        if (empty($startDate) && empty($endDate)) {
            $startDate = Carbon::now()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $startDateOneMonthBefore = date('Y-m-d', strtotime('-1 month', strtotime($startDate)));
        $totalRevenue = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('payment_amount');
        $totalRevenueOmb = Payment::whereBetween('payment_date', [$startDateOneMonthBefore, $startDate])
            ->sum('payment_amount');

        // dd($totalRevenue);
        $totalRevenueGrowth = (($totalRevenue - $totalRevenueOmb) / ($totalRevenueOmb == 0 ? 1 : $totalRevenueOmb));

        $totalSales = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->count();
        $totalSalesOmb = Transaction::whereBetween('transaction_date', [$startDateOneMonthBefore, $startDate])
            ->count();
        $totalSalesGrowth = (($totalSales - $totalSalesOmb) / ($totalSalesOmb == 0 ? 1 : $totalSalesOmb));

        $nowCustomers = Transaction::whereBetween('transaction_date', [$startDate, $endDate])->groupBy('customer_id')->pluck('customer_id')->toArray();
        $oldCustomers = Transaction::where('transaction_date', '<', $startDate)->groupBy('customer_id')->pluck('customer_id')->toArray();
        $repeatCustomer = count(array_intersect($nowCustomers, $oldCustomers));

        $nowCustomers = Transaction::whereBetween('transaction_date', [$startDateOneMonthBefore, $startDate])->groupBy('customer_id')->pluck('customer_id')->toArray();
        $oldCustomers = Transaction::where('transaction_date', '<', $startDateOneMonthBefore)->groupBy('customer_id')->pluck('customer_id')->toArray();
        $repeatCustomerOmb = count(array_intersect($nowCustomers, $oldCustomers));
        $totalRepeatCustomerGrowth = (($repeatCustomer - $repeatCustomerOmb) / ($repeatCustomerOmb == 0 ? 1 : $repeatCustomerOmb));

        $customersWithMultipleTransactions = Customer::has('transactions', '>', 1)
            ->whereHas('transactions', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            })->count();
        $customersWithMultipleTransactionsOmb = Customer::has('transactions', '>', 1)
            ->whereHas('transactions', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            })->count();
        $customersWithMultipleTransactionsGrowth = (($customersWithMultipleTransactions - $customersWithMultipleTransactionsOmb) / ($customersWithMultipleTransactionsOmb == 0 ? 1 : $customersWithMultipleTransactionsOmb));


        $chartData = [
            'totalRevenue' => [
                'now' => $totalRevenue,
                'growth' => $totalRevenueGrowth
            ],
            'totalSales' => [
                'now' => $totalSales,
                'growth' => $totalSalesGrowth,
            ],
            'totalRepeatCustomer' => [
                'now' => $repeatCustomer,
                'growth' => $totalRepeatCustomerGrowth
            ],
            'totalCustomerTransaction' => [
                'now' => $customersWithMultipleTransactionsGrowth,
                'growth' => $customersWithMultipleTransactionsGrowth
            ]
        ];

        return response()->json(['data' => $chartData]);
    }

    public function getCustomersByProvinces(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $transactions = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        if ((empty($startDate) || empty($endDate)) || $startDate == $endDate) {
            $transactions = Transaction::all();
        }

        $customerCountsByProvince = $transactions->map(function ($transaction) {
            $transaction->load('customer');
            if ($transaction->customer) {
                $transaction->customer->load('postal_code');
                if ($transaction->customer->postal_code) {
                    $transaction->customer->postal_code->load('province');
                    if ($transaction->customer->postal_code->province) {
                        return $transaction->customer->postal_code->province->highcharts_code;
                    }
                }
            }
            return null; // or a default value if needed
        })->groupBy(function ($provinceCode) {
            return $provinceCode;
        })->map(function ($group) {
            return $group->count();
        });


        return response()->json(['data' => $customerCountsByProvince]);
    }

    public function getTopSpenders(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $customers = Customer::with(['postal_code.city', 'transactions.payment'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereHas('transactions', function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            })
            ->get()
            ->sortByDesc(function ($customer) use ($startDate, $endDate) {
                $latestTransactionDate = $customer->transactions
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('transaction_date', [$startDate, $endDate]);
                    })
                    ->max('transaction_date');
                return $latestTransactionDate;
            })
            ->take(10); // Limit the results to the top 10 customers

        $customerList = [];

        foreach ($customers as $customer) {
            $latestTransaction = $customer->transactions
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                })
                ->where('transaction_date', $customer->transactions
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('transaction_date', [$startDate, $endDate]);
                    })
                    ->max('transaction_date'))
                ->first();

            $totalPayment = $latestTransaction->payment->payment_amount;
            $cityName = $customer->postal_code->city->city_name ?? null;

            $customerList[] = [
                'name' => $customer->name,
                'city' => $cityName,
                'total_payment' => $totalPayment,
            ];
        }

        $topSpendersData = json_encode($customerList);


        return response()->json(['data' => $topSpendersData]);
    }

    public function getSalesByChannel(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $transaction_query = Transaction::join('platforms', 'transactions.platform_id', '=', 'platforms.id')
            ->select('platform_name', DB::raw('count(*) as transaction_count'))
            ->groupBy('platform_name')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->get();

        $platformNames = [];
        $counts = [];

        foreach ($transaction_query as $row) {
            $platformNames[] = $row->platform_name;
            $counts[] = $row->transaction_count;
        }

        return response()->json([
            'platform' => $platformNames,
            'count' => $counts
        ]);
    }
    /**
     * Ecommerce Dashboard
     */
    public function ecommerceDashboard()
    {
        $chartData = [
            'revenueReport' => [
                'month' => ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"],
                'revenue' => [
                    'title' => 'Revenue',
                    'data' => [76, 85, 101, 98, 87, 105, 91, 114, 94],
                ],
                'netProfit' => [
                    'title' => 'Net Profit',
                    'data' => [35, 41, 36, 26, 45, 48, 52, 53, 41],
                ],
                'cashFlow' => [
                    'title' => 'Cash Flow',
                    'data' => [44, 55, 57, 56, 61, 58, 63, 60, 66],
                ],
            ],
            'revenue' => [
                'year' => [1991, 1992, 1993, 1994, 1995],
                'data' => [350, 500, 950, 700, 100],
                'total' => 4000,
                'currencySymbol' => '$',
            ],
            'productSold' => [
                'year' => [1991, 1992, 1993, 1994, 1995],
                'quantity' => [800, 600, 1000, 50, 100],
                'total' => 100,
            ],
            'growth' => [
                'year' => [1991, 1992, 1993, 1994, 1995],
                'perYearRate' => [10, 20, 30, 40, 10],
                'total' => 10,
                'preSymbol' => '+',
                'postSymbol' => '%',
            ],
            'lastWeekOrder' => [
                'name' => 'Last Week Order',
                'data' => [44, 55, 57, 56, 61, 10],
                'total' => '10k+',
                'percentage' => 100,
                'preSymbol' => '-',
            ],
            'lastWeekProfit' => [
                'name' => 'Last Week Profit',
                'data' => [44, 55, 57, 56, 61, 10],
                'total' => '10k+',
                'percentage' => 100,
                'preSymbol' => '+',
            ],
            'lastWeekOverview' => [
                'labels' => ["Success", "Return"],
                'data' => [60, 40],
                'title' => 'Profit',
                'amount' => '650k+',
                'percentage' => 0.02,
            ],
        ];
        $topCustomers = [
            [
                'serialNo' => 1,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'green',
                'backgroundColor' => 'sky',
                'isMvpUser' => true,
                'photo' => '/images/customer.png',
            ],
            [
                'serialNo' => 2,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'sky',
                'backgroundColor' => 'orange',
                'isMvpUser' => false,
                'photo' => '/images/customer.png',
            ],
            [
                'serialNo' => 3,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'orange',
                'backgroundColor' => 'green',
                'isMvpUser' => false,
                'photo' => '/images/customer.png',
            ],
            [
                'serialNo' => 4,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'green',
                'backgroundColor' => 'sky',
                'isMvpUser' => true,
                'photo' => '/images/customer.png',
            ],
            [
                'serialNo' => 5,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'sky',
                'backgroundColor' => 'orange',
                'isMvpUser' => false,
                'photo' => '/images/customer.png',
            ],
            [
                'serialNo' => 6,
                'name' => 'Danniel Smith',
                'totalPoint' => 50.5,
                'progressBarPoint' => 50,
                'progressBarColor' => 'orange',
                'backgroundColor' => 'green',
                'isMvpUser' => false,
                'photo' => '/images/customer.png',
            ],
        ];
        $recentOrders = [
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'paid',
            ],
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'paid',
            ],
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'paid',
            ],
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'due',
            ],
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'paid',
            ],
            [
                'companyName' => 'Biffco Enterprises Ltd.',
                'email' => 'Biffco@biffco.com',
                'productType' => 'Technology',
                'invoiceNo' => 'INV-0001',
                'amount' => 1000,
                'currencySymbol' => '$',
                'paymentStatus' => 'due',
            ],
        ];

        return view('dashboards.ecommerce', [
            'pageTitle' => 'Ecommerce Dashboard',
            'data' => $chartData,
            'topCustomers' => $topCustomers,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function importSales(Request $request)
    {
        dd($request);
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx|max:2048', // Adjust the allowed file types and size as needed.
        ]);

        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('excel', $fileName); // Store the file in the 'excel' directory.

            // You can process the uploaded file here, e.g., import data from the Excel file.
            // Example: Excel::import(new YourImportClass, storage_path('app/excel/' . $fileName));

            return redirect()->back()->with('success', 'Excel file uploaded successfully.');
        }

        return redirect()->back()->with('error', 'File upload failed.');

        $filePath = '/Users/jpccdigital/Downloads/sales_list.xlsx';

        Excel::import(new SalesImport(), $filePath);
    }
}
