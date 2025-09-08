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
        Schema::create('time_off_request_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description')->nullable();
        });
        //si des changement sont fais il faut aussi change l'application mobile pour update les status
        DB::table('time_off_request_types')->insert([
            ['name' => 'En attente',  'description' => 'En attente d’approbation'],
            ['name' => 'Approuvée',   'description' => 'La demande a été approuvée'],
            ['name' => 'Refusée',     'description' => 'La demande a été refusée'],
            ['name' => 'Annulée',     'description' => 'La demande a été annulée'],
            ['name' => 'Expirée',     'description' => 'La demande a expiré sans traitement'],
        ]); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_off_request_types');
    }
};
