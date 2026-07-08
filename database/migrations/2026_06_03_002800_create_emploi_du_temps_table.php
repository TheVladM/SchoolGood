<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emploi_du_temps', function (Blueprint $table): void {
            $table->id('idTemps');
            $table->string('jour');
            $table->time('heure');
            $table->unsignedBigInteger('idClasse');
            $table->unsignedBigInteger('idCours');
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idClasse')->references('idClasse')->on('classes')->cascadeOnDelete();
            $table->foreign('idCours')->references('idCours')->on('cours')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
