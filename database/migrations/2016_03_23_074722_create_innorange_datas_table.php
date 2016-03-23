<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInnorangeDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('innorange_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('measurement_point')->unsigned();
            $table->integer('visitors')->unsigned();
            $table->string('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('innorange_datas');
    }
}
