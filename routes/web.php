<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Shop\PaymentController;
use App\Http\Controllers\Auth\OtpVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\PromotionController;
use App\Http\Controllers\Admin\ProductAttributeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard Route (chỉ dành cho admin)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->role === 'admin') {
        return view('dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{id}/review', [ReviewController::class, 'store'])->name('products.review')->middleware(['auth']);

// Shop routes
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
    Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/khuyen-mai', [PromotionController::class, 'index'])->name('promotions');
});

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'getCount'])->name('count');
});

// Checkout routes
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
    Route::post('/coupon', [CheckoutController::class, 'applyCoupon'])->name('apply-coupon');
    Route::get('/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('remove-coupon');
});

// Authentication routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/order/confirmation/{id}', [OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{orderNumber}/track', [OrderController::class, 'trackOrder'])->name('orders.track');
    Route::post('/orders/{orderNumber}/verify-otp', [OrderController::class, 'verifyOtp'])->name('orders.verify-otp');
    
    // Thêm route cho đánh giá sản phẩm sau khi đơn hàng giao thành công
    Route::get('/orders/{orderNumber}/review', [ReviewController::class, 'orderReviewForm'])->name('orders.review');
    Route::post('/orders/{orderNumber}/review', [ReviewController::class, 'addOrderReview'])->name('orders.add-review');
    
    // Invoices
    Route::get('/invoices/{invoiceNumber}/download', [OrderController::class, 'downloadInvoice'])->name('invoices.download');
    
    // Reviews
    Route::get('/reviews', [ReviewController::class, 'userReviews'])->name('reviews.index');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Payments
    Route::get('/payment/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/{order}/complete', [PaymentController::class, 'completePayment'])->name('payment.complete');
    Route::post('/payment/{order}/cancel', [PaymentController::class, 'cancelPayment'])->name('payment.cancel');
    
    // OTP Verification
    Route::post('/otp/send', [OtpVerificationController::class, 'sendOtp'])->name('otp.send');
    Route::post('/otp/verify', [OtpVerificationController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');
});

// Payment Callback (no auth required)
Route::post('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Category routes
    Route::resource('categories', CategoryController::class);
    
    // Product routes
    Route::resource('products', AdminProductController::class);
    Route::get('/products/{product}/images/{image}/set-primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');
    Route::get('/product-images/{id}/delete', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::get('/products/sync-attributes', [AdminProductController::class, 'syncProductAttributes'])->name('products.sync-attributes');
    
    // Product Attributes routes (Colors & Sizes)
    Route::get('/attributes', [ProductAttributeController::class, 'index'])->name('attributes.index');
    Route::post('/attributes/add-color', [ProductAttributeController::class, 'addColor'])->name('attributes.add-color');
    Route::post('/attributes/remove-color', [ProductAttributeController::class, 'removeColor'])->name('attributes.remove-color');
    Route::post('/attributes/update-color', [ProductAttributeController::class, 'updateColor'])->name('attributes.update-color');
    Route::post('/attributes/add-size', [ProductAttributeController::class, 'addSize'])->name('attributes.add-size');
    Route::post('/attributes/remove-size', [ProductAttributeController::class, 'removeSize'])->name('attributes.remove-size');
    Route::post('/attributes/update-size', [ProductAttributeController::class, 'updateSize'])->name('attributes.update-size');
    
    // Test product update route
    Route::get('/products/{id}/edit-test', function($id) {
        $product = \App\Models\Product::with('productImages')->findOrFail($id);
        $categories = \App\Models\Category::where('is_active', true)->get();
        return view('admin.products.edit-test', compact('product', 'categories'));
    })->name('products.edit-test');
    
    // User routes
    Route::resource('users', UserController::class);
    
    // Order routes
    Route::resource('orders', AdminOrderController::class);
    
    // Inventory routes
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::get('inventory/add-stock', [InventoryController::class, 'addStockForm'])->name('inventory.add-stock-form');
    Route::post('inventory/process-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::get('inventory/process-stock-direct', [InventoryController::class, 'addStockDirect'])->name('inventory.process-stock-direct');
    Route::post('inventory/add-variant-direct', [InventoryController::class, 'addVariantDirect'])->name('inventory.add-variant-direct');
    Route::get('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('inventory/adjust', [InventoryController::class, 'processAdjust'])->name('inventory.process-adjust');
    Route::delete('inventory/variant/{id}', [InventoryController::class, 'deleteVariant'])->name('inventory.delete-variant');
    Route::get('inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // Report routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    
    // Shipments
    Route::get('shipments/unassigned', [ShipmentController::class, 'unassigned'])->name('shipments.unassigned');
    Route::get('shipments/pending', [ShipmentController::class, 'pending'])->name('shipments.pending');
    Route::post('shipments/{shipment}/assign-shipper', [ShipmentController::class, 'assignShipper'])->name('shipments.assign-shipper');
    Route::post('shipments/{shipment}/change-shipper', [ShipmentController::class, 'changeShipper'])->name('shipments.change-shipper');
    Route::resource('shipments', ShipmentController::class);
    
    // Route test cho route
    Route::get('test-route', function() {
        return 'Route shipments: </br>'
             . 'unassigned: ' . url('/admin/shipments/unassigned') . '</br>'
             . 'assign-shipper: ' . url('/admin/shipments/1/assign-shipper');
    });
    
    // Invoice routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    
    // Coupon routes
    Route::resource('coupons', CouponController::class);
    
    // Shipper routes
    Route::get('shippers/select-for-shipment', [App\Http\Controllers\Admin\ShipperController::class, 'selectForShipment'])->name('shippers.select-for-shipment');
    Route::resource('shippers', App\Http\Controllers\Admin\ShipperController::class);
    
    // Supplier routes
    Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class);
});

// Routes cho mã giảm giá test
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('coupons_test', \App\Http\Controllers\CouponTestController::class)
        ->except(['show']);
});

require __DIR__.'/auth.php';
