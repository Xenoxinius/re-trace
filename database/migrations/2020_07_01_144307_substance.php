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
        if (!Schema::hasTable('substance')) {

            Schema::create('substance', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->string("name_nl")->nullable();
                $table->string("name_fr")->nullable();
                $table->double('specific_weight')->nullable();
                $table->string("code")->unique();
                $table->BigInteger("parent")->nullable()->unsigned();
                $table->BigInteger("unit_id")->unsigned();
                $table->string('comments')->nullable();
                $table->boolean("is_hazardous");
                $table->dateTime("created_at")->default(date("Y-m-d H:i:s"));
                $table->dateTime("updated_at")->default(date("Y-m-d H:i:s"));

            });


            Schema::table('substance', function (Blueprint $table) {
                $table->foreign('parent')
                    ->references('id')
                    ->on('substance')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->foreign('unit_id')
                    ->references('id')
                    ->on('unit')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('substance');

    }
}
