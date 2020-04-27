<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTStay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_stay', function (Blueprint $table) {
            $table->bigIncrements('stay_id');
            $table->string('stay_name');
            $table->integer('stay_type_id');
            $table->string('lang');
            $table->integer('rate_average');
            $table->integer('price');
            $table->string('discount');
            $table->text('description');
            $table->string('guest_count');
            $table->string('bed_count');
            $table->string('bath_count');
            $table->boolean('wifi');
            $table->boolean('smoking');
            $table->boolean('cooler');
            $table->boolean('refrigerator');
            $table->boolean('pool');
            $table->boolean('kitchen');
            $table->integer('host_id');
            $table->integer('address_id');
            $table->integer('city_id');
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
        Schema::dropIfExists('t_stay');
    }

}
