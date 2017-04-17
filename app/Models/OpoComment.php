<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpoComment extends Model 
{
    protected $table = 'opo_comment';
    use SoftDeletes;

    public function opo()
    {
        return $this->belongsTo('App\Models\Opo','opo_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

}
