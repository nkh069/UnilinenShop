<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <div class="flex">
                <x-text-input id="email" 
                    class="block mt-1 w-full" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" 
                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                    title="Vui lòng nhập một địa chỉ email hợp lệ (ví dụ: example@gmail.com)"
                />
                <button type="button" id="send-otp-btn" style="background-color: #1d4ed8; color: white; margin-left: 8px; margin-top: 4px; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; cursor: pointer; min-width: 100px;">
                    {{ __('GỬI OTP') }}
                </button>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="otp-status" class="text-sm font-medium text-green-600 mt-2 p-2 bg-green-50 rounded-md border border-green-200" style="display: none;">
                ✓ Mã OTP đã được gửi đến email của bạn
            </div>
            @if (session('status'))
                <div class="text-sm text-green-600 mt-2">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <!-- OTP -->
        <div class="mt-4">
            <x-input-label for="otp" :value="__('OTP')" class="font-bold" />
            <x-text-input id="otp" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500" type="text" name="otp" :value="old('otp')" required />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            <p class="text-sm text-gray-600 mt-1">Nhập mã OTP được gửi đến email của bạn</p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const emailInput = document.getElementById('email');
            const otpStatus = document.getElementById('otp-status');

            // Thêm kiểm tra định dạng email trước khi gửi OTP
            function isValidEmail(email) {
                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
                return emailPattern.test(email);
            }

            sendOtpBtn.addEventListener('click', function() {
                const email = emailInput.value;
                if (!email) {
                    alert('Vui lòng nhập email trước');
                    return;
                }

                if (!isValidEmail(email)) {
                    alert('Vui lòng nhập địa chỉ email hợp lệ (ví dụ: example@gmail.com)');
                    return;
                }

                // Disable nút để tránh click nhiều lần
                sendOtpBtn.disabled = true;
                sendOtpBtn.style.backgroundColor = '#94a3b8';
                sendOtpBtn.style.cursor = 'not-allowed';
                sendOtpBtn.innerHTML = 'ĐANG GỬI...';

                // Gửi request bằng fetch thay vì submit form
                fetch('{{ route('register.send-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hiển thị thông báo thành công
                    otpStatus.style.display = 'block';
                    
                    // Kích hoạt lại nút sau 60 giây
                    setTimeout(function() {
                        sendOtpBtn.disabled = false;
                        sendOtpBtn.style.backgroundColor = '#1d4ed8';
                        sendOtpBtn.style.cursor = 'pointer';
                        sendOtpBtn.innerHTML = 'GỬI OTP';
                    }, 60000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('Có lỗi xảy ra khi gửi OTP. Vui lòng thử lại.');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.style.backgroundColor = '#1d4ed8';
                    sendOtpBtn.style.cursor = 'pointer';
                    sendOtpBtn.innerHTML = 'GỬI OTP';
                });
            });
        });
    </script>
</x-guest-layout>
