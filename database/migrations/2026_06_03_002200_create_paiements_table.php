<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table): void {
            $table->id('idPaie');
            $table->string('matricule');
            $table->unsignedBigInteger('idAca');
            $table->decimal('montant', 10, 2);
            $table->string('url')->nullable();
            $table->text('comentaire')->nullable();
            $table->unsignedBigInteger('idMode');
            $table->string('operation_ID')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->date('datePaie')->nullable();
            $table->dateTime('dateEnregistrer')->nullable();
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
            $table->foreign('idAca')->references('idAnnee')->on('annee_academiques')->cascadeOnDelete();
            $table->foreign('idMode')->references('idMode')->on('modes')->cascadeOnDelete();
            $table->foreign('idPers')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
