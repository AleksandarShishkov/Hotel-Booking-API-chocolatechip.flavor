# Hotel-Booking-API
API system for managing rooms/bookings/customers/payments

# Description:
This project provides a simple API for managing a Hotel Booking System using Laravel. 
The system allows for the management of hotel rooms, bookings, customer interactions, and payments. 
It is designed to streamline the room booking process, manage customer details, and handle payments securely.

# Setup Instructions:
Ensure to migrate the database schema by executing the following command:
  - php artisan migrate

Populate the database with default records using the following command:
  - php artisan db:seed

Run the command below to initialize the client for Laravel Passport, enabling secure authentication:
  - php artisan passport:install

Following the setup steps, you can utilize Postman to test the API functionality thoroughly.
These steps ensure a robust setup and smooth operation of the Hotel Booking System API, allowing for seamless management of rooms, bookings, customers, and payments.

# Models and relationships:
The models are devided into four groups - Room, Booking, Customer, and Payment.
- A Room can have many Bookings.
- A Booking belongs to one Room and one Customer.
- A Customer can have many Bookings.
- A Booking can have many Payments.

# Attributes:
Room: number, type, price_per_night, status
Booking: room_id, customer_id, check_in_date, check_out_date, total_price
Customer: name, email, phone_number
Payment: booking_id, amount, payment_date, status

# Authentication and Authorization
For the purpose of the application a token-based authentication was implemented through Laravel Passport.
In this way only authenticated upon login users can:
- Add rooms
- Create bookings
- Add customers
- Record payments

All input data is validated and a proper error handling for bad requests and unauthorized access is applied.

# As additional features, the application has:
- Notification system to alert hotel staff of new bookings or cancellations through Events and Listeners.
- Room availability checker to prevent double bookings.
- Unit tests that ensures that the API functionality works as expected.

# API Documentation
This document provides an overview of the available RESTFull API endpoints for managing a Hotel Booking System. 

- Endpoints
  - Customers
      - Login
          Cutomer login
          URL: /api_logins/customers/login
          Method: POST
          Description: Manages the login functionality of the applcation
          Request Body:
            'email' (string): The email of the customer,
            'password' (string): The password of the customer 
          Authentication Required: No  

      - Retrieve All Customers
          URL: /api_customers/customers
          Method: GET
          Description: Retrieves all customers registered in the system.
          Request Body: None
          Authentication Required: Yes

      - Create a Customer
          URL: /api_customers/customers/create
          Method: POST
          Description: Creates a new customer.
          Request Body:
            'name' (string): Name of the customer.
            'email' (string): Email address of the customer.
            'phone_number' (string): Phone number of the customer.
            'password' (string): Password for the customer account.
          Authentication Required: Yes

  - Rooms
      - Retrieve All Rooms
          URL: /api_rooms/rooms
          Method: GET
          Description: Retrieves all available rooms.
          Request Body: None
          Authentication Required: Yes

      - Create a Room
          URL: /api_rooms/rooms/create
          Method: POST
          Description: Creates a new room.
          Request Body:
            'number' (string): Room number.
            'type' (string): Type of the room.
            'price_per_night' (numeric): Price per night for the room.
            'status' (string): Status of the room.
           Authentication Required: Yes

      - Show Room Details
          URL: /api_rooms/rooms/{id}/show
          Method: GET
          Description: Retrieves details of a specific room.
          Request Parameters:
            'id' (integer): ID of the room to retrieve details for.
          Authentication Required: Yes

   - Bookings
        - Retrieve All Bookings
            URL: /api_bookings/bookings
            Method: GET
            Description: Retrieves all bookings.
            Request Body: None
            Authentication Required: Yes

        - Create a Booking
            URL: /api_bookings/bookings/create
            Method: POST
            Description: Creates a new booking.
            Request Body:
              'room_id' (integer): ID of the room for the booking.
              'customer_id' (integer): ID of the customer for the booking.
              'check_in_date' (date): Check-in date for the booking.
              'check_out_date' (date): Check-out date for the booking.
              'total_price' (numeric): Total price for the booking.
            Authentication Required: Yes

    - Payments
        - Record Payment for a Booking
            URL: /api_payments/payments/create
            Method: POST
            Description: Records a payment for a booking.
            Request Body:
              'booking_id' (integer): ID of the booking for the payment.
              'amount' (numeric): Amount of the payment.
              'payment_date' (date): Date of the payment.
              'status' (string): Status of the payment.
            Authentication Required: Yes

- Test Routes
    Test routes are provided for testing purposes without authentication.
    Please refer to the specific endpoint descriptions for detailed information on request and response formats.
