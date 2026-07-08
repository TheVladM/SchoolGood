<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annee_academiques', function (Blueprint $table): void {
            $table->id('idAnnee');
            $table->string('libelle');
            $table->string('periode')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annee_academiques');
    }
};
