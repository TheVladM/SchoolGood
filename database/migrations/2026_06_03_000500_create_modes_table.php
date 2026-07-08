<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modes', function (Blueprint $table): void {
            $table->id('idMode');
            $table->string('libelle');
            $table->text('information')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();

            $table->foreign('idFondateur')->references('ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modes');
    }
};
