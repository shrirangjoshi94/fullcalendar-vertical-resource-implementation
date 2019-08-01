<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_utilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('utility_id');
            $table->boolean('status')->default(true);
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('CASCADE');
            $table->foreign('utility_id')->references('id')->on('utilities')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_utilities', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['utility_id']);
        });

        Schema::dropIfExists('room_utilities');
    }
}
