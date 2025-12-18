<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountAdjustment extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class);
    }

    public function branch()
    {
        return $this->belongsTo(branches::class);
    }

}

  
