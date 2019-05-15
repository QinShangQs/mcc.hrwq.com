<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VipTv extends Model {

    protected $table = 'vip_tv';

    use SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User', 'activated_vip');
    }

}
