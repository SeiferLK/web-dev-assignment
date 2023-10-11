@props(['books'])

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Books
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="bg-white dark:bg-gray-900 p-2">

                            <div class="flex justify-end">

                                <a href="{{ route('books.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent 
                            text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-500 
                            dark:hover:bg-indigo-400 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 
                            transition ease-in-out duration-150">
                                    Add Book
                                </a>
                            </div>

                        </div>

                        <x-table>
                            <x-slot name="head">
                                <x-table-header title="ID" :sortable="true" :href="route('books.index', ['sort' => 'id', 'order' => request('order') === 'desc' ? 'asc' : 'desc'])" />
                                <x-table-header title="Title" :sortable="true" :href="route('books.index', ['sort' => 'title', 'order' => request('order') === 'desc' ? 'asc' : 'desc'])" />
                                <x-table-header title="Author" :sortable="true" :href="route('books.index', ['sort' => 'name', 'order' => request('order') === 'desc' ? 'asc' : 'desc'])" />
                                <x-table-header title="Actions" />
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($books as $book)
                                    <x-table-row>
                                        <x-table-cell>
                                            {{ $book->id }}
                                        </x-table-cell>

                                        <x-table-cell :highlight="true">
                                            {{ $book->title }}
                                        </x-table-cell>

                                        <x-table-cell :highlight="true">
                                            <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                                href="{{ route('authors.edit', $book->author_id) }}">
                                                {{ $book->author->name }}
                                            </a>
                                        </x-table-cell>

                                        <x-table-cell>
                                            <a href="{{ route('books.edit', $book->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            |
                                            <form method="post" action="{{ route('books.destroy', $book->id) }}"
                                                class="inline">
                                                @csrf
                                                @method('delete')

                                                <button type="submit"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                            </form>
                                        </x-table-cell>
                                    </x-table-row>
                                @endforeach
                            </x-slot>
                        </x-table>
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
