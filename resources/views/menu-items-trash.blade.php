<x-layouts.app :title="__('Menu Items Trash')">
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-4 border-b border-neutral-200 pb-4 dark:border-neutral-800 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Trash') }}</h2>
                    <p class="text-sm text-neutral-500">{{ __('View and restore deleted menu items or permanently remove them.') }}</p>
                </div>

                <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                    <form method="GET" action="{{ route('menu-items.trash') }}" class="flex flex-1 flex-col gap-2 md:flex-row md:items-center">
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
                                href="{{ route('menu-items.trash') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800"
                            >
                                {{ __('Clear filters') }}
                            </a>
                        </div>
                    </form>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800"
                    >
                        {{ __('Back to menu items') }}
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="mt-4 w-full min-w-[720px] divide-y divide-neutral-200 text-sm dark:divide-neutral-800">
                    <thead class="text-left text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                        <tr>
                            <th class="px-4 py-3">{{ __('Item') }}</th>
                            <th class="px-4 py-3">{{ __('Price') }}</th>
                            <th class="px-4 py-3">{{ __('Category') }}</th>
                            <th class="px-4 py-3">{{ __('Deleted at') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        @forelse ($menuItems as $menuItem)
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
                                <td class="px-4 py-3">{{ $menuItem->category?->name ?? __('N/A') }}</td>
                                <td class="px-4 py-3 text-neutral-500">{{ optional($menuItem->deleted_at)->format('M d, Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('menu-items.restore', $menuItem->id) }}">
                                            @csrf
                                            <flux:button type="submit" size="sm" variant="ghost">
                                                {{ __('Restore') }}
                                            </flux:button>
                                        </form>

                                        <flux:modal.trigger name="force-delete-menu-item-{{ $menuItem->id }}">
                                            <flux:button
                                                variant="danger"
                                                size="sm"
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'force-delete-menu-item-{{ $menuItem->id }}')"
                                            >
                                                {{ __('Delete permanently') }}
                                            </flux:button>
                                        </flux:modal.trigger>
                                    </div>
                                </td>
                            </tr>

                            <flux:modal name="force-delete-menu-item-{{ $menuItem->id }}" focusable class="max-w-lg">
                                <form method="POST" action="{{ route('menu-items.force-delete', $menuItem->id) }}" class="space-y-6">
                                    @csrf
                                    @method('DELETE')

                                    <div>
                                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Permanently delete menu item?') }}</h3>
                                        <p class="mt-2 text-sm text-neutral-500">
                                            {{ __('This action cannot be undone. The menu item and its photo will be permanently removed.') }}
                                        </p>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <flux:modal.close>
                                            <flux:button variant="ghost">
                                                {{ __('Cancel') }}
                                            </flux:button>
                                        </flux:modal.close>

                                        <flux:button variant="danger" type="submit">
                                            {{ __('Delete permanently') }}
                                        </flux:button>
                                    </div>
                                </form>
                            </flux:modal>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500">
                                    {{ __('Trash is empty.') }}
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
</x-layouts.app>


