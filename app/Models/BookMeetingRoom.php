<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookMeetingRoom extends Model
{
    protected $fillable = ['room_id', 'booked_by_user_id', 'booking_type', 'booking_type_other', 'booking_date',
        'start_time', 'end_time', 'project_name', 'meeting_description', 'meeting_title', 'repeat_type', 'end_date',
        'reference_booking_id', 'updated_by', 'deleted_at', 'custom_selection', 'occurrence', 'custom_days'];

    protected $casts = [
        'custom_days' => 'json'
    ];

    /**
     * One to one relationship between user and book_meeting_rooms table.
     * @return HasOne
     */
    public function user_details(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'booked_by_user_id');
    }

    /**
     * One to one relationship between rooms and book_meeting_rooms table.
     * @return HasOne
     */
    public function room_details(): HasOne
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
