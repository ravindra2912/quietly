<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
                'phone' => 9988998877,
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],           
        ];

        foreach ($data as $user) {
            User::create($user);
        }
    }
}
