<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livres', function (Blueprint $table): void {
            $table->id('idLivre');
            $table->string('titre');
            $table->string('auteurs')->nullable();
            $table->decimal('prix', 10, 2)->default(0);
            $table->unsignedBigInteger('idSpecialite')->nullable();
            $table->string('edition')->nullable();
            $table->year('annee_parution')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idSpecialite')->references('idSpecialite')->on('specialites')->nullOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
