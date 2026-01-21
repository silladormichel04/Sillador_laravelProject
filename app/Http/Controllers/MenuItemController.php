<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    /**
     * Display the dashboard with menu item stats.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category_id') ?: null;

        $query = MenuItem::with('category');

        // Search by name / description
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // Filter by related category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $menuItems = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
            ],
        ]);
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('storeMenuItem', $this->rules());

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('menu-photos', 'public');
        }

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

        if ($request->hasFile('photo')) {
            if ($menuItem->photo) {
                \Storage::disk('public')->delete($menuItem->photo);
            }

            $data['photo'] = $request->file('photo')->store('menu-photos', 'public');
        }

        $menuItem->update($data);

        return back()->with('status', __('Menu item updated successfully.'));
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        if ($menuItem->photo) {
            \Storage::disk('public')->delete($menuItem->photo);
        }

        $menuItem->delete();

        return back()->with('status', __('Menu item deleted successfully.'));
    }

    /**
     * Display soft-deleted menu items (Trash page).
     */
    public function trash(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category_id') ?: null;

        $query = MenuItem::onlyTrashed()->with('category');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $menuItems = $query
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('menu-items-trash', [
            'menuItems' => $menuItems,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
            ],
        ]);
    }

    /**
     * Restore a soft-deleted menu item.
     */
    public function restore(int $menuItem): RedirectResponse
    {
        $item = MenuItem::withTrashed()->findOrFail($menuItem);
        $item->restore();

        return back()->with('status', __('Menu item restored successfully.'));
    }

    /**
     * Permanently delete a soft-deleted menu item.
     */
    public function forceDelete(int $menuItem): RedirectResponse
    {
        $item = MenuItem::withTrashed()->findOrFail($menuItem);

        if ($item->photo) {
            \Storage::disk('public')->delete($item->photo);
        }

        $item->forceDelete();

        return back()->with('status', __('Menu item permanently deleted.'));
    }

    /**
     * Export filtered menu items to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = MenuItem::with('category');

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($categoryId = $request->integer('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $menuItems = $query->latest()->get();

        $pdf = Pdf::loadView('menu-items-pdf', [
            'menuItems' => $menuItems,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'menu-items-'.now()->format('Y-m-d_H-i-s').'.pdf';

        return $pdf->download($fileName);
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
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}

