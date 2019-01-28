<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGloblaParametersListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globla_parameters_list', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('check_new_pairs');
            $table->integer('min_value');
            $table->integer('max_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('globla_parameters_list');
    }
}
