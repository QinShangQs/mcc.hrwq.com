<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WechatPush extends Model
{

    protected $table = 'wechat_push';
    protected $fillable = ['title', 'url', 'picurl', 'description','push_time','create_at','updated_at'];

}
