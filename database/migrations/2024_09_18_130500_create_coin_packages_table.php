<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('coin_packages', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 2);
            $table->integer('coins');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coin_packages');
    }
}