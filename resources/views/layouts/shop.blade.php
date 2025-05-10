<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cửa Hàng Thời Trang') - UNILINEN</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Jost', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        neutral: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        },
                    },
                },
            },
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .menu-hover:after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: currentColor;
            transition: width 0.3s;
        }
        
        .menu-hover:hover:after {
            width: 100%;
        }
        
        .scrolled-header {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Rating Stars Styles - Global */
        .bi-star-fill {
            color: #ffba00;
            filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
            transition: all 0.3s ease;
        }

        .bi-star-half {
            color: #ffba00;
            filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
            transition: all 0.3s ease;
        }

        .bi-star {
            color: #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .rating-count {
            background-color: #f8f8f8;
            color: #666;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.1rem 0.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        /* Hover effects */
        .product-card:hover .bi-star-fill,
        .product-card:hover .bi-star-half,
        .group:hover .bi-star-fill,
        .group:hover .bi-star-half {
            color: #ffa000;
            transform: scale(1.1);
        }
        
        .product-card:hover .rating-count,
        .group:hover .rating-count {
            background-color: #f0f0f0;
            color: #333;
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    
    <!-- Page Specific Styles -->
    @yield('styles')
    
    @stack('styles')
</head>
<body class="antialiased text-neutral-800 bg-neutral-50 flex flex-col min-h-screen">
    <!-- Announcement Bar -->
    <div class="bg-neutral-900 text-white py-2 text-center text-sm font-light tracking-wide">
        <div class="container mx-auto px-6">
            Miễn phí vận chuyển cho đơn hàng trên 500.000₫ | Đổi trả miễn phí trong 30 ngày
        </div>
    </div>
    
    <!-- Header -->
    <header id="mainHeader" class="bg-white py-0 transition-all duration-300 fixed w-full z-50" x-data="{ isOpen: false, subMenuOpen: false }">
    <!-- Top Bar -->
        <div class="border-b border-neutral-100">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center py-3">
                    <div class="hidden md:flex space-x-4 text-sm text-neutral-500">
                        <a href="mailto:info@uninilen.com" class="hover:text-primary-600 transition">
                            <i class="far fa-envelope mr-1"></i> info@uninilen.com
                        </a>
                        <a href="tel:+84123456789" class="hover:text-primary-600 transition">
                            <i class="far fa-phone mr-1"></i> +84 123 456 789
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm text-neutral-600 hover:text-primary-600 transition">
                                <i class="far fa-user mr-1"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="text-sm text-neutral-600 hover:text-primary-600 transition">
                                <i class="far fa-user-plus mr-1"></i> Đăng ký
                            </a>
                        @else
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm text-neutral-600 hover:text-primary-600 transition">
                                    <i class="far fa-user-circle mr-1"></i> {{ Auth::user()->name }}
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200" 
                                     x-transition:enter-start="opacity-0 scale-95" 
                                     x-transition:enter-end="opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-150" 
                                     x-transition:leave-start="opacity-100 scale-100" 
                                     x-transition:leave-end="opacity-0 scale-95" 
                                     class="absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-neutral-100" 
                                     x-cloak>
                                    
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                        <i class="far fa-user mr-2"></i>Tài khoản
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                        <i class="bi bi-bag mr-2"></i>Đơn hàng
                                    </a>
                                    <a href="{{ route('reviews.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                        <i class="far fa-star mr-2"></i>Đánh giá
                                    </a>
                                    <hr class="my-1 border-neutral-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                            <i class="bi bi-box-arrow-right mr-2"></i>Đăng xuất
                                        </button>
                                        </form>
                                </div>
                            </div>
                        @endguest
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-serif font-bold text-neutral-900 tracking-tight mr-12">
                        UNILINEN
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex space-x-8">
                        <a href="{{ route('home') }}" class="menu-hover text-neutral-800 font-medium {{ request()->routeIs('home') ? 'text-primary-600' : '' }}">
                            Trang chủ
                        </a>
                        
                        <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="{{ route('products.index') }}" class="menu-hover text-neutral-800 font-medium flex items-center {{ request()->routeIs('products.index') ? 'text-primary-600' : '' }}">
                                Danh mục
                                <i class="fas fa-chevron-down text-xs ml-1 mt-1"></i>
                            </a>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 translate-y-1" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-150" 
                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 translate-y-1" 
                                 class="absolute -left-4 mt-2 w-[800px] grid grid-cols-3 gap-6 bg-white p-6 rounded-xl shadow-xl border border-neutral-100 z-50" 
                                 x-cloak>
                                
                            @php
                            $categories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->with(['children' => function($query) {
                                $query->where('is_active', true);
                            }])->get();
                            @endphp
                            
                            @foreach($categories as $category)
                                    <div class="mb-4">
                                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-neutral-900 font-medium text-base hover:text-primary-600 transition">
                                            {{ $category->name }}
                                        </a>
                                        @if($category->children->count() > 0)
                                            <ul class="mt-2 space-y-1">
                                                @foreach($category->children as $child)
                                                    <li>
                                                        <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="text-neutral-600 text-sm hover:text-primary-600 transition">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <a href="{{ route('products.index') }}" class="menu-hover text-neutral-800 font-medium {{ request()->routeIs('products.index') && !request('category') ? 'text-primary-600' : '' }}">
                            Sản phẩm
                        </a>
                        <a href="#" class="menu-hover text-neutral-800 font-medium">
                            Bộ sưu tập
                        </a>
                        <a href="{{ route('shop.promotions') }}" class="menu-hover text-neutral-800 font-medium {{ request()->routeIs('shop.promotions') ? 'text-primary-600' : '' }}">
                            Khuyến mãi
                        </a>
                        <a href="#" class="menu-hover text-neutral-800 font-medium">
                            Liên hệ
                        </a>
                    </nav>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative" x-data="{ isSearchOpen: false }">
                        <button @click="isSearchOpen = !isSearchOpen" class="p-2 rounded-full hover:bg-neutral-100 transition">
                            <i class="fas fa-search text-neutral-600"></i>
                        </button>
                        
                        <div x-show="isSearchOpen" 
                             @click.away="isSearchOpen = false" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100" 
                             x-transition:leave-end="opacity-0 scale-95" 
                             class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg p-4 z-50 border border-neutral-100" 
                             x-cloak>
                            
                            <form action="{{ route('products.index') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." class="w-full px-4 py-2 border border-neutral-200 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" value="{{ request('search') }}">
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700 transition">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="p-2 rounded-full hover:bg-neutral-100 transition relative" aria-label="Giỏ hàng">
                        <i class="fas fa-shopping-bag text-neutral-600"></i>
                        <span id="cartCount" class="absolute -top-1 -right-1 w-5 h-5 bg-primary-600 text-white text-xs rounded-full flex items-center justify-center font-medium">0</span>
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button @click="isOpen = !isOpen" class="lg:hidden p-2 rounded-full hover:bg-neutral-100 transition">
                        <i x-bind:class="isOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-neutral-600"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform -translate-y-4" 
             x-transition:enter-end="opacity-100 transform translate-y-0" 
             x-transition:leave="transition ease-in duration-300" 
             x-transition:leave-start="opacity-100 transform translate-y-0" 
             x-transition:leave-end="opacity-0 transform -translate-y-4" 
             class="lg:hidden bg-white border-t border-neutral-100" 
             x-cloak>
            
            <nav class="container mx-auto px-6 py-3">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('home') }}" class="block py-2 px-3 rounded-md {{ request()->routeIs('home') ? 'bg-neutral-100 text-primary-600' : 'text-neutral-800 hover:bg-neutral-50' }}">
                            Trang chủ
                        </a>
                    </li>
                    <li x-data="{ submenuOpen: false }">
                        <button @click="submenuOpen = !submenuOpen" class="flex items-center justify-between w-full py-2 px-3 rounded-md text-neutral-800 hover:bg-neutral-50">
                            <span>Danh mục</span>
                            <i x-bind:class="submenuOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                        </button>
                        
                        <div x-show="submenuOpen" class="mt-1 ml-4 space-y-1" x-cloak>
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block py-2 px-3 text-neutral-800 font-medium hover:bg-neutral-50 rounded-md">
                                    {{ $category->name }}
                                </a>
                                
                                @foreach($category->children as $child)
                                    <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="block py-2 px-3 pl-6 text-neutral-600 hover:bg-neutral-50 rounded-md">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="block py-2 px-3 rounded-md {{ request()->routeIs('products.index') && !request('category') ? 'bg-neutral-100 text-primary-600' : 'text-neutral-800 hover:bg-neutral-50' }}">
                            Sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 rounded-md text-neutral-800 hover:bg-neutral-50">
                            Bộ sưu tập
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.promotions') }}" class="block py-2 px-3 rounded-md {{ request()->routeIs('shop.promotions') ? 'bg-neutral-100 text-primary-600' : 'text-neutral-800 hover:bg-neutral-50' }}">
                            Khuyến mãi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 rounded-md text-neutral-800 hover:bg-neutral-50">
                            Liên hệ
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="pt-[110px]"></div>
    
    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('info'))
    <div class="container mx-auto px-6 mt-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 transform -translate-y-2" 
         x-transition:enter-end="opacity-100 transform translate-y-0" 
         x-transition:leave="transition ease-in duration-300" 
         x-transition:leave-start="opacity-100 transform translate-y-0" 
         x-transition:leave-end="opacity-0 transform -translate-y-2">
        
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <p>{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
    </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p>{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
    </div>
    @endif
    
    @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                <p>{{ session('info') }}</p>
            </div>
            <button @click="show = false" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
    </div>
    @endif
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>
    
    <!-- Newsletter Section -->
    <section class="bg-neutral-100 py-16 border-t border-neutral-200">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="font-serif text-3xl font-semibold mb-4">Đăng ký nhận thông tin</h2>
                <p class="text-neutral-600 mb-8">Nhận thông tin về bộ sưu tập mới và khuyến mãi đặc biệt</p>
                
                <form action="#" method="POST" class="flex flex-col sm:flex-row max-w-lg mx-auto">
                    <input type="email" placeholder="Nhập địa chỉ email của bạn" class="flex-grow px-4 py-3 rounded-l-md border-0 focus:ring-2 focus:ring-primary-500 sm:rounded-r-none">
                    <button type="submit" class="mt-3 sm:mt-0 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-md sm:rounded-l-none transition">
                        Đăng ký
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 text-neutral-300 pt-16 pb-6 border-t border-neutral-800">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-serif font-bold text-white tracking-tight inline-block mb-5">
                        UNILINEN
                    </a>
                    <p class="text-neutral-400 mb-6">
                        Mang đến những sản phẩm thời trang chất lượng cao với giá cả hợp lý, đáp ứng nhu cầu và phong cách của bạn.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-white text-lg font-semibold mb-5 relative after:content-[''] after:absolute after:left-0 after:bottom-[-10px] after:w-12 after:h-[2px] after:bg-primary-600">Thông tin</h3>
                    <ul class="space-y-3 mt-6">
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Giới thiệu</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Chính sách đổi trả</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Chính sách vận chuyển</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white text-lg font-semibold mb-5 relative after:content-[''] after:absolute after:left-0 after:bottom-[-10px] after:w-12 after:h-[2px] after:bg-primary-600">Tài khoản</h3>
                    <ul class="space-y-3 mt-6">
                        <li><a href="{{ route('login') }}" class="text-neutral-400 hover:text-white transition">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}" class="text-neutral-400 hover:text-white transition">Đăng ký</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-neutral-400 hover:text-white transition">Giỏ hàng</a></li>
                        <li><a href="{{ route('orders.index') }}" class="text-neutral-400 hover:text-white transition">Đơn hàng</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Yêu thích</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white text-lg font-semibold mb-5 relative after:content-[''] after:absolute after:left-0 after:bottom-[-10px] after:w-12 after:h-[2px] after:bg-primary-600">Liên hệ</h3>
                    <ul class="space-y-4 mt-6">
                        <li class="flex">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-500"></i>
                            <span>123 Đường ABC, Quận 1, TP.HCM</span>
                        </li>
                        <li class="flex">
                            <i class="fas fa-phone-alt mt-1 mr-3 text-primary-500"></i>
                            <span>+84 123 456 789</span>
                        </li>
                        <li class="flex">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary-500"></i>
                            <span>info@uninilen.com</span>
                        </li>
                        <li class="flex">
                            <i class="fas fa-clock mt-1 mr-3 text-primary-500"></i>
                            <span>8:00 - 20:00, Thứ 2 - Chủ Nhật</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 pt-6 border-t border-neutral-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-neutral-500">&copy; {{ date('Y') }} UNILINEN. Tất cả quyền được bảo lưu.</p>
                    <div class="mt-4 md:mt-0">
                        <div class="flex space-x-3">
                            <img src="{{ asset('images/payment/visa.png') }}" alt="Visa" class="h-8 object-contain">
                            <img src="{{ asset('images/payment/mastercard.png') }}" alt="MasterCard" class="h-8 object-contain">
                            <img src="{{ asset('images/payment/paypal.png') }}" alt="PayPal" class="h-8 object-contain">
                            <img src="{{ asset('images/payment/momo.png') }}" alt="MoMo" class="h-8 object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to top button -->
    <button id="backToTop" class="fixed bottom-6 right-6 bg-primary-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transform scale-0 transition-transform hover:bg-primary-700">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Common Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });
            
            // Header scroll effect
            const header = document.getElementById('mainHeader');
            const scrollThreshold = 100;
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > scrollThreshold) {
                    header.classList.add('scrolled-header');
                } else {
                    header.classList.remove('scrolled-header');
                }
            });
            
            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopButton.classList.remove('scale-0');
                    backToTopButton.classList.add('scale-100');
                } else {
                    backToTopButton.classList.remove('scale-100');
                    backToTopButton.classList.add('scale-0');
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Initialize swiper carousel if exists
            if (document.querySelector('.hero-swiper')) {
                new Swiper('.hero-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
            
            // Product swiper if exists
            if (document.querySelector('.product-swiper')) {
                new Swiper('.product-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                });
            }
            
            // Update cart count
            updateCartCount();
        });
        
        // Function to update cart count
        function updateCartCount() {
            fetch("{{ route('cart.count') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').innerText = data.count;
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }
    </script>
    
    <!-- Page Specific Scripts -->
    @yield('scripts')
    
    @stack('scripts')
</body>
</html>