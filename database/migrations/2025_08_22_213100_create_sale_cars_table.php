<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('sales','id')->cascadeOnDelete();
            $table->foreignId('purchase_car_id')->constrained('purchase_cars','id')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('accounts','id')->cascadeOnDelete();
            $table->decimal('pprice',15,2)->default(0);
            $table->decimal('expense',15,2)->default(0);
            $table->decimal('net_cost',15,2)->default(0);
            $table->decimal('price',15,2)->default(0);
            $table->decimal('profit',15,2)->default(0);
            $table->date('date')->default(now());
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_cars');
    }
};
