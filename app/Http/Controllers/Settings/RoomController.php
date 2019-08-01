<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RoomService;
use Illuminate\Http\Response;
use App\Http\Requests\Rooms\RoomRequest;
use stdClass;

class RoomController extends Controller
{
    private $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    /**
     * Function to get list of all rooms.
     */
    public function getAllRoomsResource()
    {
        return $this->roomService->getAllRoomsResource();
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $rooms = $this->roomService->getAllRooms();

        $utilities = $this->roomService->getAllUtilities();

        $roomDetails = new stdClass();

        if (isset($request->input()['roomId']) && ($request->input()['roomId'] != '')) {

            $roomDetails = $this->roomService->getRoomDetails($request->input()['roomId']);
        }

        return view("settings.room.list-rooms")->with([
            'rooms' => $rooms,
            'utilities' => $utilities,
            'roomDetails' => $roomDetails
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function show()
    {
        $rooms = $this->roomService->getAllRooms();
        $utilities = $this->roomService->getAllUtilities();

        return view('settings.room.add-room')->with([
            'rooms' => $rooms,
            'utilities' => $utilities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  RoomRequest $request
     * @return Response
     */
    public function store(RoomRequest $request)
    {
        $saveRoomDetails = $this->roomService->saveRoomDetails($request->except(['_token', '_method']));

        if (isset($saveRoomDetails->id)) {
            return redirect('roomManager')->with('success_message', __('Rooms details saved successfully!!'));
        } else if ($saveRoomDetails == __('error_record_exists')) {
            return redirect()->back()->with('error_message', __('Rooms name already exists!!'));
        }

        return redirect()->back()->with('error_message', __('Unable to save room details!!'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoomRequest $request
     * @param int
     * @return Response
     */
    public function update(RoomRequest $request, $id)
    {
        $updateRoomDetails = $this->roomService->updateRoomDetails($id, $request->except(['_token', '_method']));

        if ($updateRoomDetails == __('error_record_exists')) {
            return redirect()->back()->with('error_message', __('Rooms name already exists!!'));
        } else if ($updateRoomDetails == 1) {
            return redirect()->back()->with('success_message', __('Rooms details updated successfully!!'));
        }

        return redirect()->back()->with('error_message', __('Unable to update room details!!'));
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return string
     */
    public function destroy($id): string
    {
        $deleteRoom = $this->roomService->deleteRoom($id);

        if ($deleteRoom) {
            return redirect('roomManager')->with('success_message', __('Rooms deleted successfully!!'));
        } else {
            return redirect('roomManager')->with('error_message', __('Unable to delete room!!'));
        }
    }

}
