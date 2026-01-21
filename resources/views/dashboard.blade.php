<x-layouts.app :title="__('Menu Items Dashboard')">
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">{{ __('Total menu items') }}</p>
                <p class="mt-2 text-3xl font-semibold text-neutral-900 dark:text-white">{{ $stats['totalMenuItems'] }}</p>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">{{ __('Active menu items') }}</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-600 dark:text-emerald-400">{{ $stats['activeMenuItems'] }}</p>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">{{ __('Total categories') }}</p>
                <p class="mt-2 text-3xl font-semibold text-neutral-900 dark:text-white">{{ $stats['totalCategories'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Add menu item') }}</h2>
                <p class="mb-6 mt-1 text-sm text-neutral-500">{{ __('Create a new dish and assign it to a category.') }}</p>

                <form method="POST" action="{{ route('menu-items.store') }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <label for="name" class="flex items-center justify-between text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            {{ __('Name') }}
                            <span class="text-xs text-rose-500">{{ __('Required') }}</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                            required
                        />
                        @error('name', 'storeMenuItem')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="price" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Price') }}</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="price"
                                name="price"
                                value="{{ old('price') }}"
                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                                required
                            />
                            @error('price', 'storeMenuItem')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Status') }}</label>
                            <select
                                id="status"
                                name="status"
                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                                required
                            >
                                <option value="">{{ __('Select status') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                                @endforeach
                            </select>
                            @error('status', 'storeMenuItem')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="photo" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Photo') }} <span class="text-xs text-neutral-400">({{ __('Optional') }})</span></label>
                            <input
                                type="file"
                                id="photo"
                                name="photo"
                                accept="image/jpeg,image/png"
                                class="mt-1 block w-full text-xs text-neutral-600 file:me-4 file:rounded-md file:border-0 file:bg-neutral-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-neutral-700 hover:file:bg-neutral-200 dark:file:bg-neutral-800 dark:file:text-neutral-100"
                            />
                            <p class="mt-1 text-xs text-neutral-400">{{ __('JPG or PNG, max 2MB.') }}</p>
                            @error('photo', 'storeMenuItem')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="category_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Category') }} <span class="text-xs text-neutral-400">({{ __('Optional') }})</span></label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <option value="">{{ __('No category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id', 'storeMenuItem')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Description') }} <span class="text-xs text-neutral-400">({{ __('Optional') }})</span></label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                        >{{ old('description') }}</textarea>
                        @error('description', 'storeMenuItem')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <flux:button type="submit" icon="plus" class="w-full justify-center">
                        {{ __('Save menu item') }}
                    </flux:button>
                </form>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 lg:col-span-2">
                <div class="flex flex-col gap-4 border-b border-neutral-200 pb-4 dark:border-neutral-800 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Menu items') }}</h2>
                        <p class="text-sm text-neutral-500">{{ __('Manage the dishes available in the restaurant.') }}</p>
                    </div>

                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-1 flex-col gap-2 md:flex-row md:items-center">
                            <div class="flex-1">
                                <label for="search" class="sr-only">{{ __('Search') }}</label>
                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $filters['search'] ?? '' }}"
                                    placeholder="{{ __('Search by name or description') }}"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                                />
                            </div>

                            <div>
                                <label for="filter-category" class="sr-only">{{ __('Category') }}</label>
                                <select
                                    id="filter-category"
                                    name="category_id"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800"
                                >
                                    <option value="">{{ __('All categories') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? null) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <flux:button type="submit" size="sm">
                                    {{ __('Apply') }}
                                </flux:button>

                                <a
                                    href="{{ route('dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800"
                                >
                                    {{ __('Clear filters') }}
                                </a>
                            </div>
                        </form>

                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('menu-items.export-pdf', ['search' => $filters['search'] ?? null, 'category_id' => $filters['category_id'] ?? null]) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800"
                            >
                                {{ __('Export to PDF') }}
                            </a>

                            <a
                                href="{{ route('menu-items.trash') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-rose-200 px-3 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/40"
                            >
                                {{ __('Trash') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="mt-4 w-full min-w-[720px] divide-y divide-neutral-200 text-sm dark:divide-neutral-800">
                        <thead class="text-left text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-4 py-3">{{ __('Item') }}</th>
                                <th class="px-4 py-3">{{ __('Price') }}</th>
                                <th class="px-4 py-3">{{ __('Status') }}</th>
                                <th class="px-4 py-3">{{ __('Category') }}</th>
                                <th class="px-4 py-3">{{ __('Created') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                            @forelse ($menuItems as $menuItem)
                                @php
                                    $editBag = 'editMenuItem-' . $menuItem->id;
                                    $editHasErrors = $errors->getBag($editBag)->isNotEmpty();
                                @endphp
                                <tr class="text-neutral-900 dark:text-neutral-100">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                                                @if ($menuItem->photo_url)
                                                    <img
                                                        src="{{ $menuItem->photo_url }}"
                                                        alt="{{ $menuItem->name }}"
                                                        class="h-full w-full object-cover"
                                                    >
                                                @else
                                                    <span class="flex h-full w-full items-center justify-center text-xs font-semibold text-neutral-700 dark:text-neutral-100">
                                                        {{ $menuItem->initials }}
                                                    </span>
                                                @endif
                                            </span>

                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $menuItem->name }}</span>
                                                @if ($menuItem->description)
                                                    <span class="text-xs text-neutral-500 line-clamp-1">{{ $menuItem->description }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">₱{{ number_format($menuItem->price, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full bg-neutral-100 px-2 py-1 text-xs font-semibold capitalize text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                            {{ str_replace('_', ' ', $menuItem->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $menuItem->category?->name ?? __('N/A') }}</td>
                                    <td class="px-4 py-3 text-neutral-500">{{ $menuItem->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <flux:modal.trigger name="edit-menu-item-{{ $menuItem->id }}">
                                                <flux:button
                                                    variant="ghost"
                                                    size="sm"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'edit-menu-item-{{ $menuItem->id }}')"
                                                >
                                                    {{ __('Edit') }}
                                                </flux:button>
                                            </flux:modal.trigger>

                                            <flux:modal.trigger name="delete-menu-item-{{ $menuItem->id }}">
                                                <flux:button
                                                    variant="danger"
                                                    size="sm"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'delete-menu-item-{{ $menuItem->id }}')"
                                                >
                                                    {{ __('Delete') }}
                                                </flux:button>
                                            </flux:modal.trigger>
                                        </div>
                                    </td>
                                </tr>

                                <flux:modal
                                    name="edit-menu-item-{{ $menuItem->id }}"
                                    :show="$errors->getBag('{{ $editBag }}')->isNotEmpty()"
                                    focusable
                                    class="max-w-2xl"
                                >
                                    <form
                                        method="POST"
                                        action="{{ route('menu-items.update', $menuItem) }}"
                                        class="space-y-4"
                                        enctype="multipart/form-data"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <div class="space-y-1">
                                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Name') }}</label>
                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $menuItem->name) }}"
                                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                                required
                                            />
                                            @error('name', $editBag)
                                                <p class="text-xs text-rose-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Price') }}</label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="price"
                                                    value="{{ old('price', $menuItem->price) }}"
                                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                                    required
                                                />
                                                @error('price', $editBag)
                                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Status') }}</label>
                                                <select
                                                    name="status"
                                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                                    required
                                                >
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status }}" @selected(old('status', $menuItem->status) === $status)>
                                                            {{ \Illuminate\Support\Str::headline($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status', $editBag)
                                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                                                    {{ __('Photo') }}
                                                    <span class="text-xs text-neutral-400">({{ __('Optional') }})</span>
                                                </label>

                                                <div class="mt-1 flex items-center gap-3">
                                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                                                        @if ($menuItem->photo_url)
                                                            <img
                                                                src="{{ $menuItem->photo_url }}"
                                                                alt="{{ $menuItem->name }}"
                                                                class="h-full w-full object-cover"
                                                            >
                                                        @else
                                                            <span class="flex h-full w-full items-center justify-center text-xs font-semibold text-neutral-700 dark:text-neutral-100">
                                                                {{ $menuItem->initials }}
                                                            </span>
                                                        @endif
                                                    </span>

                                                    <input
                                                        type="file"
                                                        name="photo"
                                                        accept="image/jpeg,image/png"
                                                        class="block w-full text-xs text-neutral-600 file:me-4 file:rounded-md file:border-0 file:bg-neutral-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-neutral-700 hover:file:bg-neutral-200 dark:file:bg-neutral-800 dark:file:text-neutral-100"
                                                    />
                                                </div>

                                                <p class="mt-1 text-xs text-neutral-400">{{ __('JPG or PNG, max 2MB.') }}</p>
                                                @error('photo', $editBag)
                                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Category') }}</label>
                                            <select
                                                name="category_id"
                                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                            >
                                                <option value="">{{ __('No category') }}</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected(old('category_id', $menuItem->category_id) == $category->id)>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id', $editBag)
                                                <p class="text-xs text-rose-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Description') }}</label>
                                            <textarea
                                                name="description"
                                                rows="4"
                                                class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-800"
                                            >{{ old('description', $menuItem->description) }}</textarea>
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

                                <flux:modal name="delete-menu-item-{{ $menuItem->id }}" focusable class="max-w-lg">
                                    <form method="POST" action="{{ route('menu-items.destroy', $menuItem) }}" class="space-y-6">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Delete menu item?') }}</h3>
                                            <p class="mt-2 text-sm text-neutral-500">
                                                {{ __('Are you sure you want to delete this menu item? This action cannot be undone.') }}
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
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500">
                                        {{ __('No menu items found. Start by creating one on the left.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $menuItems->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
