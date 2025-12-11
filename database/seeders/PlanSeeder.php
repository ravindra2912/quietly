<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'price' => 29.99,
                'duration_in_month' => 3,
                'is_ad_free' => false,
                'group_active_timing' => '1',
                'is_active_multiple_group' => false,
                'plan_purchase_limit_per_user' => 'unlimit',
                'plan_purchase_limit' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Standard Plan',
                'price' => 59.99,
                'duration_in_month' => 6,
                'is_ad_free' => true,
                'group_active_timing' => '12',
                'is_active_multiple_group' => true,
                'plan_purchase_limit_per_user' => 'limited',
                'plan_purchase_limit' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Premium Plan',
                'price' => 199.99,
                'duration_in_month' => 12,
                'is_ad_free' => true,
                'group_active_timing' => '24',
                'is_active_multiple_group' => true,
                'plan_purchase_limit_per_user' => 'unlimit',
                'plan_purchase_limit' => null,
                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
