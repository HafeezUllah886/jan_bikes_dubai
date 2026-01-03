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
        Schema::create('import_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports');
            $table->string('part_name');
            $table->float('qty');
            $table->float("price")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_parts');
    }
};
