<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BookMeetingRoom;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $table = "rooms";

    protected $fillable = ['room_name', 'status', 'id', 'maximum_capacity', 'room_description', 'created_by', 'updated_by', 'inactive_from'];

    /**
     * room table and room_utilities table has one to many mapping.
     * @return HasMany
     */
    public function roomUtilities()
    {
        return $this->hasMany(RoomUtilities::class, 'room_id')->with('utilities' );
    }

    /**
     * room table and meetings table has one to many mapping.
     * @return HasMany
     */
    public function meetings()
    {
        return $this->hasMany(BookMeetingRoom::class, 'room_id');
    }
}
