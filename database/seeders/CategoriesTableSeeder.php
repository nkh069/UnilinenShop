<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'description' => 'Clothing for men',
                'image' => 'men-category.jpg',
                'parent_id' => null,
            ],
            [
                'name' => 'Women',
                'description' => 'Clothing for women',
                'image' => 'women-category.jpg',
                'parent_id' => null,
            ],
            [
                'name' => 'Kids',
                'description' => 'Clothing for kids',
                'image' => 'kids-category.jpg',
                'parent_id' => null,
            ],
            [
                'name' => 'Accessories',
                'description' => 'Fashion accessories',
                'image' => 'accessories-category.jpg',
                'parent_id' => null,
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'parent_id' => $category['parent_id'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }

        // Sub-categories for Men
        $menSubcategories = ['T-shirts', 'Shirts', 'Pants', 'Jeans', 'Suits', 'Jackets'];
        $menCategoryId = Category::where('name', 'Men')->first()->id;

        foreach ($menSubcategories as $index => $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "$name for men",
                'image' => strtolower(str_replace(' ', '-', $name)) . '.jpg',
                'parent_id' => $menCategoryId,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        // Sub-categories for Women
        $womenSubcategories = ['Dresses', 'Tops', 'Skirts', 'Pants', 'Jeans', 'Blouses'];
        $womenCategoryId = Category::where('name', 'Women')->first()->id;

        foreach ($womenSubcategories as $index => $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "$name for women",
                'image' => strtolower(str_replace(' ', '-', $name)) . '.jpg',
                'parent_id' => $womenCategoryId,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        // Sub-categories for Kids
        $kidsSubcategories = ['Boys', 'Girls', 'Infants', 'Teenagers'];
        $kidsCategoryId = Category::where('name', 'Kids')->first()->id;

        foreach ($kidsSubcategories as $index => $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Clothing for $name",
                'image' => strtolower(str_replace(' ', '-', $name)) . '.jpg',
                'parent_id' => $kidsCategoryId,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        // Sub-categories for Accessories
        $accessoriesSubcategories = ['Bags', 'Belts', 'Hats', 'Sunglasses', 'Jewelry', 'Watches'];
        $accessoriesCategoryId = Category::where('name', 'Accessories')->first()->id;

        foreach ($accessoriesSubcategories as $index => $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Fashion $name",
                'image' => strtolower(str_replace(' ', '-', $name)) . '.jpg',
                'parent_id' => $accessoriesCategoryId,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }
}
