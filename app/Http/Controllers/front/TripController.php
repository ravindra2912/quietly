<?php

namespace App\Http\Controllers\front;

use App\Models\Car;
use App\Models\Route;
use App\Models\RouteAddressPoint;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController
{
    public function oneWayRoute(): View
    {
        $cars = Car::select('id', 'name', 'details', 'image')->where('status', 'active')->get();
        $routes = Route::select('id', 'pickup_id', 'drop_id', 'KM', 'slug')
            ->with([
                'pickup_address:id,address',
                'drop_address:id,address',
                'cars' => function ($q) {
                    $q->select('id', 'route_id', 'price')
                        ->orderBy('price', 'asc')
                        ->take(1);
                },
            ])
            ->get();
        return view('front.oneway.routeList', compact('routes', 'cars'));
    }

    public function oneWayCarList(Request $request, $slug)
    {

        $route = Route::select('id', 'pickup_id', 'drop_id', 'KM', 'slug')
            ->with([
                'pickup_address' => function($q){
                    $q->with(['state:id,name', 'city:id,name']);
                },
                'drop_address:id,address',
                'cars' => function ($q) {
                    $q->select('id', 'route_id', 'car_id', 'price')
                        ->with(['carDetail:id,name,details,image,seating_capacity,Luggage,ac']);
                },
            ])
            ->where('slug', $slug)
            ->first();
                
            // dd($route->pickup_address?->city?->name);

            $allRoutes = Route::with(['pickup_address:id,address', 'drop_address:id,address'])
            ->where('status', 'active')
            ->get() // not all()
            ->groupBy(function ($route) {
                return $route->pickup_address->address ?? 'Unknown';
            });
        // dd($allRoutes);

        return view('front.oneway.CarList', compact('route', 'allRoutes'));
    }

    public function roundTrip(): View
    {
        $cars = Car::select('id', 'name', 'details', 'image', 'price_per_km', 'seating_capacity', 'Luggage', 'ac', 'km_per_day', 'is_include_vehicle_and_fuel_charge', 'is_include_toll_and_parking_charge')
            ->where('status', 'active')
            ->get();

        return view('front.roundtrip.CarList', compact('cars'));
    }

    function localTrip()
    {
        $cars = Car::select('id', 'name', 'details', 'image', 'local_trip_extra_price_per_km', 'local_trip_extra_price_per_hour')
            ->with(['localpackeges:id,car_id,details,price'])
            ->where('status', 'active')
            ->get();

        return view('front.localtrip.CarList', compact('cars'));
    }

    function airportTrip()
    {
        $cars = Car::select('id', 'name', 'details', 'image', 'airport_trip_price', 'seating_capacity', 'Luggage', 'ac')
            ->where('status', 'active')
            ->get();

        return view('front.airporttrip.CarList', compact('cars'));
    }
}
