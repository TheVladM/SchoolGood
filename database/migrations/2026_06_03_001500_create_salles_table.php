<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salles', function (Blueprint $table): void {
            $table->id('idSalle');
            $table->string('libelle');
            $table->string('position')->nullable();
            $table->float('surface')->nullable();
            $table->unsignedBigInteger('idClasse');
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idClasse')->references('idClasse')->on('classes')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
