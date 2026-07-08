<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table): void {
            $table->string('matricule')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->date('dateNaissance')->nullable();
            $table->string('lieuNaissance')->nullable();
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
            $table->string('langue')->nullable();
            $table->string('photoURL')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idVilleNaissance')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idVilleNaissance')->references('idVille')->on('ville_naissances')->nullOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
