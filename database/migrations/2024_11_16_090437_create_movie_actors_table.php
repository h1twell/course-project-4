<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieActorsTable extends Migration
{
    public function up()
    {
        Schema::create('movie_actors', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained('movies'); // MovieID
            $table->foreignId('actor_id')->constrained('actors'); // ActorID
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_actors');
    }
}
