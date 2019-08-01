<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomUtilities extends Model
{
    protected $table = 'room_utilities';

    protected $fillable = ['room_id', 'utility_id', 'status', 'created_by'];

    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function utilities()
    {
        return $this->belongsTo(Utilities::class, 'utility_id', 'id');
    }
}
