<?php

namespace App\Http\Controllers\front;

use App\Models\Booking;
use App\Models\RouteCar;
use App\Mail\NewBookingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingController
{
    public function book(Request $request)
    {
        $success = false;
        $message = 'Something Wrong!';
        $redirect = Route('admin.managebooking.index');
        $data = array();

        try {
            $rules = [
                'type' => 'required',
                'customer_name' => 'required',
                'customer_contact' => 'required|numeric|digits:10',
                'pickup_date' => 'required|date|after_or_equal:today',
                'pickup_time' => 'required|date_format:H:i'
            ];

            if ($request->type == 'onewayTrip') {
                $rules['route_id'] = 'required|exists:routes,id';
                $rules['route_vehicle_id'] = 'required|exists:route_cars,id';
            } else if ($request->type == 'roundTrip') {
                $rules['pickup_location'] = 'required';
                $rules['drop_location'] = 'required';
                $rules['vehicle_id'] = 'required|exists:cars,id';
                $rules['km'] = 'required|numeric|min:1';
            } else if ($request->type == 'localTrip') {
                $rules['pickup_location'] = 'required';
                $rules['drop_location'] = 'required';
                $rules['vehicle_id'] = 'required|exists:cars,id';
                $rules['local_trip_package_id'] = 'required|exists:local_trip_packages,id';
            } else if ($request->type == 'airportTrip') {
                $rules['pickup_location'] = 'required';
                $rules['drop_location'] = 'required';
                $rules['vehicle_id'] = 'required|exists:cars,id';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { // Validation fails
                $message = $validator->errors();
                // $message = $validator->errors()->first();
            } else {

                $insert = new Booking();
                $insert->type = $request->type;
                $insert->customer_name = $request->customer_name;
                $insert->customer_contact = $request->customer_contact;
                $insert->pickup_date = $request->pickup_date;
                $insert->pickup_time = $request->pickup_time;
                if ($request->type == 'onewayTrip') {
                    $getPrice = RouteCar::find($request->route_vehicle_id);
                    $insert->total = $getPrice->price;
                    $insert->route_id = $request->route_id;
                    $insert->route_vehicle_id = $request->route_vehicle_id;
                } else if ($request->type == 'roundTrip') {
                    $insert->pickup_location = $request->pickup_location;
                    $insert->drop_location = $request->drop_location;
                    $insert->vehicle_id = $request->vehicle_id;
                    $insert->km = $request->km;
                    $insert->total = $request->price_per_km * $request->km;
                } else if ($request->type == 'localTrip') {
                    $insert->pickup_location = $request->pickup_location;
                    $insert->drop_location = $request->drop_location;
                    $insert->vehicle_id = $request->vehicle_id;
                    $insert->local_trip_package_id = $request->local_trip_package_id;
                } else if ($request->type == 'airportTrip') {
                    $insert->pickup_location = $request->pickup_location;
                    $insert->drop_location = $request->drop_location;
                    $insert->vehicle_id = $request->vehicle_id;
                    $insert->total = $request->total_fare;
                }
                $insert->save();

                Mail::to(config('const.admin_notify_emails'))->send(new NewBookingMail($insert));

                $success = true;
                $data = $insert;
                $message = 'Booking add successfully.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['success' => $success, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
    }
}
