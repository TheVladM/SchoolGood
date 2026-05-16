<?php

use App\Enums\ClassroomSection;
use App\Enums\StudentSchoolYearStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_school_year_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained()->nullOnDelete();
            $table->string('classroom_name_snapshot');
            $table->string('level_snapshot');
            $table->enum('section_snapshot', ClassroomSection::values());
            $table->enum('status', StudentSchoolYearStatus::values())->default(StudentSchoolYearStatus::Active->value);
            $table->decimal('final_average', 5, 2)->nullable();
            $table->string('final_result')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('promoted_from_id')->nullable()->constrained('student_school_year_records')->nullOnDelete();
            $table->timestamp('promoted_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'school_year_id'], 'student_year_unique_record');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_school_year_records');
    }
};
