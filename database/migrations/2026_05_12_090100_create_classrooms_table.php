<?php

use App\Enums\ClassroomSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('level');
            $table->enum('section', ClassroomSection::values());
            $table->string('room');
            $table->string('location')->nullable();
            $table->foreignId('main_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('language_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
