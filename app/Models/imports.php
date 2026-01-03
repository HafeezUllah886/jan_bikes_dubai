<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class imports extends Model
{
    protected $guarded = [];

    public function import_cars()
    {
        return $this->hasMany(import_cars::class);
    }

    public function import_parts()
    {
        return $this->hasMany(import_parts::class);
    }
}
