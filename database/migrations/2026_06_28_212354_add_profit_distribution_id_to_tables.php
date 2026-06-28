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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('profit_distribution_id')->nullable()->constrained('profit_distributions')->nullOnDelete();
        });
        Schema::table('sale_parts', function (Blueprint $table) {
            $table->foreignId('profit_distribution_id')->nullable()->constrained('profit_distributions')->nullOnDelete();
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('profit_distribution_id')->nullable()->constrained('profit_distributions')->nullOnDelete();
        });
        Schema::table('extra_profit', function (Blueprint $table) {
            $table->foreignId('profit_distribution_id')->nullable()->constrained('profit_distributions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
