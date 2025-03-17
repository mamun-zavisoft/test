<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Permission::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $permissions = [
            ['guard_name' => 'admin', 'group_name' => 'role', 'name' => 'role-list'],
            ['guard_name' => 'admin', 'group_name' => 'role', 'name' => 'role-create'],
            ['guard_name' => 'admin', 'group_name' => 'role', 'name' => 'role-update'],
            ['guard_name' => 'admin', 'group_name' => 'role', 'name' => 'role-delete'],
            
            ['guard_name' => 'admin', 'group_name' => 'user', 'name' => 'user-list'],
            ['guard_name' => 'admin', 'group_name' => 'user', 'name' => 'user-create'],
            ['guard_name' => 'admin', 'group_name' => 'user', 'name' => 'user-update'],
            ['guard_name' => 'admin', 'group_name' => 'user', 'name' => 'user-delete'],


            ['guard_name' => 'admin', 'group_name' => 'product', 'name' => 'product-list'],
            ['guard_name' => 'admin', 'group_name' => 'product', 'name' => 'product-create'],
            ['guard_name' => 'admin', 'group_name' => 'product', 'name' => 'product-update'],
            ['guard_name' => 'admin', 'group_name' => 'product', 'name' => 'product-delete'],

            ['guard_name' => 'admin', 'group_name' => 'category', 'name' => 'category-list'],
            ['guard_name' => 'admin', 'group_name' => 'category', 'name' => 'category-create'],
            ['guard_name' => 'admin', 'group_name' => 'category', 'name' => 'category-update'],
            ['guard_name' => 'admin', 'group_name' => 'category', 'name' => 'category-delete'],

            ['guard_name' => 'admin', 'group_name' => 'brand', 'name' => 'brand-list'],
            ['guard_name' => 'admin', 'group_name' => 'brand', 'name' => 'brand-create'],
            ['guard_name' => 'admin', 'group_name' => 'brand', 'name' => 'brand-update'],
            ['guard_name' => 'admin', 'group_name' => 'brand', 'name' => 'brand-delete'],


            ['guard_name' => 'admin', 'group_name' => 'purchase', 'name' => 'purchase-list'],
            ['guard_name' => 'admin', 'group_name' => 'purchase', 'name' => 'purchase-show'],
            ['guard_name' => 'admin', 'group_name' => 'purchase', 'name' => 'purchase-create'],

            ['guard_name' => 'admin', 'group_name' => 'service', 'name' => 'service-list'],
            ['guard_name' => 'admin', 'group_name' => 'service', 'name' => 'service-show'],
            ['guard_name' => 'admin', 'group_name' => 'service', 'name' => 'service-create'],
            
            ['guard_name' => 'admin', 'group_name' => 'sale', 'name' => 'sale-list'],
            ['guard_name' => 'admin', 'group_name' => 'sale', 'name' => 'sale-show'],
            ['guard_name' => 'admin', 'group_name' => 'sale', 'name' => 'sale-create'],

            
            ['guard_name' => 'admin', 'group_name' => 'rack', 'name' => 'rack-list'],
            ['guard_name' => 'admin', 'group_name' => 'rack', 'name' => 'rack-show'],
            ['guard_name' => 'admin', 'group_name' => 'rack', 'name' => 'rack-create'],
            ['guard_name' => 'admin', 'group_name' => 'rack', 'name' => 'rack-update'],
            ['guard_name' => 'admin', 'group_name' => 'rack', 'name' => 'rack-delete'],
            
            ['guard_name' => 'admin', 'group_name' => 'drawer', 'name' => 'drawer-list'],
            ['guard_name' => 'admin', 'group_name' => 'drawer', 'name' => 'drawer-show'],
            ['guard_name' => 'admin', 'group_name' => 'drawer', 'name' => 'drawer-create'],
            ['guard_name' => 'admin', 'group_name' => 'drawer', 'name' => 'drawer-update'],
            ['guard_name' => 'admin', 'group_name' => 'drawer', 'name' => 'drawer-delete'],


            ['guard_name' => 'admin', 'group_name' => 'account', 'name' => 'account-list'],
            ['guard_name' => 'admin', 'group_name' => 'account', 'name' => 'account-create'],
            ['guard_name' => 'admin', 'group_name' => 'account', 'name' => 'account-update'],
            ['guard_name' => 'admin', 'group_name' => 'account', 'name' => 'account-delete'],


            ['guard_name' => 'admin', 'group_name' => 'supplier', 'name' => 'supplier-list'],
            ['guard_name' => 'admin', 'group_name' => 'supplier', 'name' => 'supplier-create'],
            ['guard_name' => 'admin', 'group_name' => 'supplier', 'name' => 'supplier-update'],
            ['guard_name' => 'admin', 'group_name' => 'supplier', 'name' => 'supplier-delete'],

            ['guard_name' => 'admin', 'group_name' => 'zone', 'name' => 'zone-list'],
            ['guard_name' => 'admin', 'group_name' => 'zone', 'name' => 'zone-create'],
            ['guard_name' => 'admin', 'group_name' => 'zone', 'name' => 'zone-update'],
            ['guard_name' => 'admin', 'group_name' => 'zone', 'name' => 'zone-delete'],

            ['guard_name' => 'admin', 'group_name' => 'vehicle', 'name' => 'vehicle-list'],
            ['guard_name' => 'admin', 'group_name' => 'vehicle', 'name' => 'vehicle-create'],
            ['guard_name' => 'admin', 'group_name' => 'vehicle', 'name' => 'vehicle-update'],
            ['guard_name' => 'admin', 'group_name' => 'vehicle', 'name' => 'vehicle-delete'],

            
            ['guard_name' => 'admin', 'group_name' => 'service-chart', 'name' => 'service-chart-list'],
            ['guard_name' => 'admin', 'group_name' => 'service-chart', 'name' => 'service-chart-create'],
            ['guard_name' => 'admin', 'group_name' => 'service-chart', 'name' => 'service-chart-update'],
            ['guard_name' => 'admin', 'group_name' => 'service-chart', 'name' => 'service-chart-delete'],

        ];

        $newPermissions = [];

        foreach ($permissions as $permission) {
            // Check if the name already exists
            $exists = DB::table('permissions')->where('name', $permission['name'])->exists();

            if (! $exists) {
                $newPermissions[] = $permission;
                $this->command->info('Inserted: '.$permission['name']);
            } else {
                $this->command->info('The key already exists: '.$permission['name']);
            }
        }

        DB::table('permissions')->insert($newPermissions);
    }
}
