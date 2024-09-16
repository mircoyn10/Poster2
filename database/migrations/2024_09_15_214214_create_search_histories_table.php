<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchHistoriesTable extends Migration
{
    /**
     * Esegui la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id(); // Chiave primaria
            $table->foreignId('user_id') // Chiave esterna legata alla tabella users
                ->constrained() // Imposta la relazione con la tabella users
                ->onDelete('cascade'); // Elimina i record associati se l'utente viene eliminato
            $table->text('prompt'); // Prompt inviato dall'utente
            $table->json('response'); // Risposte generate per le varie piattaforme
            $table->timestamps(); // Timestamps (created_at e updated_at)
        });
    }

    /**
     * Revert la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_histories');
    }
}


