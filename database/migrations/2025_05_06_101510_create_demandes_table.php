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
        Schema::create('demandes', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->string('typeDemande');
        $table->string('descDemande');
        $table->string('justDemande');
        $table->string('duree');
        $table->string('urgence');
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
