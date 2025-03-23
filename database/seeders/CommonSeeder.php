<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Drawer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Rack;
use App\Models\ServiceChart;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'phone' => '01412345678', 'location' => 'Dhaka, Bangladesh']);
        $category = Category::create(['name' => 'Engine Oil']);
        $brand = Brand::create(['name' => 'Hyundai']);
        $RepsolBrand = Brand::create(['name' => 'Repsol']);
        $supplier =Supplier::create(['name' => 'Walk In Supplier', 'zone_id' => $zone->id, 'phone' => '01512345678']);
        $rack = Rack::create(['name' => 'Rack-1', 'zone_id' => $zone->id]);
        for ($i = 1; $i <= 4; $i++) {
            Drawer::create(['name' => 'Drawer-' . $i, 'rack_id' => $rack->id]);
        }
        Product::create([
            'name' => 'Repsol Engine Oil',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'purchase_price' => 1200,
            'sale_price' => 1800,
            'total_available_qty' => 0
        ]);
        Product::create([
            'name' => 'Hyundai Engine Oil',
            'category_id' => $category->id,
            'brand_id' => $RepsolBrand->id,
            'purchase_price' => 1000,
            'sale_price' => 1500,
            'total_available_qty' => 0
        ]);
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'phone' => '01712345678',
            'password' => Hash::make('12345678'),
            'role' => 1,
            'zone_id' => $zone->id
        ]);
        Vehicle::create([
            'owner_type' => 1,
            'license_plate' => 'Dhaka-839574',
            'zone_id' => $zone->id,
            'status' => 1,
        ]);
        Vehicle::create([
            'owner_type' => 2,
            'license_plate' => 'Dhaka-934759',
            'zone_id' => $zone->id,
            'status' => 1,
        ]);
        ServiceChart::insert([
            [
                'name' => 'Car Wash',
                'price' => '200',
                'code' => 'CW200',
                'description' => 'Car Wash',
            ],
            [
                'name' => 'Oil Change',
                'price' => '500',
                'code' => 'OC500',
                'description' => 'Oil Change',
            ]
        ]);
        Account::create([
            'name'      => 'City Bank',
            'type'      => 2,
            'balance'   => 50000,
        ]);
    }
}
