@props(['authors', 'books'])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Search Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search results -->
                    <h2 class="text-xl mt-4 mb-2">Authors</h2>

                    @if (count($authors) > 0)
                        <x-table>
                            <x-slot name="head">
                                <x-table-header title="ID" />
                                <x-table-header title="Author" />
                                <x-table-header title="# Books" />
                            </x-slot>
                            <x-slot name="body">

                                @foreach ($authors as $author)
                                    <x-table-row>
                                        <x-table-cell>{{ $author['id'] }}</x-table-cell>

                                        <x-table-cell :highlight="true">
                                            <a href="{{ route('authors.edit', $author['id']) }}" class="text-blue-500">
                                                {!! $author['_formatted']['name'] !!}
                                            </a>
                                        </x-table-cell>

                                        <x-table-cell>
                                            {!! $author['books_count'] !!}
                                        </x-table-cell>

                                    </x-table-row>
                                @endforeach
                            </x-slot>
                        </x-table>
                    @else
                        <p>No authors found.</p>
                    @endif


                    <h2 class="text-xl mt-4 mb-2">Books</h2>

                    @if (count($books) > 0)
                        <x-table>
                            <x-slot name="head">
                                <x-table-header title="ID" />
                                <x-table-header title="Title" />
                                <x-table-header title="Author" />
                            </x-slot>
                            <x-slot name="body">

                                @foreach ($books as $book)
                                    <x-table-row>
                                        <x-table-cell>{{ $book['id'] }}</x-table-cell>

                                        <x-table-cell :highlight="true">
                                            <a href="{{ route('books.edit', $book['id']) }}" class="text-blue-500">
                                                {!! $book['_formatted']['title'] !!}
                                            </a>
                                        </x-table-cell>

                                        <x-table-cell>
                                            {!! $book['_formatted']['author_name'] !!}
                                        </x-table-cell>

                                    </x-table-row>
                                @endforeach
                            </x-slot>
                        </x-table>
                    @else
                        <p>No books found.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
