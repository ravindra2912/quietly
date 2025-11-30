<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'name' => 'Rahul Mehta',
                'review' => 'Great experience! The cab arrived on time and the driver was very polite.'
            ],
            [
                'name' => 'Anjali Sharma',
                'review' => 'Easy booking process and clean car. I use this app almost every day.'
            ],
            [
                'name' => 'Arjun Patel',
                'review' => 'Affordable fares compared to others. The fare estimate shown was accurate.'
            ],
            [
                'name' => 'Sneha Reddy',
                'review' => 'The driver was professional and knew the best routes. Highly recommended!'
            ],
            [
                'name' => 'Mohit Verma',
                'review' => 'Booking was quick and I liked the option to schedule my ride in advance.'
            ],
            [
                'name' => 'Priya Nair',
                'review' => 'I felt very safe during my ride. The live tracking feature is really helpful.'
            ],
            [
                'name' => 'Karan Singh',
                'review' => 'The cab was neat and comfortable. Payment with UPI was smooth and fast.'
            ],
            [
                'name' => 'Neha Gupta',
                'review' => 'Even in rush hour I was able to get a ride quickly. Very reliable service.'
            ],
            [
                'name' => 'Rohit Malhotra',
                'review' => 'Lost my wallet in the cab but support helped me get it back the same day!'
            ],
            [
                'name' => 'Divya Joshi',
                'review' => 'Loved the ride-sharing option. Saved money and met new people on the way.'
            ],
        ];

        foreach ($data as $faq) {
            Review::create($faq);
        }
    }
}
