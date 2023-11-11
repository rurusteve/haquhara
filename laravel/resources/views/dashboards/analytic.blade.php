<style>
#map {
    height: 500px;
    min-width: 310px;
    max-width: 800px;
    margin: 0 auto;
}

.loading {
    margin-top: 10em;
    text-align: center;
    color: gray;
}
</style>
<x-app-layout>

    <!-- START:: Breadcrumbs -->
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">Dashboard</h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">

              <div class="flatpickr btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
                <span class="flex items-center input-button" title="toggle" data-toggle>
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:filter"></iconify-icon>
                    {{-- <span>Select Date</span> --}}
                </span>

                <input class="px-2 flatpickr-days" type="text" id="range-picker" data-mode="range" placeholder="{{ __('Select Date') }}" data-input onchange="updateDateRange()"> <!-- input is mandatory -->
                {{-- <input class="form-control py-2 flatpickr flatpickr-input active" id="range-picker" data-mode="range" value="" type="text" readonly="readonly"> --}}
            </div>
        </div>
    </div>
    <!-- END:: Breadcrumbs -->

    <x-home-dashboard :data="$data"/>


    @push('scripts')
    @vite(['resources/js/plugins/flatpickr.js'])
    @vite(['resources/js/plugins/jquery-jvectormap-2.0.5.min.js'])
    @vite(['resources/js/plugins/jquery-jvectormap-world-mill-en.js'])
    @vite(['resources/js/custom/chart-active.js'])
    <script type="module">
        // flatpickr
        $(".flatpickr").flatpickr({
          dateFormat: "F, d Y",
          defaultDate: "today",
          wrap: true,
          mode: "range",
        });
        // flatpickr
        $("#disabled-range-picker").flatpickr({
          mode: "range",
          minDate: "today",
          dateFormat: "Y-m-d",
          disable: [
            function(date) {
              // disable every multiple of 8
              return !(date.getDate() % 8);
            },
          ],
        });

        $(".flatpickr.time").flatpickr({
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
          time_24hr: true,
        });

        $("#humanFriendly_picker").flatpickr({
          altInput: true,
          altFormat: "F j, Y",
          dateFormat: "Y-m-d",
        });

        $("#inline_picker").flatpickr({
          inline: true,
          altInput: true,
          altFormat: "F j, Y",
          dateFormat: "Y-m-d",
        });

      </script>
      <script src="https://code.highcharts.com/maps/highmaps.js"></script>
      <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endpush
</x-app-layout>
