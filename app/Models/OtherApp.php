<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherApp extends Model
{
    protected $fillable = ['image', 'name', 'status'];

    public function apiObject()
    {
        return [
            'id' => $this->id,
            'image' => getImage($this->image),
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
