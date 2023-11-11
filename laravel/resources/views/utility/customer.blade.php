<x-app-layout>
    <div class="space-y-8">
        <div>
            <x-breadcrumb :page-title="$pageTitle" :breadcrumb-items="$breadcrumbItems" />
        </div>

        <div class="card">
            <div class="card-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                                id="data-table">
                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <tr>
                                        <th scope="col" class=" table-th ">
                                            #
                                        </th>
                                        <th scope="col" class=" table-th ">
                                            Name
                                        </th>
                                        <th scope="col" class=" table-th ">
                                            Phone Number
                                        </th>
                                        <th scope="col" class=" table-th ">
                                            Avg Basket Size
                                        </th>
                                        <th scope="col" class=" table-th ">
                                            Avg Order Value
                                        </th>
                                        <th scope="col" class=" table-th ">
                                            Tenure
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <a href="{{ 'profile/' . $customer->id }}">
                                                <td class="table-td">{{ $loop->iteration }}</td>
                                                <td class="table-td">
                                                    <span class="flex">
                                                        <a class="btn-link"
                                                            href="{{ 'utility-profile/' . $customer->id }}"
                                                            target="_blank">
                                                            <span
                                                                class="text-sm text-slate-600 dark:text-slate-300 capitalize">{{ $customer['name'] }}</span>
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="table-td ">{{ $customer['phone_number'] }}</td>
                                                <td class="table-td ">
                                                    <div>
                                                        {{ $customer->average_basket_size() }} item
                                                    </div>
                                                </td>
                                                <td class="table-td ">
                                                    <div>
                                                        IDR
                                                        {{ number_format($customer->total_spending(), 0, ',', '.') }}
                                                    </div>
                                            </a>
                                            </td>
                                            <td class="table-td ">
                                                <div>
                                                    {{ $customer->tenure() }}
                                                </div>
                                                </a>
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
