<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Xóa tài khoản') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Sau khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của tài khoản sẽ bị xóa vĩnh viễn. Trước khi xóa tài khoản, vui lòng tải xuống bất kỳ dữ liệu hoặc thông tin nào bạn muốn giữ lại.') }}
        </p>
    </header>

    <button
        type="button"
        class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
        onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
    >{{ __('Xóa tài khoản') }}</button>

    <!-- Modal xóa tài khoản -->
    <div id="delete-account-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-xl font-medium text-gray-900 mb-4">
                    {{ __('Bạn có chắc chắn muốn xóa tài khoản của mình?') }}
                </h2>

                <p class="mt-3 text-sm text-gray-600">
                    {{ __('Sau khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của tài khoản sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn xóa vĩnh viễn tài khoản của mình.') }}
                </p>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mật khẩu') }}</label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full h-12 px-4 py-2 text-base rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('Nhập mật khẩu của bạn') }}"
                    />

                    @error('password', 'userDeletion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <button 
                        type="button" 
                        class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150"
                        onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                    >
                        {{ __('Hủy') }}
                    </button>

                    <button 
                        type="submit"
                        class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        {{ __('Xóa tài khoản') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
