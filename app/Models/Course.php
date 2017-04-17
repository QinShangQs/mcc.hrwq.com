<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    protected $table = 'course';

    protected $guarded = ['id'];

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'promoter');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'city');
    }

    public function agency()
    {
        return $this->belongsTo('App\Models\Agency', 'agency_id');
    }

}
