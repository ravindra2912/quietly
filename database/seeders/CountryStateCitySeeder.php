<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountryStateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'state' => 'Gujarat',
                'cities' => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 'Junagadh', 'Gandhinagar', 'Bharuch', 'Navsari']
            ],
            [
                'state' => 'maharashtra',
                'cities' => ['Mumbai', 'Pune', 'Nagpur', 'Aurangabad', 'Kolhapur', 'Nashik', 'Solapur', 'Nanded', 'Latur', 'Ahmednagar']
            ],
            [
                'state' => 'rajasthan',
                'cities' => ['Jaipur', 'Jodhpur', 'Ajmer', 'Udaipur', 'Bikaner', 'Jaisalmer', 'Bharatpur', 'Alwar', 'Nagaur', 'Tonk']
            ]
        ];

        foreach ($data as $state) {
            $statedetail = State::create(['name' => $state['state']]);
            foreach ($state['cities'] as $city) {
                $city = City::create(['name' => $city, 'state_id' => $statedetail->id]);
            }
        }
    }
}
