<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table): void {
            $table->id('idSession');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('idTrimestre');
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();

            $table->foreign('idTrimestre')->references('idTrimes')->on('trimestres')->cascadeOnDelete();
            $table->foreign('idPers')->references('idPers')->on('personnes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
