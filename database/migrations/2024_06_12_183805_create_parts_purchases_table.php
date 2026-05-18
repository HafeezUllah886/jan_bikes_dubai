<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parts_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no')->nullable();
            $table->text('description');
            $table->double('qty')->default(1);
            $table->double('price')->default(0);
            $table->double('expense')->default(0);
            $table->double('total')->default(0);
            $table->double('sale_price')->default(0);
            $table->double('min_price')->default(0);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string('status')->default('Available');
            $table->string('purchase_type')->default('Import');
            $table->bigInteger('refID');
            $table->bigInteger('import_id')->nullable();
            $table->boolean('profitable')->default(true);
            $table->foreignId('vendor_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts_purchases');
    }
};
