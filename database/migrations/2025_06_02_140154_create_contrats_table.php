<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratsTable extends Migration
{
    public function up()
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demandeur_id')->constrained('demandeurs')->onDelete('cascade');
            $table->foreignId('proprietaire_id')->nullable()->constrained('proprietaires')->nullOnDelete();
            $table->foreignId('navire_id')->nullable()->constrained('navires')->nullOnDelete();
            $table->foreignId('gardien_id')->nullable()->constrained('gardiens')->nullOnDelete();
            $table->text('mouvements')->nullable();
            $table->string('majoration_stationnement')->nullable();
            $table->integer('equipage')->nullable();
            $table->integer('passagers')->nullable();
            $table->integer('total_personnes')->nullable();

            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();

            $table->string('signe_par')->nullable();
            $table->string('accepte_par')->nullable();
            $table->string('lieu_signature')->nullable();
            $table->date('date_signature')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contrats');
    }
}
