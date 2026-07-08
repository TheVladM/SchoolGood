<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titulaires', function (Blueprint $table): void {
            $table->id('idTitulaire');
            $table->unsignedBigInteger('idPers');
            $table->unsignedBigInteger('idSalle');
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idPers')->references('idPers')->on('personnes')->cascadeOnDelete();
            $table->foreign('idSalle')->references('idSalle')->on('salles')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulaires');
    }
};
