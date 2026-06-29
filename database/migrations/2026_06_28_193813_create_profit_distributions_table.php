<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('vehicle_profit', 10, 2)->default(0);
            $table->decimal('parts_profit', 10, 2)->default(0);
            $table->decimal('extra_profit', 10, 2)->default(0);
            $table->decimal('expenses', 10, 2)->default(0);
            $table->decimal('net_profit', 10, 2)->default(0);
            $table->bigInteger('refID');
            $table->timestamps();
        });

        Schema::create('profit_distributions_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accountID')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('profit_type', ['profit', 'loss'])->default('profit');
            $table->decimal('amount', 10, 2)->default(0);
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_distributions');
        Schema::dropIfExists('profit_distributions_details');
    }
};
