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
        Schema::create('tuition_fees', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // CM1, CM2, etc.
            $table->enum('section', ['francophone', 'anglophone', 'bilingue']);
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->decimal('first_installment', 12, 2)->default(0);
            $table->decimal('second_installment', 12, 2)->default(0);
            $table->decimal('third_installment', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('managed_by_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique level/section combination
            $table->unique(['level', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_fees');
    }
};
