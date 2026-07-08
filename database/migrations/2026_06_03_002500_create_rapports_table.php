<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table): void {
            $table->id('idRap');
            $table->string('libelle');
            $table->integer('points')->default(0);
            $table->string('matricule');
            $table->unsignedBigInteger('idAca');
            $table->text('commentaire')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
            $table->foreign('idAca')->references('idAnnee')->on('annee_academiques')->cascadeOnDelete();
            $table->foreign('idPers')->references('idPers')->on('personnes')->nullOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
