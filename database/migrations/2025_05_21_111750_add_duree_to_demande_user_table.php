// 1. Premièrement, créons une migration pour ajouter la colonne duree à la table demande_user
// database/migrations/yyyy_mm_dd_add_duree_to_demande_user_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDureeToDemandeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('demande_user', 'duree')) {
            Schema::table('demande_user', function (Blueprint $table) {
                $table->integer('duree')->nullable()->comment('Durée en secondes pour remplir le formulaire');
                $table->timestamp('debut_remplissage')->nullable()->comment('Horodatage de début du remplissage');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demande_user', function (Blueprint $table) {
            $table->dropColumn('duree');
            $table->dropColumn('debut_remplissage');
        });
    }
}
