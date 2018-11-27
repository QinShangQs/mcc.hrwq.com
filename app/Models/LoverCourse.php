<?php

namespace App\Models;

class LoverCourse extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'lover_course';
    protected $fillable = ['user_id', 'lover_id', 'course_id','order_id', 'status'];

    public function lover()
    {
    	return $this->belongsTo('App\Models\User','lover_id');
    }
}
