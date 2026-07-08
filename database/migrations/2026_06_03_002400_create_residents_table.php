<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table): void {
            $table->id('idResi');
            $table->unsignedBigInteger('idPers');
            $table->unsignedBigInteger('idQuartier');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idPers')->references('idPers')->on('personnes')->cascadeOnDelete();
            $table->foreign('idQuartier')->references('idQuartier')->on('quartiers')->cascadeOnDelete();
            $table->foreign('idAdmin')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
