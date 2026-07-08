<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epreuves', function (Blueprint $table): void {
            $table->id('idEpreuve');
            $table->string('libelle');
            $table->string('urlDoc')->nullable();
            $table->string('auteur')->nullable();
            $table->unsignedBigInteger('idNature');
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();

            $table->foreign('idNature')->references('idNature')->on('nature_epreuves')->cascadeOnDelete();
            $table->foreign('idPers')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epreuves');
    }
};
