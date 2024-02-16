<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;


class CustomersTest extends TestCase
{
    protected $customer;
    protected $created_customer;
    protected $customers;
    protected $response;
    protected $content;
    protected $login_data;


    public function test_login_method() {

        try {
            $this->login_data = [
                'email' => 'john@gmail.com',
                'password' => '123456'
            ];
    
            $this->response = $this->postJson('api/api_tests/customers/test/login', $this->login_data);
            
            $this->content = json_decode($this->response->getContent(), true);
            $this->response->assertStatus(200);

            $this->response->assertJson($this->content);

        } catch (\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }


    public function test_store_method() {
        
        try {   
            $this->customer = [
                'name' => 'TestName',
                'email' => 'testmail@mail.com',
                'phone_number' => '1234567890',
                'password' => '123456'
            ];
           
    
            $this->response = $this->postJson('api/api_tests/customers/test/create', $this->customer);

            $this->response->assertStatus(200);

            $this->content = json_decode($this->response->getContent(), true);
            $this->response->assertJson($this->content);
    
            $this->created_customer = [
                'name' => 'TestName',
                'email' => 'testmail@mail.com',
                'phone_number' => '1234567890'
            ];

            $this->assertDatabaseHas('customers', $this->created_customer);

        } catch(\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }


    public function test_index_method() {
        try {
            $this->response = $this->get('api/api_tests/customers/test');

            $this->customers = Customer::orderBy('id')
                                        ->with('bookings.room')
                                        ->with('bookings.payments')
                                        ->get()
                                        ->toArray();
                
            $this->response->assertStatus(200);

            if (count($this->customers) > 0) {                        
                $this->response->assertJson([
                    'all customers' => $this->customers
                ]);
            } else {                    
                $this->response->assertJson([
                    'No customers added to the database!'
                ]);
            }

        } catch(\Exception $e) {
            $this->fail('Error: ' . $e->getMessage());
        }
    }
}
