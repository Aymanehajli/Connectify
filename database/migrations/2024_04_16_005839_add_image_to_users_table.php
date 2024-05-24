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

        //blob :base64 ...........
        // ndero smiya dyal fichier ex: football.png
        Schema::table('users', function (Blueprint $table) {
            //ajouter le champ image

            $table->string('image')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //pour supprimer le champ ajouté
            $table->dropColumn('image');
        });
    }
};
