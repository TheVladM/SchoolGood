<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('personnes', 'photoURL')) {
            Schema::table('personnes', function (Blueprint $table): void {
                $table->string('photoURL')->nullable()->after('alanyaID');
            });
        }

        Schema::table('parents', function (Blueprint $table): void {
            $table->unique(['idPers', 'matricule'], 'parents_idPers_matricule_unique');
        });
    }

    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table): void {
            $table->dropUnique('parents_idPers_matricule_unique');
        });

        Schema::table('personnes', function (Blueprint $table): void {
            $table->dropColumn('photoURL');
        });
    }
};
