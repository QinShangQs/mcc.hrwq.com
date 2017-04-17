<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTutorApply extends Model
{
    use SoftDeletes;

    protected $table = 'user_tutor_apply';

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

}
