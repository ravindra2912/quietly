<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'duration_in_month',
        'start_at',
        'expired_at',
        'plan_info',
        'price',
        'payment_details',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'expired_at' => 'datetime',
        'plan_info' => 'array',
        'payment_details' => 'array',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && $this->expired_at > now();
    }

    public function isExpired()
    {
        return $this->expired_at <= now();
    }

    public function apiObject()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'plan_name' => $this->plan_info['name'] ?? null,
            'duration_in_month' => $this->duration_in_month,
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'expired_at' => $this->expired_at->format('Y-m-d H:i:s'),
            'price' => $this->price,
            'status' => $this->status,
            'is_active' => $this->isActive(),
            'is_expired' => $this->isExpired(),
        ];
    }
}
