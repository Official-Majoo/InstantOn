<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a customer user
        User::create([
            'name' => 'Nature Majoo',
            'email' => 'naturemajoo@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '+267 71234567',
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Create a bank officer user
        User::create([
            'name' => 'Bank Officer',
            'email' => 'officer@fnbb.co.bw',
            'password' => Hash::make('12345678'),
            'phone_number' => '+267 72345678',
            'role' => 'bank_officer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@fnbb.co.bw',
            'password' => Hash::make('12345678'),
            'phone_number' => '+267 73456789',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('Users created successfully!');
    }
}