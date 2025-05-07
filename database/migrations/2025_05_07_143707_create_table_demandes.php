<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id(); // crée une colonne "id" en unsignedBigInteger automatiquement
            $table->unsignedBigInteger('user_id')->nullable(); // si tu veux relier à un utilisateur
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void    
    {
        Schema::dropIfExists('demandes');
    }
};
