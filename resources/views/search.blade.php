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
                    <h2>Authors</h2>

                    @if ($authors->count() > 0)
                        <ul>
                            @foreach ($authors as $author)
                                <li>
                                    <a href="{{ route('authors.edit', $author) }}">{{ $author->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No authors found.</p>
                    @endif


                    <h2>Books</h2>

                    @if ($books->count() > 0)
                        <ul>
                            @foreach ($books as $book)
                                <li>
                                    <a href="{{ route('books.edit', $book) }}">{{ $book->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No books found.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
