<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'user';

    public function c_province()
    {
        return $this->belongsTo('App\Models\Area','province','area_id');
    }

    public function c_city()
    {
        return $this->belongsTo('App\Models\Area','city','area_id');
    }

    public function partner_city()
    {
        return $this->belongsTo('App\Models\Area','partner_city','area_id');
    }

    public function user_balance()
    {
        return $this->hasMany('App\Models\UserBalance','user_id');
    }

    public function user_point()
    {
        return $this->hasMany('App\Models\UserPoint','user_id');
    }
    
    public function lover()
    {
    	return $this->belongsTo('App\Models\User','lover_id');
    }
}
