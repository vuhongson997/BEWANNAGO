<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTStayRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_stay_rating', function (Blueprint $table) {
            $table->bigIncrements('rating_id');
            $table->text('comment');
            $table->integer('rate');
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
        Schema::dropIfExists('t_stay_rating');
    }


}
