<?php

namespace Tests\Unit;

use Tests\TestCase;


class PaymentsTest extends TestCase
{
    protected $payment;   
    protected $response;
    protected $content;


    public function test_store_method() {

        try {
            $this->payment = [
                'booking_id' => 1,
                'amount' => 50.04,
                'payment_date' => '2024-02-03',
                'status' => 'processing'
            ];

            $this->response = $this->postJson('api/api_tests/payments/test/create', $this->payment);

            $this->response->assertStatus(200);
            
            $this->content = json_decode($this->response->getContent(), true);
            
            $this->response->assertJson($this->content);
    
            $this->assertDatabaseHas('payments', [
                'booking_id' => 1,
                'amount' => 50.04,
                'payment_date' => '2024-02-03',
                'status' => 'processing'
            ]);

        } catch(\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }
}
