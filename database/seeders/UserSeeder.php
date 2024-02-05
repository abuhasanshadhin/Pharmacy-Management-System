<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image_path = 'images/profile-image.avif';
        $user = [
            'name' => 'John',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '+8801000000000',
            'password' => Hash::make(123456),
            'profile_image' => $image_path
        ];

        $superAdmin = User::create($user);
        $role = Role::first();
        $superAdmin->assignRole($role->name);
    }
}
