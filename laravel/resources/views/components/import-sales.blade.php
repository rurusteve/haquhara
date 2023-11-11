<x-app-layout>
    <div class="space-y-8">
        <div>
          <x-breadcrumb :page-title="$pageTitle" :breadcrumb-items="$breadcrumbItems" />
        </div>

        <div class="grid xl:grid-cols-2 grid-cols-1 gap-6">

            <!-- BEGIN: File Dropzone -->
            <div class="card rounded-md bg-white dark:bg-slate-800 lg:h-full shadow-base xl:col-span-2">
              <div class="card-body flex flex-col p-6">
                <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                  <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">File Dropzone</div>
                  </div>
                </header>
                <div class="card-text h-full space-y-6">
                  <div class="w-full text-center border-dashed border border-secondary-500 rounded-md py-[52px] flex justify-center items-center">
                    <form action="/utility-import-sales" role="presentation" tabindex="0" class="dropzone border-none dark:bg-slate-800" id="myUploader">
                      <div class="dz-default dz-message">
                        <button class="dz-button" type="button">
                          <img src="images/svg/upload.svg" alt="" class="mx-auto mb-4">
                          <p class="text-sm text-slate-500 dark:text-slate-300">Drop files here or click to upload.</p>
                        </button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- END: File Dropzone -->
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/plugins/dropzone.min.js'])
        <script type="module">
            Dropzone.autoDiscover = false;
            $("#myUploader").dropzone({
                url: "/",
                dictDefaultMessage: "",
                addRemoveLinks: true,
            });
        </script>
    @endpush
</x-app-layout>
