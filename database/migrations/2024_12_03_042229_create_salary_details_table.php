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
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->constrained('salaries')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->decimal('amount', 15, 2);  // Jumlah untuk komponen ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_details');
    }
};
