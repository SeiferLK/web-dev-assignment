@props(['book'])

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Book
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">

                <form method="POST" class="flex flex-col gap-y-2 max-w-xl"
                    action="{{ route('books.update', $book->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />

                        <x-text-input id="title" class="block mt-1 w-full" type="text"
                            value="{{ $book->title }}" name="title" required />

                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Author')" />
                        <x-author-input :author="$book->author" />
                    </div>

                    <div class="flex mt-4">
                        <x-primary-button>
                            {{ __('Update') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
