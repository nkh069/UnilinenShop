<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID các danh mục
        $menShirtsCategoryId = Category::where('name', 'Áo sơ mi nam')->first()->id;
        $menTshirtsCategoryId = Category::where('name', 'Áo thun nam')->first()->id;
        $menJeansCategoryId = Category::where('name', 'Quần jean nam')->first()->id;
        $menPantsCategoryId = Category::where('name', 'Quần kaki nam')->first()->id;
        $menJacketsCategoryId = Category::where('name', 'Áo khoác nam')->first()->id;
        
        $womenShirtsCategoryId = Category::where('name', 'Áo sơ mi nữ')->first()->id;
        $womenTshirtsCategoryId = Category::where('name', 'Áo thun nữ')->first()->id;
        $womenJeansCategoryId = Category::where('name', 'Quần jean nữ')->first()->id;
        $womenDressesCategoryId = Category::where('name', 'Váy đầm')->first()->id;
        $womenJacketsCategoryId = Category::where('name', 'Áo khoác nữ')->first()->id;

        // Sản phẩm áo sơ mi nam
        $menShirts = [
            [
                'name' => 'Áo sơ mi nam Oxford dài tay',
                'description' => 'Áo sơ mi nam Oxford dài tay, chất liệu cotton 100%, form regular fit thoải mái, dễ phối đồ. Phù hợp với nhiều dịp khác nhau như đi làm, đi chơi, hẹn hò.',
                'price' => 450000,
                'sale_price' => 399000,
                'category_id' => $menShirtsCategoryId,
                'sku' => 'MSH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Trắng', 'Xanh nhạt', 'Xanh đậm', 'Đen'],
                'brand' => 'Routine',
                'weight' => 0.3,
                'material' => 'Cotton Oxford',
                'discount_percent' => 11,
                'images' => [
                    'products/men/shirts/oxford-1.jpg',
                    'products/men/shirts/oxford-2.jpg',
                    'products/men/shirts/oxford-3.jpg',
                ]
            ],
            [
                'name' => 'Áo sơ mi nam công sở slimfit',
                'description' => 'Áo sơ mi nam dáng slimfit, chất liệu cao cấp, dễ ủi, ít nhăn. Thiết kế đơn giản, lịch sự, phù hợp mặc đi làm, đi sự kiện.',
                'price' => 390000,
                'sale_price' => 350000,
                'category_id' => $menShirtsCategoryId,
                'sku' => 'MSH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Trắng', 'Xanh', 'Đen', 'Ghi'],
                'brand' => 'The Blues',
                'weight' => 0.25,
                'material' => 'Cotton pha polyester',
                'discount_percent' => 10,
                'images' => [
                    'products/men/shirts/slimfit-1.jpg',
                    'products/men/shirts/slimfit-2.jpg',
                ]
            ],
            [
                'name' => 'Áo sơ mi nam họa tiết',
                'description' => 'Áo sơ mi nam in họa tiết hiện đại, trẻ trung. Chất liệu thoáng mát, thấm hút mồ hôi tốt. Phù hợp mặc đi chơi, dạo phố.',
                'price' => 420000,
                'sale_price' => null,
                'category_id' => $menShirtsCategoryId,
                'sku' => 'MSH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh họa tiết', 'Trắng họa tiết', 'Đen họa tiết'],
                'brand' => 'Owen',
                'weight' => 0.28,
                'material' => 'Cotton pha',
                'discount_percent' => 0,
                'images' => [
                    'products/men/shirts/pattern-1.jpg',
                    'products/men/shirts/pattern-2.jpg',
                ]
            ],
        ];

        // Sản phẩm áo thun nam
        $menTshirts = [
            [
                'name' => 'Áo thun nam cổ tròn basic',
                'description' => 'Áo thun nam cổ tròn basic, chất liệu cotton 100%, form regular fit thoải mái. Thiết kế đơn giản, dễ phối đồ, phù hợp mặc hàng ngày.',
                'price' => 199000,
                'sale_price' => 150000,
                'category_id' => $menTshirtsCategoryId,
                'sku' => 'MTH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Trắng', 'Đen', 'Xám', 'Xanh navy'],
                'brand' => 'Coolmate',
                'weight' => 0.2,
                'material' => 'Cotton 100%',
                'discount_percent' => 25,
                'images' => [
                    'products/men/tshirts/basic-1.jpg',
                    'products/men/tshirts/basic-2.jpg',
                ]
            ],
            [
                'name' => 'Áo thun nam in hình',
                'description' => 'Áo thun nam in hình grafic hiện đại, chất liệu cotton cao cấp, mềm mại, thoáng mát. Phù hợp mặc đi chơi, dạo phố.',
                'price' => 250000,
                'sale_price' => 225000,
                'category_id' => $menTshirtsCategoryId,
                'sku' => 'MTH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xám'],
                'brand' => 'Routine',
                'weight' => 0.22,
                'material' => 'Cotton 100%',
                'discount_percent' => 10,
                'images' => [
                    'products/men/tshirts/graphic-1.jpg',
                    'products/men/tshirts/graphic-2.jpg',
                ]
            ],
            [
                'name' => 'Áo polo nam thể thao',
                'description' => 'Áo polo nam thể thao, chất liệu coolmax thấm hút mồ hôi tốt, nhanh khô. Phù hợp mặc khi chơi thể thao hoặc đi chơi.',
                'price' => 300000,
                'sale_price' => 270000,
                'category_id' => $menTshirtsCategoryId,
                'sku' => 'MTH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Xanh biển', 'Trắng', 'Đen', 'Đỏ'],
                'brand' => 'Coolmate',
                'weight' => 0.24,
                'material' => 'Coolmax',
                'discount_percent' => 10,
                'images' => [
                    'products/men/tshirts/polo-1.jpg',
                    'products/men/tshirts/polo-2.jpg',
                ]
            ],
        ];

        // Sản phẩm quần jean nam
        $menJeans = [
            [
                'name' => 'Quần jean nam slimfit',
                'description' => 'Quần jean nam slimfit, chất liệu denim co giãn thoải mái. Màu xanh wash nhẹ, dễ phối đồ.',
                'price' => 550000,
                'sale_price' => 495000,
                'category_id' => $menJeansCategoryId,
                'sku' => 'MJE-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['29', '30', '31', '32', '33', '34'],
                'colors' => ['Xanh đậm', 'Xanh nhạt', 'Đen'],
                'brand' => 'Routine',
                'weight' => 0.5,
                'material' => 'Denim co giãn',
                'discount_percent' => 10,
                'images' => [
                    'products/men/jeans/slimfit-1.jpg',
                    'products/men/jeans/slimfit-2.jpg',
                ]
            ],
            [
                'name' => 'Quần jean nam rách',
                'description' => 'Quần jean nam rách gối, form regular fit. Chất liệu denim dày dặn, đứng form.',
                'price' => 480000,
                'sale_price' => null,
                'category_id' => $menJeansCategoryId,
                'sku' => 'MJE-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['30', '31', '32', '33', '34'],
                'colors' => ['Xanh nhạt', 'Xanh đậm'],
                'brand' => 'The Blues',
                'weight' => 0.55,
                'material' => 'Denim',
                'discount_percent' => 0,
                'images' => [
                    'products/men/jeans/ripped-1.jpg',
                    'products/men/jeans/ripped-2.jpg',
                ]
            ],
        ];

        // Sản phẩm áo sơ mi nữ
        $womenShirts = [
            [
                'name' => 'Áo sơ mi nữ công sở tay dài',
                'description' => 'Áo sơ mi nữ công sở tay dài, chất liệu lụa mềm mại, thoáng mát. Thiết kế đơn giản, thanh lịch, phù hợp mặc đi làm, đi sự kiện.',
                'price' => 420000,
                'sale_price' => 380000,
                'category_id' => $womenShirtsCategoryId,
                'sku' => 'WSH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Hồng nhạt', 'Xanh nhạt', 'Be'],
                'brand' => 'Ivy Moda',
                'weight' => 0.2,
                'material' => 'Lụa cao cấp',
                'discount_percent' => 10,
                'images' => [
                    'products/women/shirts/office-1.jpg',
                    'products/women/shirts/office-2.jpg',
                ]
            ],
            [
                'name' => 'Áo kiểu nữ cổ V',
                'description' => 'Áo kiểu nữ cổ V, chất liệu voan nhẹ nhàng, thoáng mát. Thiết kế nữ tính, thanh lịch, phù hợp mặc đi làm, đi chơi.',
                'price' => 350000,
                'sale_price' => 315000,
                'category_id' => $womenShirtsCategoryId,
                'sku' => 'WSH-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Trắng', 'Hồng', 'Xanh mint'],
                'brand' => 'Elise',
                'weight' => 0.18,
                'material' => 'Voan',
                'discount_percent' => 10,
                'images' => [
                    'products/women/shirts/vneck-1.jpg',
                    'products/women/shirts/vneck-2.jpg',
                ]
            ],
        ];

        // Sản phẩm váy đầm
        $womenDresses = [
            [
                'name' => 'Đầm xòe nữ công sở',
                'description' => 'Đầm xòe nữ công sở, chất liệu tuyết mưa cao cấp, form dáng thanh lịch. Phù hợp mặc đi làm, đi sự kiện.',
                'price' => 550000,
                'sale_price' => 495000,
                'category_id' => $womenDressesCategoryId,
                'sku' => 'WDR-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh navy', 'Đỏ đô'],
                'brand' => 'Ivy Moda',
                'weight' => 0.35,
                'material' => 'Tuyết mưa',
                'discount_percent' => 10,
                'images' => [
                    'products/women/dresses/aline-1.jpg',
                    'products/women/dresses/aline-2.jpg',
                ]
            ],
            [
                'name' => 'Đầm maxi nữ dự tiệc',
                'description' => 'Đầm maxi nữ dự tiệc, chất liệu voan cao cấp, thiết kế sang trọng, quyến rũ. Phù hợp mặc đi dự tiệc, sự kiện.',
                'price' => 750000,
                'sale_price' => 675000,
                'category_id' => $womenDressesCategoryId,
                'sku' => 'WDR-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Đỏ đô', 'Xanh navy', 'Đen'],
                'brand' => 'Elise',
                'weight' => 0.4,
                'material' => 'Voan cao cấp',
                'discount_percent' => 10,
                'images' => [
                    'products/women/dresses/maxi-1.jpg',
                    'products/women/dresses/maxi-2.jpg',
                ]
            ],
        ];

        // Sản phẩm áo khoác
        $jackets = [
            [
                'name' => 'Áo khoác denim nam',
                'description' => 'Áo khoác denim nam, chất liệu denim dày dặn, bền đẹp. Form regular fit thoải mái, dễ phối đồ.',
                'price' => 680000,
                'sale_price' => 612000,
                'category_id' => $menJacketsCategoryId,
                'sku' => 'MJA-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => true,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Xanh đậm', 'Xanh nhạt', 'Đen'],
                'brand' => 'Routine',
                'weight' => 0.8,
                'material' => 'Denim',
                'discount_percent' => 10,
                'images' => [
                    'products/men/jackets/denim-1.jpg',
                    'products/men/jackets/denim-2.jpg',
                ]
            ],
            [
                'name' => 'Áo khoác bomber nữ',
                'description' => 'Áo khoác bomber nữ, chất liệu nỉ dày dặn, giữ ấm tốt. Thiết kế trẻ trung, năng động, dễ phối đồ.',
                'price' => 550000,
                'sale_price' => 495000,
                'category_id' => $womenJacketsCategoryId,
                'sku' => 'WJA-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'featured' => false,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Đen', 'Hồng', 'Xanh navy'],
                'brand' => 'Ivy Moda',
                'weight' => 0.7,
                'material' => 'Nỉ cao cấp',
                'discount_percent' => 10,
                'images' => [
                    'products/women/jackets/bomber-1.jpg',
                    'products/women/jackets/bomber-2.jpg',
                ]
            ],
        ];

        // Kết hợp tất cả sản phẩm
        $allProducts = array_merge($menShirts, $menTshirts, $menJeans, $womenShirts, $womenDresses, $jackets);

        // Thêm sản phẩm vào cơ sở dữ liệu
        foreach ($allProducts as $productData) {
            $images = $productData['images'];
            unset($productData['images']);

            // Tạo slug từ tên sản phẩm
            $productData['slug'] = Str::slug($productData['name']);

            $product = Product::create($productData);

            // Thêm hình ảnh cho sản phẩm
            foreach ($images as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => $index === 0, // Hình ảnh đầu tiên là hình chính
                    'sort_order' => $index,
                ]);
            }
        }
    }
} 