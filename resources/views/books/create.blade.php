@props(['authors'])

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Add Book
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">

                <form method="POST" class="mt-6 space-y-6 max-w-xl" action="{{ route('books.store') }}">
                    @csrf

                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />

                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" required />

                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="author" :value="__('Author')" />

                        <x-author-input />

                        <x-input-error :messages="$errors->get('author_id')" class="mt-2" />
                    </div>

                    <div class="flex mt-4">
                        <x-primary-button>
                            {{ __('Create book') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</x-app-layout>
