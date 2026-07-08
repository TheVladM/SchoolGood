<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tranches', function (Blueprint $table): void {
            $table->id('idTranche');
            $table->string('libelle');
            $table->decimal('montant', 10, 2)->default(0);
            $table->unsignedTinyInteger('delai_mois')->default(0);
            $table->unsignedTinyInteger('delai_jour')->default(0);
            $table->unsignedBigInteger('idScolarite');
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();

            $table->foreign('idScolarite')->references('idScolarite')->on('scolarites')->cascadeOnDelete();
            $table->foreign('idFondateur')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tranches');
    }
};
