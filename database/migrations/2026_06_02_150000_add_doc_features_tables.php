<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('classrooms', function (Blueprint $table): void {
            $table->foreignId('room_id')->nullable()->after('section')->constrained('rooms')->nullOnDelete();
            $table->string('cycle_type')->default('standard')->after('section');
        });

        Schema::create('student_school_grades', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->string('term')->default('Annuel');
            $table->decimal('grade', 5, 2);
            $table->text('comment')->nullable();
            $table->foreignId('recorded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'school_year_id', 'subject', 'term'], 'student_grade_unique');
        });

        Schema::create('announcement_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('content');
            $table->string('audience')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('announcements', function (Blueprint $table): void {
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->json('attachments')->nullable()->after('content');
            $table->timestamp('read_at')->nullable()->after('approved_at');
        });

        Schema::table('book_loans', function (Blueprint $table): void {
            $table->unsignedInteger('overdue_days_logged')->default(0)->after('returned_at');
            $table->foreignId('penalty_payment_id')->nullable()->after('daily_penalty_rate')->constrained('payments')->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table): void {
            $table->boolean('declared_by_parent')->default(false)->after('notes');
        });

        Schema::table('school_years', function (Blueprint $table): void {
            $table->boolean('auto_promote_enabled')->default(true)->after('promoted_at');
        });
    }

    public function down(): void
    {
        Schema::table('school_years', function (Blueprint $table): void {
            $table->dropColumn('auto_promote_enabled');
        });

        Schema::table('payments', function (Blueprint $table): void {
            $table->dropColumn('declared_by_parent');
        });

        Schema::table('book_loans', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('penalty_payment_id');
            $table->dropColumn('overdue_days_logged');
        });

        Schema::table('announcements', function (Blueprint $table): void {
            $table->dropColumn(['rejection_reason', 'attachments', 'read_at']);
        });

        Schema::dropIfExists('announcement_templates');
        Schema::dropIfExists('student_school_grades');

        Schema::table('classrooms', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('room_id');
            $table->dropColumn('cycle_type');
        });

        Schema::dropIfExists('rooms');
    }
};
