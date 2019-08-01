<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RoomService;
use App\Services\CalendarSettingsService;
use App\Http\Requests\BookMeetingRoomRequest;
use App\Services\BookMeetingRoomService;
use Illuminate\Support\Facades\Validator;
use App\Models\BookingType;

class RoomBookingController extends Controller
{
    private $roomService;
    private $calendarSettingsService;
    private $bookMeetingRoomService;

    public function __construct(RoomService $roomService,
                                CalendarSettingsService $calendarSettingsService,
                                BookMeetingRoomService $bookMeetingRoomService)
    {
        $this->roomService = $roomService;
        $this->calendarSettingsService = $calendarSettingsService;
        $this->bookMeetingRoomService = $bookMeetingRoomService;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->date)) {
            $dateFilter = $request->date;
        } else {
            $dateFilter = date('Y-m-d');
        }

        $bookingTypes = BookingType::all();

        $rooms = $this->roomService->getAllRooms();
        $calendarSettings = $this->calendarSettingsService->getCalendarSettings($dateFilter);

        if ($calendarSettings == 'error') {
            return redirect('dashboard')->with('error_message', __('Invalid date!!!'));
        }

        return view('dashboard.dashboard', [
            'bookingTypes' => $bookingTypes,
            'rooms' => $rooms,
            'calendarSettings' => $calendarSettings
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return Collection
     */
    public function getMeetingsEvents(Request $request): Collection
    {
        return $this->bookMeetingRoomService->getMeetingsEvents($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BookMeetingRoomRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookMeetingRoomRequest $request)
    {
        $customDays = [];
        if(isset($request->custom_days))
        {
            $customDays = implode(',', $request->custom_days);
        }

        $validator = Validator::make($request->all(), [
            'room_id' => 'CheckRoomAvailability:' . $request->start_time . ',' . $request->end_time .
                ',' . $request->booking_date . ',' . $request->repeat_type . ',' . $request->reference_booking_id .
                ',' . $request->occurrence . ',' . json_encode($customDays) . ',' . $request->custom_selection .
                ',' . $request->end_date,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $saveMeetingDetails = $this->bookMeetingRoomService->bookMeetingRoom($request->except(['_token']));

        if ($saveMeetingDetails->id) {
            $message = __('success');
            $request->session()->flash('success_message', __('Room booked successfully!!'));
        } else {
            $message = __('error');
            $request->session()->flash('error_message', __('Failed to book meeting Room!!'));
        }

        return $message;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BookMeetingRoomRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookMeetingRoomRequest $request, $id)
    {
        $customDays = [];
        if(isset($request->custom_days))
        {
            $customDays = implode(',', $request->custom_days);
        }

        $validator = Validator::make($request->all(), [
            'room_id' => 'CheckRoomAvailability:' . $request->start_time . ',' . $request->end_time .
                ',' . $request->booking_date . ',' . $request->repeat_type . ',' . $request->reference_booking_id .
                ',' . $request->occurrence . ',' . json_encode($customDays) . ',' . $request->custom_selection .
                ',' . $request->end_date.','.$id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updateMeetingDetails = $this->bookMeetingRoomService->updateMeetings($id, $request->except(['_token']));

        if ($updateMeetingDetails) {
            $message = __('success');
            $request->session()->flash('success_message', __('Meeting details updated successfully!!'));
        } else {
            $message = __('error');
            $request->session()->flash('error_message', __('Failed to update meeting details!!'));
        }

        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $deleteMeeting = $this->bookMeetingRoomService->deleteMeetings($id, $request);

        if ($deleteMeeting) {
            $message = __('success');
            $request->session()->flash('success_message', __('Meeting deleted successfully!!'));
        } else {
            $message = __('error');
            $request->session()->flash('error_message', __('Unable to delete meeting!!'));
        }

        return $message;
    }

}
