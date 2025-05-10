<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh mục cha
        $mainCategories = [
            [
                'name' => 'Nam',
                'description' => 'Thời trang dành cho nam giới',
                'image' => 'categories/men.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Nữ',
                'description' => 'Thời trang dành cho nữ giới',
                'image' => 'categories/women.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Trẻ em',
                'description' => 'Thời trang dành cho trẻ em',
                'image' => 'categories/kids.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Phụ kiện',
                'description' => 'Các phụ kiện thời trang',
                'image' => 'categories/accessories.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($mainCategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'parent_id' => null,
            ]);
        }

        // Danh mục con cho Nam
        $menId = Category::where('name', 'Nam')->first()->id;
        $menSubcategories = [
            [
                'name' => 'Áo sơ mi nam',
                'description' => 'Các loại áo sơ mi dành cho nam giới',
                'image' => 'categories/men-shirts.jpg',
                'parent_id' => $menId,
                'is_active' => true,
            ],
            [
                'name' => 'Áo thun nam',
                'description' => 'Các loại áo thun dành cho nam giới',
                'image' => 'categories/men-tshirts.jpg',
                'parent_id' => $menId,
                'is_active' => true,
            ],
            [
                'name' => 'Quần jean nam',
                'description' => 'Các loại quần jean dành cho nam giới',
                'image' => 'categories/men-jeans.jpg',
                'parent_id' => $menId,
                'is_active' => true,
            ],
            [
                'name' => 'Quần kaki nam',
                'description' => 'Các loại quần kaki dành cho nam giới',
                'image' => 'categories/men-pants.jpg',
                'parent_id' => $menId,
                'is_active' => true,
            ],
            [
                'name' => 'Áo khoác nam',
                'description' => 'Các loại áo khoác dành cho nam giới',
                'image' => 'categories/men-jackets.jpg',
                'parent_id' => $menId,
                'is_active' => true,
            ],
        ];

        foreach ($menSubcategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'parent_id' => $category['parent_id'],
            ]);
        }

        // Danh mục con cho Nữ
        $womenId = Category::where('name', 'Nữ')->first()->id;
        $womenSubcategories = [
            [
                'name' => 'Áo sơ mi nữ',
                'description' => 'Các loại áo sơ mi dành cho nữ giới',
                'image' => 'categories/women-shirts.jpg',
                'parent_id' => $womenId,
                'is_active' => true,
            ],
            [
                'name' => 'Áo thun nữ',
                'description' => 'Các loại áo thun dành cho nữ giới',
                'image' => 'categories/women-tshirts.jpg',
                'parent_id' => $womenId,
                'is_active' => true,
            ],
            [
                'name' => 'Quần jean nữ',
                'description' => 'Các loại quần jean dành cho nữ giới',
                'image' => 'categories/women-jeans.jpg',
                'parent_id' => $womenId,
                'is_active' => true,
            ],
            [
                'name' => 'Váy đầm',
                'description' => 'Các loại váy đầm dành cho nữ giới',
                'image' => 'categories/women-dresses.jpg',
                'parent_id' => $womenId,
                'is_active' => true,
            ],
            [
                'name' => 'Áo khoác nữ',
                'description' => 'Các loại áo khoác dành cho nữ giới',
                'image' => 'categories/women-jackets.jpg',
                'parent_id' => $womenId,
                'is_active' => true,
            ],
        ];

        foreach ($womenSubcategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'parent_id' => $category['parent_id'],
            ]);
        }

        // Danh mục con cho Trẻ em
        $kidsId = Category::where('name', 'Trẻ em')->first()->id;
        $kidsSubcategories = [
            [
                'name' => 'Áo bé trai',
                'description' => 'Các loại áo dành cho bé trai',
                'image' => 'categories/boys-shirts.jpg',
                'parent_id' => $kidsId,
                'is_active' => true,
            ],
            [
                'name' => 'Áo bé gái',
                'description' => 'Các loại áo dành cho bé gái',
                'image' => 'categories/girls-shirts.jpg',
                'parent_id' => $kidsId,
                'is_active' => true,
            ],
            [
                'name' => 'Quần bé trai',
                'description' => 'Các loại quần dành cho bé trai',
                'image' => 'categories/boys-pants.jpg',
                'parent_id' => $kidsId,
                'is_active' => true,
            ],
            [
                'name' => 'Váy bé gái',
                'description' => 'Các loại váy dành cho bé gái',
                'image' => 'categories/girls-dresses.jpg',
                'parent_id' => $kidsId,
                'is_active' => true,
            ],
        ];

        foreach ($kidsSubcategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'parent_id' => $category['parent_id'],
            ]);
        }

        // Danh mục con cho Phụ kiện
        $accessoriesId = Category::where('name', 'Phụ kiện')->first()->id;
        $accessoriesSubcategories = [
            [
                'name' => 'Đồng hồ',
                'description' => 'Các loại đồng hồ thời trang',
                'image' => 'categories/watches.jpg',
                'parent_id' => $accessoriesId,
                'is_active' => true,
            ],
            [
                'name' => 'Túi xách',
                'description' => 'Các loại túi xách thời trang',
                'image' => 'categories/bags.jpg',
                'parent_id' => $accessoriesId,
                'is_active' => true,
            ],
            [
                'name' => 'Giày dép',
                'description' => 'Các loại giày dép thời trang',
                'image' => 'categories/shoes.jpg',
                'parent_id' => $accessoriesId,
                'is_active' => true,
            ],
            [
                'name' => 'Mũ nón',
                'description' => 'Các loại mũ nón thời trang',
                'image' => 'categories/hats.jpg',
                'parent_id' => $accessoriesId,
                'is_active' => true,
            ],
        ];

        foreach ($accessoriesSubcategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'parent_id' => $category['parent_id'],
            ]);
        }
    }
} 