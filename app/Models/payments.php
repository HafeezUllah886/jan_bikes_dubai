<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'accountID');
    }

    public function toAccount()
    {
        return $this->belongsTo(accounts::class, 'toID');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
