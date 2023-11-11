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
                    <div class="card-title text-slate-900 dark:text-white">Import Sales File</div>
                  </div>
                </header>
                <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
                <script>
                    // Note that the name "myDropzone" is the camelized
                    // id of the form.
                    Dropzone.options.dropzoneForm = {
                        url: "/utility-import-sales", // Laravel route to handle the upload
            addRemoveLinks: true,
            paramName: "file",
            dictDefaultMessage: "Drop files here or click to upload",
            maxFilesize: 5,
            acceptedFiles: ".xlsx, .xls, .csv",
            uploadMultiple: false,
                      // Configuration options go here
                      init: function() {
                        this.on("addedfile", file => {
                        console.log("A file has been added");
                        });
                        this.on("complete", function(file) {
                        this.removeFile(file);
                        // location.reload();
                        });
                        }
                    };

                  </script>

                <div class="card-text h-full space-y-6">
                    @if (session('success'))
                    <x-alert :message="session('success')" :type="'success'" />
                    @endif
                    @if (session('failed'))
                    <x-alert :message="session('failed')" :type="'danger'" />
                    @endif
                    <form method="POST" action="{{ route('utility.import-sales')}}" role="presentation" tabindex="0" class="flex justify-center dropzone border-none dark:bg-slate-800 border border-secondary-500 rounded-md py-[52px] mx-2  " id="dropzoneForm">
                        @csrf
                        <div class="dz-default dz-message">

                  {{-- <div class="w-full text-center border-dashed flex justify-center items-center"> --}}

                        <button class="dz-button" type="submit">
                          <img src="images/svg/upload.svg" alt="" class="mx-auto mb-4">
                          <p class="text-sm text-slate-500 dark:text-slate-300">Drop files here or click to upload.</p>
                        </button>
                      {{-- </div> --}}
                    </div>
                </form>
                </div>
              </div>
            </div>
            <!-- END: File Dropzone -->
        </div>
    </div>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

</x-app-layout>
