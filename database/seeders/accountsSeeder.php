<?php

namespace Database\Seeders;

use App\Models\accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create([
            'title' => "Business",
            'type' => "Business",
            'status' => "Active",
        ]);

        accounts::create([
            'title' => "Test Customer",
            'type' => "Customer",
            'address' => "Test Address",
            'contact' => "1234567890",
            'status' => "Active",
        ]);

        accounts::create([
            'title' => "Another Customer",
            'type' => "Customer",
            'address' => "Test Address",
            'contact' => "1234567890",
            'status' => "Active",
        ]);

        accounts::create([
            'title' => "Another 1 Customer",
            'type' => "Customer",
            'address' => "Test Address",
            'contact' => "1234567890",
            'status' => "Active",
        ]);
        
    }
}
