<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
     use SoftDeletes;

     protected $fillable = [
          'display_name', 
          'slug'
     ];
     
     /**
     * Get the user Role.
     *
     * @return HasMany
     */
     public function users(): HasMany
     {
          return $this->hasMany('App\Models\User');
     }
}
