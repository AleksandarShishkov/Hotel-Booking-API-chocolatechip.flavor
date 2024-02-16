<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Booking;

class PaymentsController extends Controller
{
    protected $validator;
    protected $payment_data;
    

    public function store(Request $request)
    {
        $this->validator = Validator::make($request->all(), [
            'booking_id' => 'required|numeric|exists:bookings,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'required|string'
        ]);

        if($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()->first()
            ]);
        }

        if(!Booking::find($request->booking_id)) {
            return response()->json([
                'error' => 'No such booking in the database!'
            ]);
        }

        $this->payment_data = [
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'payment_date' => date('Y-m-d', strtotime($request->payment_date)),
            'status' => $request->status
        ];

        if(DB::table('payments')->insert($this->payment_data)) {
            return response()->json([
                'The record has been created successfully!'
            ]);
        }

        return response()->json([
            'There was an issue. Check the database connection!'
        ]);        
    }
}
