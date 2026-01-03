<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class imports extends Model
{
    protected $guarded = [];

    public function cars()
    {
        return $this->hasMany(import_cars::class, 'import_id', 'id');
    }

    public function parts()
    {
        return $this->hasMany(import_parts::class, 'import_id', 'id');
    }
}
