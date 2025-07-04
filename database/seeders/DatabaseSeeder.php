<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'name' => 'Gabriel Willian',
            'email' => 'user@test.com',
            'password' => Hash::make('123456789'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}
