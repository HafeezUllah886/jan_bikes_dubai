<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitDistribution extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ProfitDistributionDetail::class, 'refID', 'id');
    }
}

class ProfitDistributionDetail extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'accountID', 'id');
    }
}
