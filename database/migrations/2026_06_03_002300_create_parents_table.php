<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table): void {
            $table->id('idParent');
            $table->unsignedBigInteger('idPers');
            $table->string('matricule');
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idPers')->references('idPers')->on('personnes')->cascadeOnDelete();
            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
