<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'is_ads',
        'ads_key',
    ];

    protected $casts = [
        'is_ads' => 'boolean',
    ];
}
