<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all leaf categories (no children)
        $categories = Category::whereIn('id', function ($query) {
            $query->select('parent_id')
                ->from('categories')
                ->whereNotNull('parent_id');
        })->get();

        $brands = ['Nike', 'Adidas', 'Puma', 'Levi\'s', 'Zara', 'H&M', 'Uniqlo', 'Gap'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Navy', 'Gray', 'Beige', 'Brown', 'Yellow'];
        $materials = ['Cotton', 'Polyester', 'Wool', 'Linen', 'Silk', 'Denim', 'Leather', 'Synthetic'];
        $statuses = ['active', 'active', 'active', 'inactive', 'out_of_stock'];

        foreach ($categories as $category) {
            // Create 5-10 products per category
            $numProducts = rand(5, 10);

            for ($i = 0; $i < $numProducts; $i++) {
                $name = ucfirst($this->getRandomWord(1)) . ' ' . ucfirst($this->getRandomWord(1)) . ' ' . $category->name;
                $slug = Str::slug($name);
                $price = rand(100, 1000) * 1000; // VND price
                $salePrice = rand(0, 1) ? $price * 0.8 : null; // 20% discount for some products
                $sku = strtoupper(substr($category->name, 0, 3)) . '-' . rand(1000, 9999);
                $featured = rand(0, 5) === 0; // 1 in 5 chance to be featured
                $discount = $salePrice ? 20 : 0;
                $status = $statuses[array_rand($statuses)];
                
                // Random selections
                $brand = $brands[array_rand($brands)];
                $material = $materials[array_rand($materials)];
                $weight = rand(100, 1000) / 100; // 0.1 to 10 kg
                
                // Product sizes based on category type
                $productSizes = $this->getSizesForCategory($category->name, $sizes);
                
                // Product colors - randomly select 3-6 colors
                $numColors = rand(3, 6);
                $productColors = [];
                for ($j = 0; $j < $numColors; $j++) {
                    $productColors[] = $colors[array_rand($colors)];
                }
                $productColors = array_unique($productColors);

                $product = Product::create([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "This is a high-quality $material {$category->name} made by $brand. Perfect for any occasion, this {$category->name} offers comfort and style.",
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'category_id' => $category->id,
                    'sku' => $sku,
                    'status' => $status,
                    'featured' => $featured,
                    'sizes' => $productSizes,
                    'colors' => $productColors,
                    'brand' => $brand,
                    'weight' => $weight,
                    'material' => $material,
                    'discount_percent' => $discount,
                ]);

                // Create product images
                $numImages = rand(3, 6);
                for ($j = 0; $j < $numImages; $j++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => "products/{$product->id}/product-" . ($j + 1) . ".jpg",
                        'is_primary' => $j === 0, // First image is primary
                        'sort_order' => $j,
                    ]);
                }

                // Create inventory for each size and color combination
                foreach ($productSizes as $size) {
                    foreach ($productColors as $color) {
                        Inventory::create([
                            'product_id' => $product->id,
                            'size' => $size,
                            'color' => $color,
                            'quantity' => rand(5, 50),
                            'low_stock_threshold' => 5,
                            'in_stock' => true,
                            'location' => 'Warehouse A',
                            'barcode' => $sku . '-' . substr($size, 0, 1) . '-' . substr($color, 0, 1),
                        ]);
                    }
                }
            }
        }
    }

    private function getRandomWord($length)
    {
        $words = [
            'elegant', 'stylish', 'classic', 'modern', 'casual', 'formal', 'premium', 
            'exclusive', 'cool', 'trendy', 'fashion', 'luxury', 'unique', 'comfortable',
            'slim', 'fitted', 'vintage', 'signature', 'summer', 'winter', 'spring',
            'autumn', 'urban', 'outdoor', 'sport', 'elite', 'essential', 'basic',
        ];
        
        $result = [];
        for ($i = 0; $i < $length; $i++) {
            $result[] = $words[array_rand($words)];
        }
        
        return implode(' ', $result);
    }

    private function getSizesForCategory($categoryName, $allSizes)
    {
        $categoryName = strtolower($categoryName);
        
        // For accessories like bags, belts, hats, etc.
        $accessoryCategories = ['bags', 'belts', 'hats', 'sunglasses', 'jewelry', 'watches'];
        if (in_array($categoryName, $accessoryCategories)) {
            switch ($categoryName) {
                case 'belts':
                    return ['28', '30', '32', '34', '36', '38', '40', '42'];
                case 'watches':
                case 'sunglasses':
                    return ['One Size'];
                case 'hats':
                    return ['S', 'M', 'L', 'XL'];
                case 'bags':
                    return ['Small', 'Medium', 'Large'];
                default:
                    return ['One Size'];
            }
        }
        
        // For clothing
        $numSizes = rand(3, count($allSizes));
        $startIndex = rand(0, count($allSizes) - $numSizes);
        
        return array_slice($allSizes, $startIndex, $numSizes);
    }
}
