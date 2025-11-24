<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    /**
     * Display the dashboard with menu item stats.
     */
    public function index(): View
    {
        $menuItems = MenuItem::with('category')
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();

        $stats = [
            'totalMenuItems' => MenuItem::count(),
            'activeMenuItems' => MenuItem::where('status', 'available')->count(),
            'totalCategories' => $categories->count(),
        ];

        return view('dashboard', [
            'menuItems' => $menuItems,
            'categories' => $categories,
            'stats' => $stats,
            'statuses' => MenuItem::STATUSES,
        ]);
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('storeMenuItem', $this->rules());

        MenuItem::create($data);

        return back()->with('status', __('Menu item created successfully.'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validateWithBag(
            'editMenuItem-' . $menuItem->id,
            $this->rules($menuItem->id)
        );

        $menuItem->update($data);

        return back()->with('status', __('Menu item updated successfully.'));
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return back()->with('status', __('Menu item deleted successfully.'));
    }

    /**
     * Validation rules shared by store/update.
     */
    protected function rules(?int $ignoreId = null): array
    {
        $nameRule = Rule::unique('menu_items', 'name');
        if ($ignoreId !== null) {
            $nameRule->ignore($ignoreId);
        }

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                $nameRule,
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(MenuItem::STATUSES)],
            'description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }
}

