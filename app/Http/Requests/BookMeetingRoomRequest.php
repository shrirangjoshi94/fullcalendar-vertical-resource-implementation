<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookMeetingRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //TODO validation to check whether that room is free on that date and time.

        return [
            'booking_type' => 'required|string',
            'booking_type_other' => 'required_if:booking_type,other|string',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'room_id' => 'required|integer',
            'booking_date' => ['required', 'date', 'date_format:Y-m-d'],
            'meeting_title' => 'required|string',
            'meeting_description' => ['required', 'string'],
            'project_name' => ['required', 'string'],
            'custom_days' => 'array|required_if:repeat_type,custom___custom',
            'custom_selection' => ['required_if:repeat_type, custom___custom'],
            'occurrence' => ['required_if:custom_selection,occurrence', 'numeric', 'min:1', 'sometimes', 'required_unless:repeat_type,'],
            'end_date' => ['required_if:custom_selection, endDate', 'date', 'date_format:Y-m-d', 'after:booking_date'],
        ];
    }

    public function messages()
    {
        return [
            'booking_type.required' => __('Please select a booking type'),
            'booking_type_other.required_if' => __('Please enter booking type'),
            'booking_date.required' => __('Please select date'),
            'end_time.after' => __('Meeting end time must be after the meeting start time'),
            'room_id.required' => __('Please select room'),
            'occurrence.required_if' => __('The occurrence value must be 1 or greater'),
            'custom_days.required_if' => __('Please select the days on which you would like the meeting to repeat'),
        ];
    }
}
