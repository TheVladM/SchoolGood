<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table): void {
            $table->id('idMessages');
            $table->unsignedBigInteger('idExp_Pers');
            $table->unsignedBigInteger('idParent')->nullable();
            $table->string('objet');
            $table->text('information');
            $table->string('type_message')->nullable();
            $table->unsignedBigInteger('AnneeAcade')->nullable();
            $table->boolean('valider')->default(false);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idExp_Pers')->references('idPers')->on('personnes')->cascadeOnDelete();
            $table->foreign('idParent')->references('idParent')->on('parents')->nullOnDelete();
            $table->foreign('AnneeAcade')->references('idAnnee')->on('annee_academiques')->nullOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
