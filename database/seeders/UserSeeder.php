<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $userRole = Role::where('name', 'user')->firstOrFail();

        // Admin user
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@eventapp.com',
                'password' => Hash::make('123'),
                'name' => 'Admin',
                'surname' => 'User',
                'bio' => 'Administrator of the event application',
                'profile_image' => null,
                'role_id' => $adminRole->id,
                'city_id' => null,
                'is_active' => true,
            ]
        );

        // User Amaia
        User::firstOrCreate(
            ['username' => 'amaia'],
            [
                'email' => 'amaia@eventapp.com',
                'password' => Hash::make('123'),
                'name' => 'Amaia',
                'surname' => 'González',
                'bio' => 'Event enthusiast and organizer',
                'profile_image' => null,
                'role_id' => $userRole->id,
                'city_id' => null,
                'is_active' => true,
            ]
        );

        // User Dani
        User::firstOrCreate(
            ['username' => 'dani'],
            [
                'email' => 'dani@eventapp.com',
                'password' => Hash::make('123'),
                'name' => 'Daniel',
                'surname' => 'Negrete',
                'bio' => 'Music lover and concert goer',
                'profile_image' => null,
                'role_id' => $userRole->id,
                'city_id' => null,
                'is_active' => true,
            ]
        );
    }
}
