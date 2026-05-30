<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class extra_profit extends Model
{
    use HasFactory;

    protected $table = 'extra_profit';

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'accountID');
    }

    public function category()
    {
        return $this->belongsTo(profitCategories::class, 'cat');
    }
}
