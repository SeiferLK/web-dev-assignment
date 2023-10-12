<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Export') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <form method="POST" action="{{ route('export.file') }}">
                        @csrf
                        <div class="mb-3">
                            <x-input-label for="extension" value="Export Type" />
                            <x-select class="form-select" id="extension" name="extension">
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                            </x-select>
                        </div>

                        <div class="mb-3">
                            <x-input-label for="export-data" value="Export Data" />
                            <x-select class="form-select" id="export-data" name="export-data">
                                <option value="title-author">Title and Author</option>
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                            </x-select>
                        </div>

                        <x-primary-button>Export Data</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
