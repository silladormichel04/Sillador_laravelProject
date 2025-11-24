<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $items = [
            [
                'name' => 'Burrata Caprese',
                'price' => 12.50,
                'status' => 'available',
                'description' => 'Heirloom tomatoes, basil oil, balsamic pearls.',
                'category_slug' => 'starters',
            ],
            [
                'name' => 'Truffle Fries',
                'price' => 9.00,
                'status' => 'available',
                'description' => 'Hand-cut fries, truffle oil, pecorino.',
                'category_slug' => 'starters',
            ],
            [
                'name' => 'Tuscan Kale Salad',
                'price' => 11.00,
                'status' => 'available',
                'description' => 'Citrus vinaigrette, candied walnuts, pecorino.',
                'category_slug' => 'salads',
            ],
            [
                'name' => 'Margherita Pizza',
                'price' => 15.00,
                'status' => 'available',
                'description' => 'San Marzano sauce, mozzarella, basil.',
                'category_slug' => 'pizzas',
            ],
            [
                'name' => 'Wild Mushroom Pizza',
                'price' => 17.00,
                'status' => 'out_of_stock',
                'description' => 'Roasted mushrooms, fontina, thyme butter.',
                'category_slug' => 'pizzas',
            ],
            [
                'name' => 'Lemon Ricotta Ravioli',
                'price' => 19.50,
                'status' => 'available',
                'description' => 'Brown butter sauce, pine nuts, basil.',
                'category_slug' => 'pastas',
            ],
            [
                'name' => 'Olive Oil Cake',
                'price' => 9.50,
                'status' => 'archived',
                'description' => 'Citrus glaze, mascarpone cream.',
                'category_slug' => 'desserts',
            ],
            [
                'name' => "Chef's Seasonal Special",
                'price' => 23.00,
                'status' => 'available',
                'description' => 'Rotating entree based on market produce.',
                'category_slug' => null,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'status' => $item['status'],
                'description' => $item['description'],
                'category_id' => $item['category_slug']
                    ? ($categories[$item['category_slug']] ?? null)
                    : null,
            ]);
        }
    }
}

