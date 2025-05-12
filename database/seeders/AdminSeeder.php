<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminUser;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::create(
            [
                "name"=>"Admin User",
                "email"=>"admin@gmail.com",
                "role"=>"admin",
                "password"=>"admin123",

            ]
            );
    }
}
