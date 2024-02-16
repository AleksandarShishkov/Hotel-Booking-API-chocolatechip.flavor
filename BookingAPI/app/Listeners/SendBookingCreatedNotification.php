<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\JsonResponse;


class SendBookingCreatedNotification
{
    protected $booking;
    protected $staff_message;
    protected $response;

    
    public function handle(BookingCreated $event): JsonResponse
    {
        $this->booking = $event->booking;

        $this->staff_message = [
            'booking id' => $this->booking->id,
            'check in date' => $this->booking->check_in_date,
            'check out date' => $this->booking->check_out_date,
            'message' => 'New booking created'
        ];

        $this->response = [
            'data' => $this->staff_message
        ];

        return response()->json($this->response);
    }
}
