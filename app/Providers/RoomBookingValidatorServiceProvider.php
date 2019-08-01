<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BookMeetingRoomService;
use Illuminate\Support\Facades\Validator;

/**
 * Class RoomBookingValidatorServiceProvider
 * is used for checking the validation while booking meeting rooms.
 * @package App\Providers
 */
class RoomBookingValidatorServiceProvider extends ServiceProvider
{

    public $bookMeetingRoomService;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * @param BookMeetingRoomService $bookMeetingRoomService
     */
    public function boot(BookMeetingRoomService $bookMeetingRoomService)
    {
        $this->bookMeetingRoomService = $bookMeetingRoomService;

        Validator::extend('CheckRoomAvailability', function ($attribute, $value, $parameters, $validator) {

            return $this->checkAvailability($attribute, $value, $parameters);
        }, __('Rooms is not available at the given time slot'));
    }

    /**
     * @param string
     * @param string
     * @param array
     * @return bool
     */
    public function checkAvailability(string $attribute, string $value, array $parameters): bool
    {
        $meetingId = 0;
        if(isset($parameters['9'])) {
            $meetingId = $parameters['9'];
        }

        $referenceBookingId = 0;
        if(isset($parameters['4'])) {
            $referenceBookingId = $parameters['4'];
        }

        $condition = [
            $attribute => $value,
            'start_time' => date('H:i:s', strtotime($parameters['0'])),
            'end_time' => date('H:i:s', strtotime($parameters['1'])),
            'booking_date' => $parameters['2'],
            'repeat_type' => $parameters['3'],
            'reference_booking_id' => $referenceBookingId,
            'occurrence' => $parameters['5'],
            'custom_days' => explode(',', $parameters['6']),
            'custom_selection' => $parameters['7'],
            'end_date' => $parameters['8'],
            'meetingId' => $meetingId,
        ];
        
        return $this->bookMeetingRoomService->checkAvailability($condition);
    }
}
