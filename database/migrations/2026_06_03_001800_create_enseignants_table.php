<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table): void {
            $table->id('idEnseignant');
            $table->unsignedBigInteger('idPers');
            $table->unsignedBigInteger('idCours')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idPers')->references('idPers')->on('personnes')->cascadeOnDelete();
            $table->foreign('idCours')->references('idCours')->on('cours')->nullOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
