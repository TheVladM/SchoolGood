<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_loans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('borrowed_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();
            $table->decimal('daily_penalty_rate', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('returned_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
