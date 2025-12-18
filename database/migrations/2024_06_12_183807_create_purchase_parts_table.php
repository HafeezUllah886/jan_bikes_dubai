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
        Schema::create('purchase_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->string('description');
            $table->string('weight_ltr', 15,2)->nullable();
            $table->string('grade')->nullable();
            $table->integer('qty')->default(0);
            $table->integer('sold')->default(0);
            $table->decimal('price', 15,2)->default(0);
            $table->decimal('price_pkr', 15,2)->default(0);
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_parts');
    }
};
