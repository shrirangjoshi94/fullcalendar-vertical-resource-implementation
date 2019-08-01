<?php

namespace App\Observers;

use App\Models\Room;
use App\Services\BookMeetingRoomService;

class RoomObserver
{
    private $bookMeetingRoomService;

    public function __construct(BookMeetingRoomService $bookMeetingRoomService)
    {
        $this->bookMeetingRoomService = $bookMeetingRoomService;
    }

    /**
     * Handle the room "created" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function created(Room $room)
    {
        //
    }

    /**
     * Handle the room "updated" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function updated(Room $room)
    {
        if($room->isDirty('status'))
        {
            if ($room->status == false)
            {
                $status = false;
                $this->bookMeetingRoomService->meetingsStatusUpdate($room->id, $status);
            }
        }

        return;
    }

    /**
     * Handle the room "deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function deleted(Room $room)
    {
        //
    }

    /**
     * Handle the room "restored" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function restored(Room $room)
    {
        //
    }

    /**
     * Handle the room "force deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function forceDeleted(Room $room)
    {
        //
    }
}
