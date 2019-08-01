<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookMeetingRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_meeting_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('booked_by_user_id');
            $table->string('booking_type');
            $table->string('booking_type_other')->nullable();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('project_name');
            $table->mediumText('meeting_title');
            $table->longText('meeting_description');
            $table->string('repeat_type')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('occurrence')->nullable();
            $table->string('custom_days')->nullable();
            $table->string('custom_selection')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('reference_booking_id')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('CASCADE');
            $table->foreign('booked_by_user_id')->references('id')
                ->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_meeting_rooms', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['booked_by_user_id']);
        });

        Schema::dropIfExists('book_meeting_rooms');
    }
}