<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table): void {
            $table->id('ID');
            $table->string('nom');
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('actif')->default(true);
            $table->string('typeAdmin')->nullable();
            $table->string('mobile')->nullable();
            $table->string('alanyaID')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
