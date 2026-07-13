<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $permissions = [
            // Dashboard
            ['name' => 'Business Balance', 'group' => 'Dashboard'],
            ['name' => 'Customer Balance', 'group' => 'Dashboard'],
            ['name' => 'Vendor Balance', 'group' => 'Dashboard'],
            ['name' => 'Available Purchases', 'group' => 'Dashboard'],
            ['name' => 'Booked Advance', 'group' => 'Dashboard'],
            ['name' => 'Monthly Sales', 'group' => 'Dashboard'],

            // Imports
            ['name' => 'Imports History', 'group' => 'Imports'],
            ['name' => 'Imports Approve', 'group' => 'Imports'],
            ['name' => 'Imports Delete', 'group' => 'Imports'],

            // Purchases
            ['name' => 'Purchases History', 'group' => 'Purchases'],
            ['name' => 'Purchases Create', 'group' => 'Purchases'],
            ['name' => 'Purchases Delete', 'group' => 'Purchases'],
            ['name' => 'Purchases Expenses', 'group' => 'Purchases'],

            // Parts Purchases
            ['name' => 'Parts Purchases History', 'group' => 'Parts Purchases'],

            // Stocks
            ['name' => 'Cars Stock', 'group' => 'Stocks'],
            ['name' => 'Parts Stock', 'group' => 'Stocks'],

            // Sales
            ['name' => 'Sales History', 'group' => 'Sales'],
            ['name' => 'Sales Create', 'group' => 'Sales'],
            ['name' => 'Sales Edit', 'group' => 'Sales'],
            ['name' => 'Sales Delete', 'group' => 'Sales'],

            // User Management
            ['name' => 'User Create', 'group' => 'User Management'],
            ['name' => 'User Edit', 'group' => 'User Management'],
            ['name' => 'Role Create', 'group' => 'User Management'],
            ['name' => 'Role Edit', 'group' => 'User Management'],

            // Accounts
            ['name' => 'Account View', 'group' => 'Accounts'],
            ['name' => 'Account Create', 'group' => 'Accounts'],
            ['name' => 'Account Edit', 'group' => 'Accounts'],
            ['name' => 'Account Business', 'group' => 'Accounts'],
            ['name' => 'Account Customer', 'group' => 'Accounts'],
            ['name' => 'Account Vendor', 'group' => 'Accounts'],
            ['name' => 'Account Investor', 'group' => 'Accounts'],

            // Reports
            ['name' => 'Profit Loss Report', 'group' => 'Reports'],

            // Finance
            ['name' => 'Account Adjustments View', 'group' => 'Finance'],
            ['name' => 'Account Adjustments Create', 'group' => 'Finance'],
            ['name' => 'Account Adjustments Delete', 'group' => 'Finance'],
            ['name' => 'Transfers View', 'group' => 'Finance'],
            ['name' => 'Transfers Create', 'group' => 'Finance'],
            ['name' => 'Transfers Delete', 'group' => 'Finance'],
            ['name' => 'Payment Receiving View', 'group' => 'Finance'],
            ['name' => 'Payment Receiving Create', 'group' => 'Finance'],
            ['name' => 'Payment Receiving Delete', 'group' => 'Finance'],
            ['name' => 'Payments View', 'group' => 'Finance'],
            ['name' => 'Payments Create', 'group' => 'Finance'],
            ['name' => 'Payments Delete', 'group' => 'Finance'],
            ['name' => 'Advance Payments View', 'group' => 'Finance'],
            ['name' => 'Advance Payments Create', 'group' => 'Finance'],
            ['name' => 'Advance Payments Delete', 'group' => 'Finance'],
            ['name' => 'Expenses View', 'group' => 'Finance'],
            ['name' => 'Expenses Create', 'group' => 'Finance'],
            ['name' => 'Expenses Delete', 'group' => 'Finance'],
            ['name' => 'Extra Profit View', 'group' => 'Finance'],
            ['name' => 'Extra Profit Create', 'group' => 'Finance'],
            ['name' => 'Extra Profit Delete', 'group' => 'Finance'],
            ['name' => 'Profit Distribution View', 'group' => 'Finance'],
            ['name' => 'Profit Distribution Create', 'group' => 'Finance'],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'group' => $permission['group'], 'guard_name' => 'web']
            );
        }

        // Create Admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign to first user if exists
        $user = User::first();
        if ($user) {
            $user->assignRole('Admin');
        }
    }
}
