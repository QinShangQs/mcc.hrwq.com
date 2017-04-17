<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalkComment extends Model
{
    use SoftDeletes;
    protected $table = 'talk_comment';

    public function answer_user()
    {
        return $this->belongsTo('App\Models\User','r_user_id');
    }

    public function talk()
    {
        return $this->belongsTo('App\Models\Talk','talk_id');
    }
}
