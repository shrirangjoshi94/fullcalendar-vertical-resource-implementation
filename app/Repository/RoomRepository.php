<?php

namespace App\Repository;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomRepository extends Repository
{
    protected $model;

    public function __construct(Room $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getAllRooms(): Collection
    {
        return $this->model->with('roomUtilities')->get();
    }

    /**
     * @param int
     * @return object
     */
    public function getRoomDetails($id)
    {
        return $this->model->with('roomUtilities')->get()->find($id);
    }

}
