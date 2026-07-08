<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scolarites', function (Blueprint $table): void {
            $table->id('idScolarite');
            $table->decimal('inscription', 10, 2)->default(0);
            $table->decimal('pension', 10, 2)->default(0);
            $table->unsignedSmallInteger('nbreTranche')->default(1);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('idCycle');
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();

            $table->foreign('idCycle')->references('idCycle')->on('cycles')->cascadeOnDelete();
            $table->foreign('idFondateur')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scolarites');
    }
};
