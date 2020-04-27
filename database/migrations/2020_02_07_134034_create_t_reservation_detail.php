<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTReservationDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_reservation_detail', function (Blueprint $table) {
            $table->bigIncrements('reservation_detail_id');
            $table->dateTime('check_out');
            $table->dateTime('check_in');
            $table->integer('quantity');
            $table->string('status');
            $table->integer('reservation_id');
            $table->integer('stay_id');
            $table->integer('room_id');
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
        Schema::dropIfExists('t_reservation_detail');
    }
}
