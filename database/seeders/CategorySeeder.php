<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Starters', 'description' => 'Light bites to start the meal.'],
            ['name' => 'Salads', 'description' => 'Fresh and seasonal greens.'],
            ['name' => 'Pizzas', 'description' => 'Wood-fired pies with house-made dough.'],
            ['name' => 'Pastas', 'description' => 'Handmade pasta favorites.'],
            ['name' => 'Desserts', 'description' => 'Sweet endings to every meal.'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => $this->uniqueSlug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

