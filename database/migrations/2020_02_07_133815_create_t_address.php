<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_address', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->integer('country_id');
            $table->integer('city_id');
            $table->integer('district_id');
            $table->integer('ward_id');
            $table->string('area');
            $table->string('street');
            $table->string('address_number');
            $table->string('lat');
            $table->string('Ing');
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
        Schema::dropIfExists('t_address');
    }

}
