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
            $table->string("meter_type")->nullable();
            $table->string("company")->nullable();
            $table->string("model")->nullable();
            $table->string("color")->nullable();
            $table->string("chassis")->unique();
            $table->string("engine")->nullable();
            $table->date('date')->nullable();
            $table->float("price",15,2)->default(0);
            $table->float("expense",15,2)->default(0);
            $table->float("total",15,2)->default(0);
            $table->float("sale_price",15,2)->default(0);
            $table->text('notes')->nullable();
            $table->string("status")->default("Available");
            $table->string("type")->default("Bike");
            $table->string("purchase_type")->default("Import");
            $table->bigInteger('import_id');
            $table->bigInteger('refID');
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
