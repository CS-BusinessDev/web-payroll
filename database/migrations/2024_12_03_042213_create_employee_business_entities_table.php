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
        Schema::create('employee_business_entities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('business_entity_id')
                ->constrained()
                ->onDelete('cascade');
            $table->decimal('salary', 15, 2)->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_business_entities');
    }
};
