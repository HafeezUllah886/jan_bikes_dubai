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
        Schema::create('sale_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained('parts_purchases')->cascadeOnDelete();
            $table->string('description');
            $table->float('qty');
            $table->float('pprice');
            $table->float('price');
            $table->float('amount');
            $table->float('profit');
            $table->date('date');
            $table->string('profit_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_parts');
    }
};
