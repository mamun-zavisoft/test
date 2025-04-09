<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create(['name' => 'Walk In Supplier']);
    
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'phone' => '01712345678',
            'password' => Hash::make('12345678'),
            'role' => '1',
        ]);
    }
}
