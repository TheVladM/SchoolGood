<?php

use App\Enums\SchoolYearStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_years', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->date('diploma_awarded_on')->nullable();
            $table->date('promotion_opens_on')->nullable();
            $table->enum('status', SchoolYearStatus::values())->default(SchoolYearStatus::Planned->value);
            $table->foreignId('next_school_year_id')->nullable()->constrained('school_years')->nullOnDelete();
            $table->timestamp('promoted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_years');
    }
};
