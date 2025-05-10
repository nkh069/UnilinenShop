@extends('layouts.shop')

@section('title', 'Thông tin tài khoản')

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6 text-center">{{ __('Thông tin tài khoản') }}</h1>
        
        <div class="space-y-6 max-w-3xl mx-auto">
            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
