<?php

namespace Database\Seeders;

use App\Models\expenseCategories;
use App\Models\payment_categories;
use Illuminate\Database\Seeder;

class payment_categories_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        payment_categories::create([
            'name' => 'Test Receiving Category',
            'for' => 'Receive',
        ]);
        payment_categories::create([
            'name' => 'Test Payment Category',
            'for' => 'Payment',
        ]);
        expenseCategories::create([
            'name' => 'Salaries',
        ]);
        expenseCategories::create([
            'name' => 'Rent',
        ]);
        expenseCategories::create([
            'name' => 'Utility',
        ]);
        expenseCategories::create([
            'name' => 'Packing',
        ]);
        expenseCategories::create([
            'name' => 'Delivery Charges',
        ]);
    }
}
