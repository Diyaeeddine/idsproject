<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $currentYear = date('Y');

        Schema::create('budgets', function (Blueprint $table) use ($currentYear) {
            $table->id();
            $table->string('intitule');
            $table->decimal('budget_previsionnel', 15, 2);
            $table->date('atterrissage');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
