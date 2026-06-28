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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no')->nullable();
            $table->string('meter_type')->nullable();
            $table->string('company')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('chassis')->unique();
            $table->string('engine')->nullable();
            $table->date('date')->nullable();
            $table->double('price')->default(0);
            $table->double('expense')->default(0);
            $table->double('total')->default(0);
            $table->double('sale_price')->default(0);
            $table->double('min_price')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('Available');
            $table->string('type')->default('Bike');
            $table->string('purchase_type')->default('Import');
            $table->bigInteger('import_id')->nullable();
            $table->bigInteger('refID');
            $table->boolean('profitable')->default(true);
            $table->foreignId('vendor_id')->constrained('accounts')->cascadeOnDelete();
            $table->boolean('is_profit_distributed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
