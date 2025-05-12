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
    Schema::create('champ_personnalises', function (Blueprint $table) {
        $table->id();
        $table->string('key');
        $table->string('value');
        $table->unsignedBigInteger('demande_id');
        $table->timestamps();
        $table->foreign('demande_id')->references('id')->on('demandes')->onDelete('cascade');

    });
    }

    public function down(): void
    {
        Schema::dropIfExists('champ_personnalises');
    }
};
