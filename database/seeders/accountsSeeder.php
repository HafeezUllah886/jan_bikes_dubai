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
            'title' => "Cash",
            'type' => "Business",
            'status' => "Active",
        ]);

        accounts::create([
            'title' => "Jan Brothers",
            'type' => "Vendor",
            'address' => "",
            'contact' => "",
            'status' => "Active",
        ]);

        accounts::create([
            'title' => "Walk-in Customer",
            'type' => "Customer",
            'address' => "",
            'contact' => "",
            'status' => "Active",
        ]);

      
        
    }
}
