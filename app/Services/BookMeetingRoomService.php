<?php

namespace App\Services;

use App\Repository\BookMeetingRoomRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Services\CalendarSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use DateTime;

class BookMeetingRoomService
{
    private $bookMeetingRoomRepository;
    private $calendarSettingsService;

    /**
     * @param BookMeetingRoomRepository $bookMeetingRoomRepository
     * @param CalendarSettingsService $calendarSettingsService
     * @return void
     */
    public function __construct(BookMeetingRoomRepository $bookMeetingRoomRepository, CalendarSettingsService $calendarSettingsService)
    {
        $this->bookMeetingRoomRepository = $bookMeetingRoomRepository;
        $this->calendarSettingsService = $calendarSettingsService;
    }

    /**
     * Function to save meeting details in database.
     * @param array
     * @return object
     */
    public function bookMeetingRoom(array $meetingDetails)
    {
        $meetingDetails['booked_by_user_id'] = Auth::user()->id;
        $meetingDetails = $this->getRepeatType($meetingDetails);

        if (isset($meetingDetails['repeat_type'])) {
            $objBookMeetingRoom = $referenceId = NULL;
            $meetingDetails['end_date'] = $this->calendarSettingsService->getEndDate($meetingDetails);
            $arrBookingDates = $this->calendarSettingsService->getBookingDates($meetingDetails);

            if ('custom' == $meetingDetails['repeat_type'] && 'occurrence' == $meetingDetails['custom_selection']) {
                $meetingDetails['end_date'] = NULL;
            }

            if ('custom' == $meetingDetails['repeat_type']) {
                $meetingDetails['custom_days'] = $meetingDetails['custom_days'];
            } else {
                $meetingDetails['end_date'] = NULL;
            }

            foreach ($arrBookingDates as $bookingDate) {
                $meetingDetails['booking_date'] = $bookingDate;
                $meetingDetails['reference_booking_id'] = $referenceId;
                $objBookMeetingRoom = $this->bookMeetingRoomRepository->create($meetingDetails);
                if (NULL == $referenceId) {
                    $referenceId = $objBookMeetingRoom->id;
                    $this->bookMeetingRoomRepository->updateReferenceId($referenceId);
                }
            }

            return $objBookMeetingRoom;
        }

        return $this->bookMeetingRoomRepository->create($meetingDetails);
    }

    /**
     * @param int $meetingId
     * @param array $meetingDetails
     * @return int|object
     */
    public function updateMeetings(int $meetingId, array $meetingDetails)
    {
        $meetingDetails['booked_by_user_id'] = Auth::user()->id;
        $meetingDetails['updated_by'] = Auth::user()->id;
        $objBookMeetingRoom = $this->bookMeetingRoomRepository->getMeeting($meetingId);
        $meetingDetails = $this->getRepeatType($meetingDetails);
        $custom_days = null;
        if (array_key_exists('custom_days', $meetingDetails) && '' != $meetingDetails['custom_days'] && null != $meetingDetails['custom_days']) {
            $custom_days = json_encode($meetingDetails['custom_days']);
        }
        if ($meetingDetails['repeat_type'] == $objBookMeetingRoom->repeat_type) {

            $arrUpdateData = array(
                'start_time' => $meetingDetails['start_time'],
                'end_time' => $meetingDetails['end_time'],
                'end_date' => ($meetingDetails['end_date'] ?? null),
                'project_name' => $meetingDetails['project_name'],
                'room_id' => $meetingDetails['room_id'],
                'occurrence' => ($meetingDetails['occurrence'] ?? null),
                'meeting_title' => $meetingDetails['meeting_title'],
                'meeting_description' => $meetingDetails['meeting_description'],
                'custom_days' => $custom_days,
                'updated_by' => $meetingDetails['updated_by']
            );

            if (0 == $meetingDetails['updateAll']) {
                $arrUpdateData['booking_date'] = $meetingDetails['booking_date'];
                return $this->bookMeetingRoomRepository->update($meetingId, $arrUpdateData);
            } else {
                return $this->bookMeetingRoomRepository->updateByReferenceIdByBookingDate($meetingDetails['booking_date'],
                    $meetingDetails['reference_booking_id'], $arrUpdateData);
            }

        } else {
            if ($objBookMeetingRoom->repeat_type) {
                $this->bookMeetingRoomRepository->deleteMeetingsByReferenceIdByBookingDate($objBookMeetingRoom->reference_booking_id,
                    $objBookMeetingRoom->booking_date);

                if ($meetingDetails['repeat_type']) {
                    return $this->bookMeetings($meetingDetails);
                } else {
                    $meetingDetails['reference_booking_id'] = NULL;
                    return $this->bookMeetingRoomRepository->create($meetingDetails);
                }
            } else {
                $this->bookMeetingRoomRepository->deleteMeetingsByReferenceId($objBookMeetingRoom->reference_booking_id);
                return $this->bookMeetings($meetingDetails);
            }
        }
    }

    /**
     * @param $meetingDetails
     * @return object
     */
    public function bookMeetings($meetingDetails)
    {
        $referenceId = NULL;
        $meetingDetails['end_date'] = $this->calendarSettingsService->getEndDate($meetingDetails);
        $arrBookingDates = $this->calendarSettingsService->getBookingDates($meetingDetails);

        if ('custom' == $meetingDetails['repeat_type'] && 'occurrence' == $meetingDetails['custom_selection']) {
            $meetingDetails['end_date'] = NULL;
        }

        if ('custom' == $meetingDetails['repeat_type']) {
            $meetingDetails['custom_days'] = $meetingDetails['custom_days'];
        } else {
            $meetingDetails['end_date'] = NULL;
        }

        foreach ($arrBookingDates as $bookingDate) {
            $meetingDetails['booking_date'] = $bookingDate;
            $meetingDetails['reference_booking_id'] = $referenceId;
            $objBookMeetingRoom = $this->bookMeetingRoomRepository->create($meetingDetails);
            if (NULL == $referenceId) {
                $referenceId = $objBookMeetingRoom->id;
                $this->bookMeetingRoomRepository->updateReferenceId($referenceId);
            }
        }

        return $objBookMeetingRoom;
    }

    /**
     * @param array
     * @return array
     */
    public function getRepeatType($meetingDetails): array
    {
        if (isset($meetingDetails['repeat_type'])) {
            $repeatType = explode('___', $meetingDetails['repeat_type']);
            $meetingDetails['repeat_type'] = $repeatType[0];
        }
        return $meetingDetails;
    }

    /**
     * Function to get all the booked meetings.
     * @param Request $request
     * @return Collection
     */
    public function getMeetingsEvents(Request $request): Collection
    {
        if (isset($request->dateFilter)) {
            $dateFilter = $request->dateFilter;
        } else {
            $dateFilter = date('Y-m-d');
        }

        $userId = 0;
        if (isset($request->myMeetings) && $request->myMeetings == 'show') {
            $userId = Auth::user()->id;
        }

        $meetings = $this->bookMeetingRoomRepository->getAllMeetings($dateFilter, $userId);

        if (count($meetings)) {
            foreach ($meetings as $key => $value) {
                $value->resourceId = $value->room_id;
                $value->start = $dateFilter . 'T' . $value->start_time;
                $value->end = $dateFilter . 'T' . $value->end_time;
                $value->title = $value->meeting_title;
//                $value->reference_booking_id = $value->reference_booking_id;
            }
        }

        return $meetings;
    }

    /**
     * @param array
     * @return bool
     */
    public function checkAvailability(array $condition): bool
    {
        if (isset($condition['repeat_type']) && !empty($condition['repeat_type'])) {
            $condition = $this->getRepeatType($condition);
            $arrBookingDates = $this->calendarSettingsService->getBookingDates($condition);
        } else {
            $arrBookingDates[] = $condition['booking_date'];
        }

        $checkAvailability = $this->bookMeetingRoomRepository->checkAvailability($condition, $arrBookingDates);

        if (count($checkAvailability) == 0) {
            return true;
        } else if ((count($checkAvailability) == 1) && ($checkAvailability->first()->id == $condition['meetingId'])) {
            return true;
        } else if (count($checkAvailability) > 0) {

            foreach ($checkAvailability as $key => $value) {
                if ($value->reference_booking_id != $condition['reference_booking_id']) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param int $meetingId
     * @param Request $request
     * @return bool|mixed
     */
    public function deleteMeetings(int $meetingId, Request $request)
    {
        $referenceBookingId = $request->referenceBookingId;

        $objMeeting = $this->bookMeetingRoomRepository->find($meetingId);

        if ($referenceBookingId) {
            return $this->bookMeetingRoomRepository->deleteMeetingsByReferenceIdByBookingDate($objMeeting->reference_booking_id,
                $objMeeting->booking_date);
        } else {
            return $this->bookMeetingRoomRepository->deleteMeetings($objMeeting->id);
        }
    }

    /**
     * @param $roomId
     * @param $status
     * @return bool
     * @throws \Exception
     */
    public function meetingsStatusUpdate($roomId, $status): bool
    {
        $currentDate = date('Y-m-d');
        $currentTime = new DateTime(date("H:i:s"));
        $currentTime->setTimezone(new DateTimeZone('Asia/Calcutta'));
        $currentTime = $currentTime->format('H:i:s');

        return $this->bookMeetingRoomRepository->meetingsStatusUpdate($roomId, $status, $currentDate, $currentTime);
    }

    /**
     * @param int
     * @return string
     */
    public function getRoomInactiveTime($roomId)
    {
        $currentDate = date('Y-m-d');
        $currentTime = new DateTime(date("H:i:00"));
        $currentTime->setTimezone(new DateTimeZone('Asia/Calcutta'));
        $currentTime = $currentTime->format('H:i:s');

        $getRoomInactiveTime = $this->bookMeetingRoomRepository->getRoomInactiveTime($roomId, $currentDate, $currentTime);

        if ($getRoomInactiveTime) {
            $inactiveTime = $getRoomInactiveTime->end_time;
        } else {
            $inactiveTime = $currentTime;
        }

        $inactiveTime = date('Y-m-d H:i:s', strtotime($inactiveTime));

        return $inactiveTime;
    }
}
