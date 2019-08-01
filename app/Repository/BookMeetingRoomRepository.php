<?php

namespace App\Repository;

use App\Models\BookMeetingRoom;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BookMeetingRoomRepository extends Repository
{
    protected $model;

    public function __construct(BookMeetingRoom $model)
    {
        $this->model = $model;
    }

    /**
     * Function to get all the meetings.
     * @param string
     * @param int
     * @return Collection
     */
    public function getAllMeetings(string $dateFilter, $userId): Collection
    {
        $meetings = $this->model::where([
            ['status', true],
            ['deleted_at', null]
        ])
            ->where(function ($query) use ($dateFilter, $userId) {

                if ($userId > 0) {
                    $query->where('booked_by_user_id', $userId);
                }

                $query->where(function ($query) use ($dateFilter) {
                    $query->whereDate('booking_date', $dateFilter);
                });
            })->with(['user_details', 'room_details'])->get();

        return $meetings;
    }

    /**
     * Function to check availability of room before saving in DB.
     * @param array
     * @param array
     * @return Collection
     */
    public function checkAvailability(array $condition, array $arrBookingDates)
    {
        $checkAvailability = $this->model::where([
            'room_id' => $condition['room_id'],
            'status' => true,
            'deleted_at' => null
        ]);

        $checkAvailability->whereIn('booking_date', $arrBookingDates);

        $checkAvailability->where(function ($query) use ($condition) {
            $query->where('start_time', '>=', $condition['start_time'])
                ->where('start_time', '<', $condition['end_time']);

            $query->orWhere('end_time', '>', $condition['start_time'])
                ->where('end_time', '<', $condition['start_time']);

            $query->orWhere('start_time', '<', $condition['start_time'])
                ->where('end_time', '>', $condition['start_time']);
        });

        return $checkAvailability->get();
    }

    /**
     * @param int
     * @param bool
     * @param string
     * @param string
     * @return bool
     */
    public function meetingsStatusUpdate($roomId, $status, $currentDate, $currentTime): bool
    {
        $meetingStatusUpdate = $this->model->where([
            ['room_id', $roomId],
            ['status', !$status]
        ]);

        $meetingStatusUpdate->where(function ($query) use ($currentDate, $currentTime) {

            $query->where(function ($query1) use ($currentDate, $currentTime) {
                $query1->whereDate('booking_date', $currentDate);
                $query1->whereTime('start_time', '>=', $currentTime);
            });

            $query->orWhere(function ($query2) use ($currentDate, $currentTime) {

                $query2->whereNotNull('end_date');

                $query2->where(function ($query3) use ($currentDate, $currentTime) {

                    $query3->orWhere(function ($query4) use ($currentDate) {

                        $query4->whereDate('booking_date', '<', $currentDate);
                        $query4->whereDate('end_date', '>', $currentDate);
                    });

                    $query3->orWhere(function ($query5) use ($currentDate, $currentTime) {
                        $query5->whereDate('end_date', $currentDate);
                        $query5->whereTime('start_time', '>=', $currentTime);
                    });
                });
            });

            $query->orWhere(function ($query5) use ($currentDate) {

                $query5->whereNull('end_date');
                $query5->whereDate('booking_date', '>', $currentDate);
            });
        });

        return $meetingStatusUpdate->update([
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param int
     * @param string
     * @param string
     * @return Collection
     */
    public function getRoomInactiveTime($roomId, $currentDate, $currentTime)
    {
        $getRoomInactiveTime = $this->model->where([
            ['room_id', $roomId],
            ['status', true]
        ]);

        $getRoomInactiveTime->where(function ($query) use ($currentDate, $currentTime) {

            $query->where(function ($query1) use ($currentDate, $currentTime) {
                $query1->whereDate('booking_date', $currentDate);
                $query1->whereTime('start_time', '<', $currentTime);
                $query1->whereTime('end_time', '>', $currentTime);
            });

            $query->orWhere(function ($query2) use ($currentDate, $currentTime) {

                $query2->whereNotNull('end_date');

                $query2->where(function ($query3) use ($currentDate, $currentTime) {

                    $query3->orWhere(function ($query4) use ($currentDate, $currentTime) {

                        $query4->whereDate('booking_date', '<', $currentDate);
                        $query4->whereDate('end_date', '>', $currentDate);
                        $query4->whereTime('start_time', '<', $currentTime);
                        $query4->whereTime('end_time', '>', $currentTime);
                    });

                    $query3->orWhere(function ($query5) use ($currentDate, $currentTime) {
                        $query5->whereDate('end_date', $currentDate);
                        $query5->whereTime('start_time', '<', $currentTime);
                        $query5->whereTime('end_time', '>', $currentTime);
                    });
                });
            });

        });

        return $getRoomInactiveTime->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteMeetings($id)
    {
        return $this->model->where(['id' => $id])->update([
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param $referenceId
     * @param $bookingDate
     * @return mixed
     */
    public function deleteMeetingsByReferenceIdByBookingDate($referenceId, $bookingDate)
    {
        return $this->model->where([
            ['reference_booking_id', $referenceId],
            ['booking_date', '>=', $bookingDate]
        ])->update([
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param $referenceId
     * @return mixed
     */
    public function deleteMeetingsByReferenceId($referenceId)
    {
        return $this->model->where('reference_booking_id', $referenceId)->update([
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param $referenceId
     * @return mixed
     */
    public function updateReferenceId($referenceId)
    {
        return $this->model->where('id', $referenceId)->update([
            'reference_booking_id' => $referenceId
        ]);
    }

    /**
     * @param $meetingId
     * @return mixed
     */
    public function getMeeting($meetingId)
    {
        return $this->model->find($meetingId);
    }

    /**
     * @param $bookingDate
     * @param $referenceBookingId
     * @param $arrUpdateData
     * @return mixed
     */
    public function updateByReferenceIdByBookingDate($bookingDate, $referenceBookingId, $arrUpdateData)
    {
        return $this->model->where([['booking_date', '>=', $bookingDate], ['reference_booking_id', '=', $referenceBookingId]])
            ->update($arrUpdateData);
    }

}
