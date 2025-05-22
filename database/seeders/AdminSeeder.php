<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // <-- Import Hash facade

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Admin User",
            "email" => "admin@gmail.com",
            "role" => "admin",
            "password" => Hash::make("admin123"),  // <-- Hash the password here
        ]);
    }
}
