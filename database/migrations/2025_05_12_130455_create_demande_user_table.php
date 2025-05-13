<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('demande_user', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('demande_id');
        $table->unsignedBigInteger('user_id');
        $table->timestamp('date_affectation')->nullable();

        $table->timestamps();

        // Clés étrangères
        $table->foreign('demande_id')->references('id')->on('demandes')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_user');
    }
};
