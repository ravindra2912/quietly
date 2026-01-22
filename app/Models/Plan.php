<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'price_in_dollar',
        'duration_in_month',
        'is_ad_free',
        'group_active_timing',
        'is_active_multiple_group',
        'plan_purchase_limit_per_user',
        'plan_purchase_limit',
        'status',
    ];

    protected $casts = [
        'is_ad_free' => 'boolean',
        'is_active_multiple_group' => 'boolean',
        'price' => 'decimal:2',
        'price_in_dollar' => 'decimal:2',
    ];

    // Relationships
    public function planPurchases()
    {
        return $this->hasMany(PlanPurchase::class);
    }

    public function apiObject()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'price_in_dollar' => $this->price_in_dollar,
            'duration_in_month' => $this->duration_in_month,
            'is_ad_free' => $this->is_ad_free,
            'group_active_timing' => $this->group_active_timing,
            'is_active_multiple_group' => $this->is_active_multiple_group,
            'plan_purchase_limit_per_user' => $this->plan_purchase_limit_per_user,
            'plan_purchase_limit' => $this->plan_purchase_limit,
            'status' => $this->status,
        ];
    }
}
