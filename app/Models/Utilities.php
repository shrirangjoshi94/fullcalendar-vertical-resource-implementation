<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilities extends Model
{
    protected $table = 'utilities';

    protected $fillable = ['utility_name', 'utility_description', 'status'];

    /**
     * get the roomUtilities that owns the Utilities
     */
    public function roomUtilities()
    {
        return $this->hasMany(RoomUtilities::class, 'utility_id', 'id');
    }

}
