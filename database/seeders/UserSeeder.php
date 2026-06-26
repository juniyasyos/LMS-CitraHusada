<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Hash all plain-text passwords (e.g., "123") to bcrypt format
     * so users can login with their original passwords
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'nip' => '1234567',
            'password' => Hash::make('password'),
        ]);

        // // Create 20 dummy users
        // User::factory(20)->create();

        // // Hash all existing plain-text passwords in the database
        // User::chunk(100, function ($users) {
        //     foreach ($users as $user) {
        //         // Check if password needs rehashing (i.e., it's plain text, not bcrypt)
        //         if (Hash::needsRehash($user->password)) {
        //             // Hash the plain-text password
        //             $user->update([
        //                 'password' => Hash::make($user->password)
        //             ]);
        //         }
        //     }
        // });
    }
}
