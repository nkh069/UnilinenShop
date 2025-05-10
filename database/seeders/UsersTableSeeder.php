<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
            'address' => '123 Admin Street',
            'city' => 'Hanoi',
            'country' => 'Vietnam',
            'postal_code' => '100000',
        ]);

        // Staff
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654322',
            'role' => 'staff',
            'is_verified' => true,
            'email_verified_at' => now(),
            'address' => '456 Staff Street',
            'city' => 'Ho Chi Minh City',
            'country' => 'Vietnam',
            'postal_code' => '700000',
        ]);

        // Regular customers
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654323',
            'role' => 'customer',
            'is_verified' => true,
            'email_verified_at' => now(),
            'address' => '789 Customer Street',
            'city' => 'Da Nang',
            'country' => 'Vietnam',
            'postal_code' => '550000',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654324',
            'role' => 'customer',
            'is_verified' => true,
            'email_verified_at' => now(),
            'address' => '101 Customer Ave',
            'city' => 'Nha Trang',
            'country' => 'Vietnam',
            'postal_code' => '650000',
        ]);

        // Create more random customers
        \App\Models\User::factory(20)->create();
    }
}
