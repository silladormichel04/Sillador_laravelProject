<x-layouts.app :title="__('Categories')">
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Add category') }}</h2>
                <p class="mb-6 mt-1 text-sm text-neutral-500">{{ __('Group menu items into logical sections.') }}</p>

                <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="category-name" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Name') }}</label>
                        <input
                            id="category-name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                            required
                        />
                        @error('name', 'storeCategory')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category-description" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Description') }} <span class="text-xs text-neutral-400">({{ __('Optional') }})</span></label>
                        <textarea
                            id="category-description"
                            name="description"
                            rows="4"
                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                        >{{ old('description') }}</textarea>
                        @error('description', 'storeCategory')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <flux:button type="submit" icon="plus" class="w-full justify-center">
                        {{ __('Save category') }}
                    </flux:button>
                </form>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 lg:col-span-2">
                <div class="flex flex-col gap-1 border-b border-neutral-200 pb-4 dark:border-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Categories') }}</h2>
                    <p class="text-sm text-neutral-500">{{ __('Edit names and descriptions or remove unused groups.') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="mt-4 w-full min-w-[640px] divide-y divide-neutral-200 text-sm dark:divide-neutral-800">
                        <thead class="text-left text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-4 py-3">{{ __('Name') }}</th>
                                <th class="px-4 py-3">{{ __('Slug') }}</th>
                                <th class="px-4 py-3">{{ __('Description') }}</th>
                                <th class="px-4 py-3">{{ __('Items') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                            @forelse ($categories as $category)
                                @php
                                    $editBag = 'editCategory-' . $category->id;
                                    $editHasErrors = $errors->getBag($editBag)->isNotEmpty();
                                @endphp
                                <tr class="text-neutral-900 dark:text-neutral-100">
                                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                                    <td class="px-4 py-3 text-neutral-500">{{ $category->slug }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $category->description ?? __('N/A') }}</td>
                                    <td class="px-4 py-3">
                                        {{ $category->menu_items_count }}
                                        {{ \Illuminate\Support\Str::plural(__('item'), $category->menu_items_count) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <flux:modal.trigger name="edit-category-{{ $category->id }}">
                                                <flux:button
                                                    variant="ghost"
                                                    size="sm"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'edit-category-{{ $category->id }}')"
                                                >
                                                    {{ __('Edit') }}
                                                </flux:button>
                                            </flux:modal.trigger>

                                            <flux:modal.trigger name="delete-category-{{ $category->id }}">
                                                <flux:button
                                                    variant="danger"
                                                    size="sm"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'delete-category-{{ $category->id }}')"
                                                >
                                                    {{ __('Delete') }}
                                                </flux:button>
                                            </flux:modal.trigger>
                                        </div>
                                    </td>
                                </tr>

                                <flux:modal
                                    name="edit-category-{{ $category->id }}"
                                    :show="$errors->getBag('{{ $editBag }}')->isNotEmpty()"
                                    focusable
                                    class="max-w-xl"
                                >
                                    <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Name') }}</label>
                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $category->name) }}"
                                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                                required
                                            />
                                            @error('name', $editBag)
                                                <p class="text-xs text-rose-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Description') }}</label>
                                            <textarea
                                                name="description"
                                                rows="4"
                                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                            >{{ old('description', $category->description) }}</textarea>
                                            @error('description', $editBag)
                                                <p class="text-xs text-rose-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end gap-3">
                                            <flux:modal.close>
                                                <flux:button variant="ghost">
                                                    {{ __('Cancel') }}
                                                </flux:button>
                                            </flux:modal.close>

                                            <flux:button type="submit">
                                                {{ __('Save changes') }}
                                            </flux:button>
                                        </div>
                                    </form>
                                </flux:modal>

                                <flux:modal name="delete-category-{{ $category->id }}" focusable class="max-w-lg">
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="space-y-6">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Delete this category?') }}</h3>
                                            <p class="mt-2 text-sm text-neutral-500">
                                                {{ __('Deleting this category will set all linked menu items to "N/A".') }}
                                            </p>
                                        </div>

                                        <div class="flex justify-end gap-3">
                                            <flux:modal.close>
                                                <flux:button variant="ghost">
                                                    {{ __('Cancel') }}
                                                </flux:button>
                                            </flux:modal.close>

                                            <flux:button variant="danger" type="submit">
                                                {{ __('Delete') }}
                                            </flux:button>
                                        </div>
                                    </form>
                                </flux:modal>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500">{{ __('No categories yet. Create one to get started.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

