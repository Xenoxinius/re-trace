<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Substance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('substance', function (Blueprint $table) {
            $table->id();
            $table->integer("article_number");
            $table->string("stream");
            $table->integer("code");
            $table->string("name");
            $table->string("nature_of_materials");
            $table->integer("specific_weight_in_kg/m3");
            $table->integer("weight_in_kg/m3");
            $table->string("comments");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('substance');

    }
}
