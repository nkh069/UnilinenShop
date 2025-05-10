@extends('layouts.shop')

@section('title', 'Khuyến Mãi | UniLinen Shop')

@section('meta_description', 'Khám phá các chương trình khuyến mãi và mã giảm giá hấp dẫn nhất tại UniLinen Shop')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl md:text-4xl font-serif font-semibold text-center mb-12" data-aos="fade-up">Khuyến Mãi Đặc Biệt</h1>
    
    <div class="max-w-4xl mx-auto mb-12 text-center" data-aos="fade-up" data-aos-delay="100">
        <p class="text-neutral-600 text-lg">
            Khám phá các chương trình khuyến mãi hấp dẫn và mã giảm giá độc quyền từ UniLinen Shop.
            Sử dụng các mã giảm giá dưới đây để tiết kiệm khi mua sắm.
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16" data-aos="fade-up" data-aos-delay="200">
        @forelse($coupons as $coupon)
            <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-neutral-800">{{ $coupon->description ?: 'Mã giảm giá' }}</h3>
                        @if($coupon->valid_until)
                            <span class="text-sm text-neutral-500">
                                Hết hạn: {{ $coupon->valid_until->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-neutral-600 mb-4">
                            @if($coupon->type == 'percentage')
                                Giảm {{ number_format($coupon->value) }}% cho đơn hàng
                                @if($coupon->min_order_amount > 0)
                                    từ {{ number_format($coupon->min_order_amount, 0, ',', '.') }}₫
                                @endif
                            @else
                                Giảm {{ number_format($coupon->value, 0, ',', '.') }}₫ cho đơn hàng
                                @if($coupon->min_order_amount > 0)
                                    từ {{ number_format($coupon->min_order_amount, 0, ',', '.') }}₫
                                @endif
                            @endif
                        </p>
                        
                        @if($coupon->category)
                            <p class="text-neutral-500 text-sm">
                                <i class="fas fa-tag mr-1"></i> Áp dụng cho danh mục: {{ $coupon->category->name }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="relative group">
                            <div class="flex items-center bg-neutral-100 px-4 py-2 rounded-md">
                                <span class="font-mono font-semibold text-primary-600">{{ $coupon->code }}</span>
                                <button class="ml-3 text-neutral-500 hover:text-primary-600 transition-colors copy-code" data-code="{{ $coupon->code }}">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-neutral-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                                Sao chép mã
                            </div>
                        </div>
                        
                        <a href="{{ route('checkout.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition-colors font-medium text-sm">
                            Sử dụng ngay
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 text-center py-16">
                <div class="text-5xl text-neutral-300 mb-4">
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="text-xl font-medium text-neutral-600 mb-2">Hiện không có khuyến mãi nào</h3>
                <p class="text-neutral-500">Vui lòng quay lại sau để cập nhật các chương trình khuyến mãi mới nhất.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-md transition-colors">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endforelse
    </div>
    
    <div class="bg-neutral-100 rounded-lg p-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="300">
        <h2 class="text-2xl font-serif font-semibold text-center mb-6">Hướng dẫn sử dụng mã giảm giá</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="space-y-3">
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-copy"></i>
                </div>
                <h3 class="font-medium text-neutral-800">Bước 1: Sao chép mã</h3>
                <p class="text-neutral-600 text-sm">Chọn mã giảm giá phù hợp và nhấn vào biểu tượng sao chép.</p>
            </div>
            
            <div class="space-y-3">
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="font-medium text-neutral-800">Bước 2: Mua sắm</h3>
                <p class="text-neutral-600 text-sm">Thêm sản phẩm vào giỏ hàng và tiến hành thanh toán.</p>
            </div>
            
            <div class="space-y-3">
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-tag"></i>
                </div>
                <h3 class="font-medium text-neutral-800">Bước 3: Áp dụng mã</h3>
                <p class="text-neutral-600 text-sm">Dán mã giảm giá vào ô tương ứng trong trang thanh toán.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý sao chép mã
        const copyButtons = document.querySelectorAll('.copy-code');
        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    // Thay đổi icon thành check
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'fas fa-check text-green-500';
                    
                    // Hiển thị thông báo
                    const tooltip = document.createElement('div');
                    tooltip.className = 'fixed top-4 right-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md z-50';
                    tooltip.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            <p>Đã sao chép mã: ${code}</p>
                        </div>
                    `;
                    document.body.appendChild(tooltip);
                    
                    // Xóa thông báo sau 3 giây
                    setTimeout(() => {
                        tooltip.remove();
                        icon.className = originalClass;
                    }, 3000);
                });
            });
        });
    });
</script>
@endsection 