<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vcourse
 *
 */
class Vcourse extends Model
{

    use SoftDeletes;
    protected $table = 'vcourse';
    public $timestamps = true;

    protected $fillable = ['agency_id', 'title', 'cover', 'type', 'price', 'current_class', 'total_class', 'work', 'suitable', 'teacher', 'teacher_intr', 'vcourse_obj', 'vcourse_des', 'status', 'video_original', 'video_tran', 'video_free', 'bucket', 'free_time', 'sort'];

    public function agency()
    {
        return $this->belongsTo('App\Models\Agency','agency_id');
    }

}
