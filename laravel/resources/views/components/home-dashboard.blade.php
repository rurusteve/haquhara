<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .loading {
        z-index: 20;
        display: flex;
        position: absolute;
        justify-content: center;
        top: 0;
        left: -5px;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .loading-content {
        position: absolute;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        top: 30%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div class="grid grid-cols-12 gap-5 mb-5">
    <div class="2xl:col-span-12 lg:col-span-12 col-span-12">
        {{-- <div id="loading-spinner" class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div> --}}
        <section id="loading">
            <div id="loading-content"></div>
        </section>
        <div class="p-4 card" id="my-component-container">
            <div>
                <div class="grid md:grid-cols-4 col-span-1 gap-4">
                    <div class="bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                        <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                            <img src="images/all-img/shade-1.png" alt="" draggable="false"
                                class="w-full h-full object-contain">
                        </div>
                        <span class="block mb-6 text-sm text-slate-900 dark:text-white font-medium">
                            {{ __('Total Revenue') }}
                        </span>
                        <span id="dashboard-total-revenue-now"
                            class="hidden block mb- text-2xl text-slate-900 dark:text-white font-medium mb-6">
                            {{ number_format($data['totalRevenue']['now'], 0) }}
                        </span>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <div class="flex-none text-xl text-primary-500">
                                <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                            </div>
                            <div class="flex-1 text-sm">
                                <span id="dashboard-total-revenue-growth"
                                    class="hidden block mb-[2px] text-primary-500">
                                    {{ floatval(number_format($data['totalRevenue']['growth'], 3)) }}%
                                </span>
                                <span class="block mb-1 text-slate-600 dark:text-slate-300">
                                    From last period
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                        <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                            <img src="images/all-img/shade-2.png" alt="" draggable="false"
                                class="w-full h-full object-contain">
                        </div>
                        <span class="block mb-6 text-sm text-slate-900 dark:text-white font-medium">
                            {{ __('Total Sales') }}
                        </span>
                        <span id="dashboard-total-sales-now"
                            class="hidden block mb- text-2xl text-slate-900 dark:text-white font-medium mb-6">
                            {{ number_format($data['totalSales']['now'], 0) }}
                        </span>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <div class="flex-none text-xl text-primary-500">
                                <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                            </div>
                            <div class="flex-1 text-sm">
                                <span id="dashboard-total-sales-growth" class="hidden block mb-[2px] text-primary-500">
                                    {{ floatval(number_format($data['totalSales']['growth'], 3)) }}%
                                </span>
                                <span class="block mb-1 text-slate-600 dark:text-slate-300">
                                    From last period
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-primary-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                        <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                            <img src="images/all-img/shade-3.png" alt="" draggable="false"
                                class="w-full h-full object-contain">
                        </div>
                        <span class="block mb-6 text-sm text-slate-900 dark:text-white font-medium">
                            {{ __('Returning Customer') }}
                        </span>
                        <span id="dashboard-total-repeat-customer-now"
                            class="hidden block mb- text-2xl text-slate-900 dark:text-white font-medium mb-6">
                            {{ $data['totalRepeatCustomer']['now'] }}
                        </span>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <div class="flex-none text-xl text-primary-500">
                                @if ($data['totalCustomerTransaction']['growth'] < 0)
                                    <iconify-icon icon="heroicons:arrow-trending-down"></iconify-icon>
                                @else
                                    <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                @endif
                            </div>
                            <div class="flex-1 text-sm">
                                <span id="dashboard-total-repeat-customer-growth"
                                    class="hidden block mb-[2px] text-primary-500">
                                    {{ floatval(number_format($data['totalRepeatCustomer']['growth'], 3)) }}%
                                </span>
                                <span class="block mb-1 text-slate-600 dark:text-slate-300">
                                    From last period
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-25 relative z-[1]">
                        <div class="overlay absolute left-0 top-0 w-full h-full z-[-1]">
                            <img src="images/all-img/shade-4.png" alt="" draggable="false"
                                class="w-full h-full object-contain">
                        </div>
                        <span class="block mb-6 text-sm text-slate-900 dark:text-white font-medium">
                            {{ __('Frequent Spender') }}
                        </span>
                        <span id="dashboard-total-customer-transaction-now"
                            class="hidden block mb- text-2xl text-slate-900 dark:text-white font-medium mb-6">
                            {{ number_format($data['totalCustomerTransaction']['now'], 0) }}
                        </span>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <div class="flex-none text-xl text-primary-500">
                                @if ($data['totalCustomerTransaction']['growth'] < 0)
                                    <iconify-icon icon="heroicons:arrow-trending-down"></iconify-icon>
                                @else
                                    <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                @endif
                            </div>
                            <div class="flex-1 text-sm">
                                <span id="dashboard-total-customer-transaction-growth"
                                    class="hidden block mb-[2px] text-primary-500">
                                    {{ floatval(number_format($data['totalCustomerTransaction']['growth'], 3)) }}%
                                </span>
                                <span class="block mb-1 text-slate-600 dark:text-slate-300">
                                    From last period
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-5">

    <div class="lg:col-span-5 col-span-12">
        <div class="card">
            <header class=" card-header">
                <h4 class="card-title">Sales by Channel
                </h4>
            </header>
            <div class="card-body px-6 pb-6">
                <div id="channelSalesChart"></div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-5 col-span-12">
        <div class="card">
            <div class="card-header noborder">
                <h4 class="card-title">Top Spending Customer
                </h4>
            </div>
            <div class="card-body p-6">
                <!-- BEGIN: Order table -->

                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class=" bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            City
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Value
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="top-spender"
                                    class="hidden bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tbody
                                    class="top-spender-list bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END: Order Table -->
            </div>
        </div>
    </div>
    <div class="lg:col-span-7 col-span-12">
        <div class="card">
            <header class="card-header">
                <h4 class="card-title">Customer by Province</h4>
            </header>
            <div class="card-body p-6">
                <div class="hidden" id="map"></div>
            </div>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    function showLoading() {
        document.querySelector('#loading').classList.add('loading');
        document.querySelector('#loading-content').classList.add('loading-content');
    }

    function hideLoading() {
        document.querySelector('#loading').classList.remove('loading');
        document.querySelector('#loading-content').classList.remove('loading-content');
    }


    function formatDate(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based
        const day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    async function getTopology() {
        try {
            const response = await fetch('https://code.highcharts.com/mapdata/countries/id/id-all.topo.json');
            if (response.ok) {
                const topology = await response.json();
                return topology;
            } else {
                throw new Error('Failed to fetch topology data');
            }
        } catch (error) {
            // Handle the error if the fetch fails
            return null;
        }
    }

    async function updateDateRange() {
        $('#top-spender').addClass('hidden');
        $('#map').addClass('hidden');
        showLoading();

        const topology = await getTopology();

        if (!topology) {
            // Handle the case where topology data is not available
            return;
        }

        const selectedDates = flatpickr("#range-picker").selectedDates;

        const currentDate = new Date();
        const startDate = new Date(selectedDates[0]);
        let endDate;

        if (selectedDates.length === 2) {
            endDate = new Date(selectedDates[1]);
        } else {
            endDate = startDate;
        }

        const formattedStartDate = formatDate(startDate);
        const formattedEndDate = formatDate(endDate);

        const growth_endpoint = window.location.origin + '/dashboard-data' + '?start_date=' + formattedStartDate +
            '&end_date=' + formattedEndDate;
        const map_endpoint = window.location.origin + '/customer-by-provinces' + '?start_date=' +
            formattedStartDate + '&end_date=' + formattedEndDate;
        const top_spenders_endpoint = '/top-spenders' + '?start_date=' + formattedStartDate + '&end_date=' +
            formattedEndDate;
        const channel_sales_endpoint = '/channel-sales' + '?start_date=' + formattedStartDate + '&end_date=' +
            formattedEndDate;

        $.ajax({
            url: map_endpoint,
            method: 'GET',
            success: function(data) {
                const map_data = data.data;

                const highchartsData = Object.entries(map_data).map(([key, value]) => [key, value]);

                Highcharts.mapChart('map', {
                    chart: {
                        map: topology,
                    },
                    title: {
                        text: null,
                    },
                    colorAxis: {
                        min: 0,
                    },
                    credits: {
                        enabled: false,
                    },
                    series: [{
                        data: highchartsData,
                        name: 'Data',
                        states: {
                            hover: {
                                color: '#51c793',
                                borderColor: 'none',
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}',
                        },
                    }, ],
                });
            },
            error: function() {
                // Handle errors if the API request fails
            },
        });

        $.ajax({
            url: growth_endpoint,
            method: 'GET',
            success: function(data) {
                // Hide the loading spinner
                $('#dashboard-total-revenue-now').addClass('hidden');
                $('#dashboard-total-revenue-growth').addClass('hidden');
                $('#dashboard-total-sales-now').addClass('hidden');
                $('#dashboard-total-sales-growth').addClass('hidden');
                $('#dashboard-total-repeat-customer-now').addClass('hidden');
                $('#dashboard-total-repeat-customer-growth').addClass('hidden');
                $('#dashboard-total-customer-transaction-now').addClass('hidden');
                $('#dashboard-total-customer-transaction-growth').addClass('hidden');
                $('#top-spender').addClass('hidden');
                $('#map').addClass('hidden');

                $('#dashboard-total-revenue-now').text(parseFloat(data.data.totalRevenue.now)
                    .toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                $('#dashboard-total-revenue-growth').text(data.data.totalRevenue.growth.toFixed(0) +
                    '%');
                $('#dashboard-total-sales-now').text(parseFloat(data.data.totalSales.now)
                    .toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                $('#dashboard-total-sales-growth').text(data.data.totalSales.growth.toFixed(0) + '%');
                $('#dashboard-total-repeat-customer-now').text(parseFloat(data.data.totalRepeatCustomer
                    .now).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#dashboard-total-repeat-customer-growth').text(data.data.totalRepeatCustomer.growth
                    .toFixed(0) + '%');
                $('#dashboard-total-customer-transaction-now').text(parseFloat(data.data
                    .totalCustomerTransaction.now).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#dashboard-total-customer-transaction-growth').text(data.data
                    .totalCustomerTransaction.growth.toFixed(0) + '%');

                // Display the updated data
                $('#dashboard-total-revenue-now').removeClass('hidden');
                $('#dashboard-total-revenue-growth').removeClass('hidden');
                $('#dashboard-total-sales-now').removeClass('hidden');
                $('#dashboard-total-sales-growth').removeClass('hidden');
                $('#dashboard-total-repeat-customer-now').removeClass('hidden');
                $('#dashboard-total-repeat-customer-growth').removeClass('hidden');
                $('#dashboard-total-customer-transaction-now').removeClass('hidden');
                $('#dashboard-total-customer-transaction-growth').removeClass('hidden');
                $('#top-spender').removeClass('hidden');
                $('#map').removeClass('hidden');
            },
            error: function() {
                $('#updateMe').text('Failed to fetch data from the API.');
            }
        });

        $.ajax({
            url: top_spenders_endpoint,
            method: 'GET',
            success: function(data) {
                const top_spenders = JSON.parse(data.data);
                const top_spender_list = $('.top-spender-list');
                top_spender_list.empty();

                if (top_spenders.length === 0) {
                    // Display a message when the data is empty
                    top_spender_list.append(
                        '<tr> class="flex justify-center" <td colspan="3"  scope="col" class=" table-th ">No data available.</td></tr>'
                    );
                } else {
                    top_spenders.forEach(function(customer) {
                        const li = $('<tr>');

                        const tdName = $('<td>').text(customer.name).addClass('table-td');
                        const cityName = customer.city ? customer.city.charAt(0).toUpperCase() +
                            customer.city.slice(1).toLowerCase() :
                            ''; // Capitalize first letter and make the rest lower case
                        const formattedPayment = parseFloat(customer.total_payment)
                            .toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                            });
                        const tdCity = $('<td>').text(cityName || '').addClass('table-td');
                        const tdPayment = $('<td>').text(formattedPayment).addClass('table-td');

                        li.append(tdName, tdCity, tdPayment);
                        top_spender_list.append(li);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Error top spenders')
            }
        });

        $.ajax({
            url: channel_sales_endpoint,
            method: 'GET',
            success: function(data) {
                var platform = data.platform;
                var count = data.count;

                // Create the configuration options for the bar chart
                var options = {
                    chart: {
                        type: "bar",
                        height: 350,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                        },
                    },
                    xaxis: {
                        categories: platform,
                    },
                };

                // Create a data series for the bar chart
                var series = [{
                    name: "Channel Sales",
                    data: count
                }];

                options.series = series; // Assign the data series to options.series

                var chart = new ApexCharts(
                    document.querySelector("#channelSalesChart"),
                    options
                );

                chart.render();
                hideLoading()
            },
            error: function(error) {
                console.log("Error fetching data: " + error);
            }
        });


    };

    $(document).ready(function() {
        updateDateRange();
    });
</script>
