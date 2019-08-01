<?php

namespace App\Services;

use App\Repository\RoomUtilityRepository;
use App\Repository\RoomRepository;

class RoomUtilityService
{
    private $roomUtilityRepository;
    private $roomRepository;

    public function __construct(RoomUtilityRepository $roomUtilityRepository, RoomRepository $roomRepository)
    {
        $this->roomUtilityRepository = $roomUtilityRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     *Function to add room and utilities mapping
     * @param int
     * @param array
     * @return void
     */
    public function addNewRoomUtilityMapping(int $roomId, array $utilitiesId)
    {
        if (count($utilitiesId) > 0) {
            foreach ($utilitiesId as $value) {
                $this->roomUtilityRepository->create([
                    'room_id' => $roomId,
                    'utility_id' => $value,
                    'created_by' => auth()->user()->id,
                ]);
            }
        }

        return;
    }

    /**
     * @param int
     * @param array
     */
    public function updateRoomUtilityMapping(int $roomId, array $utilitiesId)
    {
        $roomDetails = $this->roomRepository->getRoomDetails($roomId);

        if (count($roomDetails->roomUtilities) > 0) {

            $roomDetails->roomUtilities()->delete();
        }

        $this->addNewRoomUtilityMapping($roomId, $utilitiesId);

        return;
    }

}
