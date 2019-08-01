<?php

namespace App\Services;

use App\Repository\RoomRepository;
use App\Models\Utilities;
use App\Services\RoomUtilityService;
use Illuminate\Database\Eloquent\Collection;

class RoomService
{
    private $roomRepository;
    private $roomUtilityService;
    private $bookMeetingRoomService;

    public function __construct(RoomRepository $roomRepository, RoomUtilityService $roomUtilityService,
                                BookMeetingRoomService $bookMeetingRoomService)
    {
        $this->roomRepository = $roomRepository;
        $this->roomUtilityService = $roomUtilityService;
        $this->bookMeetingRoomService = $bookMeetingRoomService;
    }

    /**
     * Function to get all the rooms.
     * @return Collection
     */
    public function getAllRooms(): Collection
    {
        return $this->roomRepository->getAllRooms();
    }

    /**
     * @return Collection
     */
    public function getAllUtilities(): Collection
    {
        return Utilities::all();
    }

    /**
     *
     */
    public function getAllRoomsResource()
    {
        $rooms = $this->getAllRooms();

        $resources = [];

        if(isset($rooms) && !empty($rooms))
        {
            for($i = 0; $i < count($rooms); $i++) {
                $resources[$i]['id'] = $rooms[$i]->id;
                $resources[$i]['title'] = $rooms[$i]->room_name;
                $resources[$i]['maximum_capacity'] = $rooms[$i]->maximum_capacity;
                $resources[$i]['room_description'] = $rooms[$i]->room_description;
                $resources[$i]['status'] = $rooms[$i]->status;
                $resources[$i]['room_utilities'] = $rooms[$i]->roomUtilities;

                if(!$rooms[$i]->status) {

                    if(date('Y-m-d') == date('Y-m-d',strtotime($rooms[$i]->inactive_from))) {
                        $resources[$i]['businessHours'] = [
                        'startTime' => '00:00',
                            'endTime' => date('H:i:s',strtotime($rooms[$i]->inactive_from)),
                        ];
                    } else {
                        $resources[$i]['businessHours'] = [
                        'startTime' => '00:00',
                            'endTime' => '00:00:00',
                        ];
                    }
                }
            }
        }

        return $resources;
    }

    /**
     * Get the details of the room.
     * @param int
     * @return object
     */
    public function getRoomDetails(int $id)
    {
        return $this->roomRepository->getRoomDetails($id);
    }

    /**
     * @param array
     * @return string | object
     */
    public function saveRoomDetails(array $attributes)
    {
        $checkIfRoomExists = $this->roomRepository->where(array(
            'room_name' => $attributes['room_name'],
        ))->first();

        if (isset($checkIfRoomExists)) {
            return __('error_record_exists');
        }

        $attributes['created_by'] = auth()->user()->id;

        $saveRoomDetails = $this->roomRepository->create($attributes);

        if (isset($saveRoomDetails->id) && isset($attributes['utility'])) {

            $this->roomUtilityService->addNewRoomUtilityMapping($saveRoomDetails->id, $attributes['utility']);
        }

        return $saveRoomDetails;
    }

    /**
     * @param int
     * @param array
     * @return int| string
     */
    public function updateRoomDetails(int $id, array $attributes)
    {
        $checkIfRoomExists = $this->roomRepository->where(array(
            'room_name' => $attributes['room_name'],
        ))->first();

        if(isset($checkIfRoomExists)) {

            if ($checkIfRoomExists->id != $id) {

                return __('error_record_exists');
            }
        }

        $attributes['updated_by'] = auth()->user()->id;

        if(!$attributes['status']) {
            $attributes['inactive_from'] = $this->bookMeetingRoomService->getRoomInactiveTime($id);
        } else {
            $attributes['inactive_from'] = null;
        }

        $updateRoomDetails = $this->roomRepository->update($id, $attributes);

        if ($updateRoomDetails && isset($attributes['utility'])) {

            $this->roomUtilityService->updateRoomUtilityMapping($id, $attributes['utility']);
        }

        return $updateRoomDetails;
    }

    /**
     * @param int
     * @return int
     */
    public function deleteRoom(int $id): int
    {
        return $this->roomRepository->destroy($id);
    }

}
