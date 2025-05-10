<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Thông tin cá nhân') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Cập nhật thông tin tài khoản và địa chỉ email của bạn.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if(auth()->user()->avatar)
                    <img class="h-20 w-20 object-cover rounded-full" src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-2xl text-gray-500">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="mt-1 block w-full text-base text-gray-700
                    file:mr-4 file:py-2.5 file:px-5
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100
                ">
                @error('avatar')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF tối đa 2MB</p>
            </div>
        </div>

        <!-- Thông tin cơ bản -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ tên</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Địa chỉ email của bạn chưa được xác minh.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Nhấn vào đây để gửi lại email xác minh.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input id="phone" name="phone" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
            @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Địa chỉ -->
        <h3 class="text-base font-medium text-gray-900 mt-6">Thông tin địa chỉ</h3>
        
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
            <input id="address" name="address" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('address', $user->address) }}" autocomplete="street-address">
            @error('address')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Thành phố</label>
                <input id="city" name="city" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('city', $user->city) }}" autocomplete="address-level2">
                @error('city')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Mã bưu chính</label>
                <input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('postal_code', $user->postal_code) }}" autocomplete="postal-code">
                @error('postal_code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Quốc gia</label>
            <input id="country" name="country" type="text" class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('country', $user->country ?? 'Việt Nam') }}" autocomplete="country-name">
            @error('country')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Lưu thông tin') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">{{ __('Đã lưu.') }}</p>
            @endif
        </div>
    </form>
</section>
