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
            $table->string("c_no")->nullable();
            $table->string("bl_no")->nullable();
            $table->decimal("bl_amount", 15,2)->default(0);
            $table->decimal("bl_amount_pkr", 15,2)->default(0);
            $table->decimal("container_amount", 15,2)->default(0);
            $table->decimal("net_amount", 15,2)->default(0);
            $table->decimal("conversion_rate", 15,5)->default(1);
            $table->date("date");
            $table->integer('sale_id')->nullable();
            $table->bigInteger('refID');
            $table->text('key')->nullable();
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
