<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cars()
    {
        return $this->hasMany(purchase_cars::class);
    }

    public function parts()
    {
        return $this->hasMany(purchase_parts::class);
    }

}
