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
        Schema::create('parts_purchases', function (Blueprint $table) {
            $table->id();
            $table->string("inv_no");
            $table->text("description");
            $table->float("qty");
            $table->float("price");
            $table->float("expense");
            $table->float("total");
            $table->float("sale_price");
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string("status")->default("Available");
            $table->string("purchase_type")->default("Import");
            $table->bigInteger('refID');
            $table->bigInteger('import_id')->nullable();
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
