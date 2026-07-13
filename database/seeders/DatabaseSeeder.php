<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(accountsSeeder::class);
        $this->call(userSeeder::class);
        $this->call(PermissionSeeder::class);
        /*  $this->call(payment_categories_seeder::class); */

    }
}
