<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('admins', 'photoURL')) {
            Schema::table('admins', function (Blueprint $table): void {
                $table->string('photoURL')->nullable()->after('alanyaID');
            });
        }
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table): void {
            $table->dropColumn('photoURL');
        });
    }
};
