<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('category')->nullable();
            $table->string('language')->nullable();
            $table->unsignedInteger('total_copies')->default(1);
            $table->string('shelf_location')->nullable();
            $table->unsignedInteger('loan_duration_days')->default(7);
            $table->decimal('late_fee_per_day', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('acquired_at')->nullable();
            $table->foreignId('managed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
