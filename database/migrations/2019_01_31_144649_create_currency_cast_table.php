<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_cast', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('symbol')->index();
            $table->double('daily_price_change_persent',6, 3);
            $table->double('last_price',32, 8);
            $table->double('high_price',32, 8);
            $table->double('low_price',32, 8);
            $table->integer('count');

            $table->timestamp('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_cast');
    }
}
