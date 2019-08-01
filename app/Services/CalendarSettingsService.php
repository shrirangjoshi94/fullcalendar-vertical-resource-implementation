<?php

namespace App\Services;

use DateTime;
use http\Client\Response;
use DateTimeZone;

class CalendarSettingsService
{

    /**
     *  Function to return calendar settings to the JS file.
     * @param string
     * @param string
     * @return Response | array | string
     */
    public function getCalendarSettings($dateFilter)
    {

        if (DateTime::createFromFormat('Y-m-d', $dateFilter) == FALSE) {
            return 'error';
        }

        $calendarSettings = config('calendar_settings');

        if ($calendarSettings['weekends'] == 0) {
            $calendarSettings['weekends'] = false;
        } else {
            $calendarSettings['weekends'] = true;
        }

        if ($calendarSettings['weekends'] == false) {
            if ($this->isWeekend($dateFilter)) {
                return 'error';
            }
        }

        $calendarSettings = $this->addTimeSlots($calendarSettings, $dateFilter);
        $calendarSettings['isLoggedIn'] = false;

        if (!empty(auth()->user())) {
            $calendarSettings['isLoggedIn'] = true;
        }

        //this call is to filter out the weekends currently later it will contain logic for filtering out holidays as well
        $calendarSettings = $this->getDayList($calendarSettings);

        $calendarSettings['defaultDate'] = $dateFilter;
        return $calendarSettings;
    }

    /**
     * @param array
     * @param string
     * @return array
     */
    public function addTimeSlots($calendarSettings, $dateFilter)
    {
        $minTime = $calendarSettings['minTime'];
        $startTimeSlots = [];
        $endTimeSlots = [];
        /**
         * Below 5 lines are to get the current time if the date is of today.
         */
        if ($dateFilter == date('Y-m-d')) {
            $activeMinTime = new DateTime(date("H:i:s"));
            $activeMinTime->setTimezone(new DateTimeZone($calendarSettings['time_zone']));
            $activeMinTime = $activeMinTime->format('H:i:s');
            $calendarSettings['current_active_time'] = $activeMinTime;
        }

        while ($minTime != $calendarSettings['maxTime']) {
            array_push($startTimeSlots, $minTime);
            $minTime = date('H:i:s', (strtotime($minTime) + strtotime($calendarSettings['slotDuration'])));
            array_push($endTimeSlots, $minTime);
        }

        $calendarSettings['slotDurationMinutes'] = $this->getTimeSlotMinutes($calendarSettings['slotDuration']);
        $calendarSettings['start_time'] = $startTimeSlots;
        $calendarSettings['end_time'] = $endTimeSlots;
        return $calendarSettings;
    }

    /**
     * @param string
     * @return int
     */
    public function getTimeSlotMinutes($date)
    {
        $hours = date("H", strtotime($date));
        $minutes = date("i", strtotime($date));
        $minutes = $hours * 60 + $minutes;
        return $minutes;
    }

    /**
     * Function to get the end date.
     * @param  string
     * @param int
     * @param int
     * @return string
     */
    public function getEndDate($meetingDetails): string
    {
        $calendarSettings = config('calendar_settings');
        $repeatType = $meetingDetails['repeat_type'];
        $bookingDate = $meetingDetails['booking_date'];

        if ('custom' != $repeatType) {
            $days = $calendarSettings['repeatTypes'][$repeatType] * $meetingDetails['occurrence'];
            return date('Y-m-d', strtotime($bookingDate . ' + ' . $days . ' days'));
        } else {
            if ('occurrence' == $meetingDetails['custom_selection']) {
                $days = 7 * $meetingDetails['occurrence'];
                return date('Y-m-d', strtotime($bookingDate . ' + ' . $days . ' days'));
            } else {
                return $meetingDetails['end_date'];
            }
        }
    }

    /**
     * @param $meetingDetails
     * @return array
     */
    public function getBookingDates(array $meetingDetails)
    {
        $arrBookingDate = [];
        $bookingDate = $meetingDetails['booking_date'];
        $endDate = $meetingDetails['end_date'];
        if ("" == $endDate || null == $endDate) {
            $endDate = $this->getEndDate($meetingDetails);
        }
        $calendarSettings = config('calendar_settings');

        if ('custom' != $meetingDetails['repeat_type']) {
            //if weekend condition is false go inside if statement
            if (!$calendarSettings['weekends']) {
                while ($bookingDate <= $endDate) {
                    if (!$this->isWeekend($bookingDate)) {
                        array_push($arrBookingDate, $bookingDate);
                    }
                    $bookingDate = date('Y-m-d', strtotime($bookingDate . ' +1 days'));
                }
            } else {
                while ($bookingDate <= $endDate) {
                    array_push($arrBookingDate, $bookingDate);
                    $bookingDate = date('Y-m-d', strtotime($bookingDate . ' +1 days'));
                }
            }
        } else {
            $custom_day = $meetingDetails['custom_days'];
            if ('occurrence' == $meetingDetails['custom_selection']) {
                while ($bookingDate != $endDate) {
                    $intWeekDay = date('w', strtotime($bookingDate));
                    if (in_array($intWeekDay, $custom_day)) {
                        array_push($arrBookingDate, $bookingDate);
                    }
                    $bookingDate = date('Y-m-d', strtotime($bookingDate . ' +1 days'));
                }
            } else {
                while ($bookingDate <= $endDate) {
                    $intWeekDay = date('w', strtotime($bookingDate));
                    if (in_array($intWeekDay, $custom_day)) {
                        array_push($arrBookingDate, $bookingDate);
                    }
                    $bookingDate = date('Y-m-d', strtotime($bookingDate . ' +1 days'));
                }
            }
        }

        return $arrBookingDate;
    }

    /**
     * Function to check the weekend condition for invalid date.
     * @param string
     * @return bool
     */
    public function isWeekend($dateFilter): bool
    {
        $weekDay = date('w', strtotime($dateFilter));
        if ($weekDay == 0 || $weekDay == 6) {
            return true;
        }
        return false;
    }

    /**
     * @param array
     * @return array
     */
    public function getDayList(array $calendarSettings)
    {
        foreach ($calendarSettings['dayList'] as $key => $value) {
            if (!$calendarSettings['weekends']) {
                if ($key == 0 || $key == 6) {
                    unset($calendarSettings['dayList'][$key]);
                }
            }
        }
        return $calendarSettings;
    }

}
