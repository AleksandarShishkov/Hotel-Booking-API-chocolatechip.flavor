<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Room;


class RoomsTest extends TestCase
{

    protected $rooms;
    protected $response;
    protected $content;

    public function test_index_method() {

        try {    
            $this->response = $this->get('api/api_tests/rooms/test');
            
            $this->rooms = Room::orderBy('id')
                                ->with('bookings.customer')
                                ->with('bookings.payments')
                                ->get()
                                ->toArray();
            
            $this->response->assertStatus(200);
            
            if (count($this->rooms) > 0) {                        
                $this->response->assertJson([
                    'all rooms' => $this->rooms
                ]);
            } else {                    
                $this->response->assertJson([
                    'message' => 'No rooms to display'
                ]);
            }

        } catch(\Exeption $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }   


    public function test_store_method() {
        
        try {

            $this->room = [
                'number' => 3,
                'type' => 'test',
                'price_per_night' => 12.3,
                'status' => 'test'
            ];
            
            $this->response = $this->postJson('api/api_tests/rooms/test/create', $this->room);
            
            $this->response->assertStatus(200);

            $this->content = json_decode($this->response->getContent(), true);
            $this->response->assertJson($this->content);
            
            $this->assertDatabaseHas('rooms', [
                'number' => 3,
                'type' => 'test',
                'price_per_night' => 12.3,
                'status' => 'test'
            ]);
        } catch(\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }
}
