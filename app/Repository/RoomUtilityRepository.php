<?php

namespace App\Repository;

use App\Models\RoomUtilities;

class RoomUtilityRepository extends Repository
{
    protected $model;

    public function __construct(RoomUtilities $model)
    {
        $this->model = $model;
    }

}
