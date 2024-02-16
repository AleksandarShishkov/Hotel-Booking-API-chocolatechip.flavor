<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;

class BookingsTest extends TestCase
{
    protected $booking;
    protected $bookings;
    protected $content;

    public function test_index_method() {
        try {
    
            $this->response = $this->get('api/api_tests/bookings/test');
    
            $this->bookings = Booking::orderBy('id')
                ->with('room')
                ->with('customer')
                ->with('payments')
                ->get()
                ->toArray();
    
                
            $this->response->assertStatus(200);
            
            if (count($this->bookings) > 0) {    
                $this->response->assertJson([
                    'bookings' => $this->bookings
                ]);
            } else {
                $this->response->assertJson([
                    'bookings' => 'No bookings were made!'
                ]);
            }
        } catch (\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }


    public function test_store_method() {
        
        try {   
            $this->booking = [
                'room_id' => 1,
                'customer_id' => 1,
                'check_in_date' => '2024-05-14',
                'check_out_date' => '2024-06-19',
                'total_price' => 100.00
            ];
           
    
            $this->response = $this->postJson('api/api_tests/bookings/test/create', $this->booking);

            $this->response->assertStatus(200);

            $this->content = json_decode($this->response->getContent(), true);
            $this->response->assertJson($this->content);
    
            $this->assertDatabaseHas('bookings', [
                'room_id' => 1,
                'customer_id' => 1,
                'check_in_date' => '2024-05-14',
                'check_out_date' => '2024-06-19',
                'total_price' => 100.00
            ]);

        } catch(\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }
}
