<?php

use App\Enums\AnnouncementAudience;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table): void {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('classroom_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            $values = implode("','", AnnouncementAudience::values());
            DB::statement("ALTER TABLE announcements MODIFY COLUMN audience ENUM('{$values}') NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
