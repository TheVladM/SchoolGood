<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frequentes', function (Blueprint $table): void {
            $table->id('idFrequente');
            $table->unsignedBigInteger('idSalle');
            $table->unsignedBigInteger('idAcademi');
            $table->string('matricule');
            $table->text('commentaire')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idSalle')->references('idSalle')->on('salles')->cascadeOnDelete();
            $table->foreign('idAcademi')->references('idAnnee')->on('annee_academiques')->cascadeOnDelete();
            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
            $table->unique(['idSalle', 'idAcademi', 'matricule'], 'frequentes_unique_triplet');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frequentes');
    }
};
