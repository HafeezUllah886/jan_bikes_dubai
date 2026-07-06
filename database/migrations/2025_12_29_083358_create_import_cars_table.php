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
        Schema::create('import_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports');
            $table->string('type')->nullable();
            $table->string('meter_type')->nullable();
            $table->string('company')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('chassis')->unique();
            $table->string('engine')->nullable();
            $table->float('price')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('booking_customer_id')->nullable();
            $table->float('booking_price')->nullable();
            $table->float('booking_advance')->nullable();
            $table->date('booking_date')->nullable();
            $table->text('booking_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_cars');
    }
};
