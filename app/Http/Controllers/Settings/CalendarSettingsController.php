<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\CalendarSettingsService;
use Illuminate\Http\Request;

class CalendarSettingsController extends Controller
{

    private $calendarSettingsService;

    public function __construct(CalendarSettingsService $calendarSettingsService)
    {
        $this->calendarSettingsService = $calendarSettingsService;
    }

    /**
     * Function to get the calendar settings from the config files.
     * @param Request $request
     * @return array
     */
    public function getCalendarSettings(Request $request)
    {
        $defaultDate = $request->input()['defaultDate'];

        return $this->calendarSettingsService->getCalendarSettings($defaultDate);
    }

}
