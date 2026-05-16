<?php

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_entries', function (Blueprint $table): void {
            $table->id();
            $table->string('level');
            $table->enum('section', ClassroomSection::values());
            $table->string('subject');
            $table->enum('day', CourseDay::values());
            $table->time('start_time');
            $table->time('end_time');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['level', 'section', 'day', 'start_time', 'end_time'], 'timetable_unique_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
    }
};
