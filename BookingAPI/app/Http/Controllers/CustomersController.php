<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    protected $validator;
    protected $token;
    protected $customer;
    protected $customers;
    protected $customer_data;
    protected $credentials;

    
    public function login(Request $request) {
        $this->validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()->first()
            ]);
        }
        
        $this->customer = Customer::where('email', $request->email)->first();

        if($this->customer && Hash::check($request->password, $this->customer->password)) {

            $this->token = $this->customer->createToken('BookingApi')->accessToken;
            return response()->json([
                'success' => 'You have logged successfully',
                'your access token' => $this->token
            ]);
        }

        $this->credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        return response()->json([
            'error' => 'Invalid credentials',
            'auth_attempt_result' => Auth::attempt($this->credentials)
        ]);
    }


    public function store(Request $request) {
        $this->validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:customers,email',
            'phone_number' => 'required',
            'password' => 'required'

        ]);

        if($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()->first()
            ]);
        }

        $this->customer_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ];

        if(DB::table('customers')->insert($this->customer_data)) {
            return response()->json([
                'The record has been created successfully!'
            ]);
        }

        return response()->json([
            'There was an issue. Check the database!'
        ]);
    }


    public function index() {
        $this->customers = Customer::orderBy('id')
                                        ->with('bookings.room')
                                        ->with('bookings.payments')
                                        ->get();

        if($this->customers) {
            return response()->json([
                'all customers' => $this->customers
            ]);
        }

        return response()->json([
            'No customers added to the database!'
        ]);
    }
}
