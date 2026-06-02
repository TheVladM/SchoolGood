<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->foreignId('timetable_entry_id')
                ->nullable()
                ->after('classroom_id')
                ->constrained('timetable_entries')
                ->cascadeOnDelete();
            $table->unique(['timetable_entry_id', 'classroom_id'], 'courses_timetable_classroom_unique');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropUnique('courses_timetable_classroom_unique');
            $table->dropConstrainedForeignId('timetable_entry_id');
        });
    }
};
