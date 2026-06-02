<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homework_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homework_id')->constrained('homeworks')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->decimal('grade', 5, 2)->nullable();
            $table->string('file_path')->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['homework_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homework_submissions');
    }
};
