<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v6.13.0/css/ol.css" type="text/css">
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v6.13.0/build/ol.js"></script>

<x-app-layout>
    <div class="space-y-8">
        <div>
            <x-breadcrumb :page-title="$pageTitle" :breadcrumb-items="$breadcrumbItems" />
        </div>

        <div class="space-y-5 profile-page">
            <div
                class="profiel-wrap px-[35px] pb-10 md:pt-[84px] pt-10 rounded-lg bg-white dark:bg-slate-800 lg:flex lg:space-y-0
                    space-y-6 justify-between items-end relative z-[1]">
                <div
                    class="bg-slate-900 dark:bg-slate-700 absolute left-0 top-0 md:h-1/2 h-[150px] w-full z-[-1] rounded-t-lg">
                </div>
                <div class="profile-box flex-none md:text-start text-center">
                    <div class="md:flex items-end md:space-x-6 rtl:space-x-reverse">
                        <div class="flex-none">
                            <div
                                class="md:h-[186px] md:w-[186px] h-[140px] w-[140px] md:ml-0 md:mr-0 ml-auto mr-auto md:mb-0 mb-4 rounded-full ring-4
                                    ring-slate-100 relative">
                                <img src="images/avatar.png" alt=""
                                    class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                                Hello, I'm
                            </div>
                            <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                                {{$data["customer"]["name"]}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end profile box -->
                <div
                    class="profile-info-500 md:flex md:text-start text-center flex-1 max-w-[516px] md:space-y-0 space-y-4">
                    <div class="flex-1">
                        <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                            Total Spending
                        </div>
                        <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                            {{ ($data['total_spending']) }}
                         </div>
                    </div>
                    <!-- end single -->
                    {{-- <div class="flex-1">
                        <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                            {{$data["avg_basket_size"]}}
                        </div>
                        <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                            Avg Basket Size
                        </div>
                    </div> --}}
                    <!-- end single -->
                    <div class="flex-1">
                        <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                            Tenure
                        </div>
                        <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                            {{$data["tenure"]}}
                        </div>
                    </div>
                    <!-- end single -->
                </div>
                <!-- profile info-500 -->
            </div>
            <div class="grid grid-cols-12 gap-6">
                <div class="lg:col-span-4 col-span-12">
                    <div class="card h-full">
                        <header class="card-header">
                            <h4 class="card-title">Info</h4>
                        </header>
                        <div class="card-body p-6">
                            <ul class="list space-y-8">
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="heroicons:envelope"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            EMAIL
                                    </div>
                                        <a href="{{ $data["customer"]["email"] ? 'mailto::'.$data["customer"]["name"] : null }}" target="_blank"
                                            class="text-base text-slate-600 dark:text-slate-50">
                                            {{ $data["customer"]["email"] ? $data["customer"]["name"] : '-' }}
                                        </a>
                                    </div>
                                </li>
                                <!-- end single list -->
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="heroicons:phone-arrow-up-right"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            PHONE
                                        </div>
                                        <a href="https://wa.me/{{ $data["customer"]["phone_number"] ? $data["customer"]["phone_number"] : null }}" target="_blank" class="text-base text-slate-600 dark:text-slate-50">
                                            {{ $data["customer"]["phone_number"] ? $data["customer"]["phone_number"] : '-' }}
                                        </a>
                                    </div>
                                </li>
                                <!-- end single list -->
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="heroicons:map"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            SHIPPING ADDRESS
                                        </div>
                                        <div class="text-base text-slate-600 dark:text-slate-50">
                                            {{ $data["customer"]["shipping_address"] ? $data["customer"]["shipping_address"] : '-' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="heroicons:map"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            PROVINCE
                                        </div>
                                        <div class="text-base text-slate-600 dark:text-slate-50">
                                            {{ ucfirst(strtolower($data["customer"]["postal_code"]["province"]["prov_name"])) ? ucfirst(strtolower($data["customer"]["postal_code"]["province"]["prov_name"])) : '-' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                        <iconify-icon icon="heroicons:map"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                            CITY
                                        </div>
                                        <div class="text-base text-slate-600 dark:text-slate-50">
                                            {{ ucfirst(strtolower($data["customer"]["postal_code"]["city"]["city_name"])) ? ucfirst(strtolower($data["customer"]["postal_code"]["city"]["city_name"])) : '-' }}
                                        </div>
                                    </div>
                                </li>
                                <!-- end single list -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-8 col-span-12">
                    <div class=" space-y-5">
                            <div class="card">
                              <header class=" card-header noborder">
                                <h4 class="card-title">Transactions
                                </h4>
                              </header>
                              <div class="card-body px-6 pb-6">
                                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                                  <span class=" col-span-8  hidden"></span>
                                  <span class="  col-span-4 hidden"></span>
                                  <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                      <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                        <thead class=" bg-slate-200 dark:bg-slate-700">
                                          <tr>
                                            <th scope="col" class=" table-th ">
                                              #
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Code
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Date
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Qty
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Amount
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Channel
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                          @foreach($data["customer"]["transactions"] as $item)
                                          <tr>
                                            <td class="table-td">{{ $loop->iteration }}</td>
                                            <td class="table-td ">#{{ $item['transaction_number'] }}</td>
                                            <td class="table-td ">{{ $item['transaction_date'] }}</td>
                                            <td class="table-td ">
                                              <div>
                                                {{ $item['details']->sum('quantity') }}
                                              </div>
                                            </td>
                                            <td class="table-td ">
                                              <div>
                                                IDR {{ number_format($item['payment']['payment_amount'], 0, ',', '.') }}
                                              </div>
                                            </td>
                                            <td class="table-td ">
                                              <div>
                                               {{ $item['transaction_type'] }}
                                              </div>
                                            </td>
                                          </tr>
                                        @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <header class=" card-header noborder">
                                <h4 class="card-title">Products
                                </h4>
                              </header>
                              <div class="card-body px-6 pb-6">
                                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                                  <span class=" col-span-8  hidden"></span>
                                  <span class="  col-span-4 hidden"></span>
                                  <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                      <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                        <thead class=" bg-slate-200 dark:bg-slate-700">
                                          <tr>
                                            <th scope="col" class=" table-th ">
                                              #
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Code
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Date
                                            </th>
                                            <th scope="col" class=" table-th ">
                                              Bought
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                          @foreach($data["purchased_products"] as $item)
                                          <tr>
                                            <td class="table-td">{{ $loop->iteration }}</td>
                                            <td class="table-td ">{{ $item['product_name'] }}</td>
                                            <td class="table-td ">{{ $item['product_code'] }}</td>
                                            <td class="table-td ">
                                              <div>
                                                {{ $item['total_bought'] }}
                                              </div>
                                            </td>
                                          </tr>
                                        @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @vite(['resources/js/custom/chart-active.js'])
    @endpush

    @push('scripts')
        <script type="module">
            // data table validation
            $("#data-table, .data-table").DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: `<iconify-icon icon="ic:round-keyboard-arrow-left"></iconify-icon>`,
                        next: `<iconify-icon icon="ic:round-keyboard-arrow-right"></iconify-icon>`,
                    },
                    search: "Search:",
                },
            });
        </script>
    @endpush
</x-app-layout>
