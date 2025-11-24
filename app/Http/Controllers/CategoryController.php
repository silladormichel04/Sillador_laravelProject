<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display the categories management page.
     */
    public function index(): View
    {
        $categories = Category::withCount('menuItems')
            ->orderBy('name')
            ->get();

        return view('categories', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('storeCategory', $this->rules());
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        Category::create($data);

        return back()->with('status', __('Category created successfully.'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validateWithBag(
            'editCategory-' . $category->id,
            $this->rules($category->id)
        );

        $data['slug'] = $this->generateUniqueSlug($data['name'], $category);

        $category->update($data);

        return back()->with('status', __('Category updated successfully.'));
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('status', __('Category deleted successfully.'));
    }

    /**
     * Validation rules shared by store/update.
     */
    protected function rules(?int $ignoreId = null): array
    {
        $nameRule = Rule::unique('categories', 'name');
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
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Generate a unique slug based on the supplied name.
     */
    protected function generateUniqueSlug(string $name, ?Category $ignore = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

