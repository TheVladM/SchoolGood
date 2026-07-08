<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table): void {
            $table->id('idEval');
            $table->float('note')->nullable();
            $table->text('appreciation')->nullable();
            $table->string('matricule');
            $table->unsignedBigInteger('idEpreuve');
            $table->unsignedBigInteger('idCours');
            $table->unsignedBigInteger('idSession');
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
            $table->foreign('idEpreuve')->references('idEpreuve')->on('epreuves')->cascadeOnDelete();
            $table->foreign('idCours')->references('idCours')->on('cours')->cascadeOnDelete();
            $table->foreign('idSession')->references('idSession')->on('exams')->cascadeOnDelete();
            $table->foreign('idPers')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
