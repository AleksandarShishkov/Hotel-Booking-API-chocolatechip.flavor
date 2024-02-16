<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Room;


class RoomsController extends Controller
{

    protected $all_rooms;
    protected $room;
    protected $validator;
    protected $room_data;


    public function index() {
        $this->all_rooms = Room::orderBy('id')->with('bookings.customer')->with('bookings.payments')->get();
        
        if($this->all_rooms) {
            return response()->json([
                'all rooms' => $this->all_rooms
            ]);
        } 
       
        return response()->json([
            'message' => 'No rooms to display'
        ]);
    }


    public function store(Request $request) {
        $this->validator = Validator::make($request->all(), [
            "number" => "required|numeric",
            "type" => "required|string",
            "price_per_night" => "required|numeric",
            "status" => "required|string"
        ]);

        if($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()->first()
            ]);
        }

        if(Room::where('number', $request->number)->first()) {
            return response()->json([
                'error' => 'Room No:' . $request->number . ' is already in the database!'
            ]);
        }

        $this->room_data = [
            "number" => $request->number,
            "type" => $request->type,
            "price_per_night" => $request->price_per_night,
            "status" => $request->status
        ];

        if(DB::table('rooms')->insert($this->room_data)) {
            return response()->json([
                'The record has been created successfully!'
            ]);
        }

        return response()->json([
            'There was an issue. Check the database!'
        ]);
    }


    public function show($id) {
        $this->room = Room::where('id', $id)->with('bookings.customer')->with('bookings.payments')->get();

        if($this->room) {
            return response()->json([
                'room info' => $this->room
            ]);
        }

        return response()->json([
            'error' => 'No such room located'
        ]);
    }
}
