<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTOccupiedReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_occupied_reservations', function (Blueprint $table) {
            $table->bigIncrements('occupied_reservation_id');
            $table->string('check_in');
            $table->string('check_out');
            $table->string('status');
            $table->integer('stay_id');
            $table->integer('guest_id');
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
        Schema::dropIfExists('t_occupied_reservations');
    }
}
