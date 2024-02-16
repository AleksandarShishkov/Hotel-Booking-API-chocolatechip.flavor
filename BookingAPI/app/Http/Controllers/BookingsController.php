<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Customer;

class BookingsController extends Controller
{

    protected $booking;
    protected $bookings;
    protected $event_listener;
    protected $validator;
    protected $room;
    protected $check_room_availability;
    protected $booking_data;
    protected $response;


    public function index() {
        $this->bookings = Booking::orderBy('id')
                                    ->with('room')
                                    ->with('customer')
                                    ->with('payments')
                                    ->get();


        if(isset($this->bookings[0])) {
            return response()->json([
                'bookings' => $this->bookings
            ]);
        }

        return response()->json([
            'bookings' => 'No bookings were made!'
        ]);
    }


    public function store(Request $request) {

        $this->validator = Validator::make($request->all(), [
            'room_id' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'total_price' => 'required'
        ]);

        if($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()->first()
            ]);
        }

        $check_in_date = date('Y-m-d', strtotime($request->check_in_date));
        $check_out_date = date('Y-m-d', strtotime($request->check_out_date));

        if($check_in_date > $check_out_date) {
            return response()->json([
                'error' => 'The checkin date cannot be after the checkout'
            ]);
        }

        $this->room = Room::find($request->room_id);

        if(!$this->room) {
            return response()->json([
                'error' => 'No such room found!'
            ]);
        }

        $this->check_room_availability = Booking::where('room_id', $request->room_id)
                                                ->where(function ($query) use ($check_in_date, $check_out_date) {
                                                    $query->whereBetween('check_in_date', [$check_in_date, $check_out_date])
                                                          ->orWhereBetween('check_out_date', [$check_in_date, $check_out_date]);
                                                })
                                                ->doesntExist();
        
        if(!$this->check_room_availability) {
            return response()->json([
                'error' => 'The room is not available for the selected dates!'
            ]);
        }

        if(!Customer::find($request->customer_id)) {
            return response()->json([
                'error' => 'No such customer found!'
            ]);
        }

        $this->booking_data = [
            'room_id' => $request->room_id,
            'customer_id' => $request->customer_id,
            'check_in_date' => $check_in_date,
            'check_out_date' => $check_out_date,
            'total_price' => $request->total_price
        ];

        if(DB::table('bookings')->insert($this->booking_data)) {
            $this->booking = Booking::latest()->first();
            $this->event_listener = event(new BookingCreated($this->booking));
            $this->response = $this->event_listener[0]->getData();
            
            return response()->json([
                'staff notification: ' => $this->response
            ]);
        }

        return response()->json([
            'There was an issue. Check the database connection!'
        ]);
    }
}
