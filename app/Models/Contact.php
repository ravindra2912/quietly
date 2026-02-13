<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'description',
        'type',
        'status',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the badge color for status
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get the badge color for type
     */
    public function getTypeBadgeColor(): string
    {
        return match($this->type) {
            'ReportAnIssue' => 'danger',
            'FeatureRequest' => 'primary',
            'GeneralHelp' => 'success',
            default => 'secondary',
        };
    }
}
