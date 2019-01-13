<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTeam extends Model {

    protected $table = 'order_team';
    protected $guarded = ['id'];

    use SoftDeletes;

    /**
     * 发起中
     */
    const STATUS_INIT = 0;

    /**
     * 组团成功
     */
    const STATUS_SUCCESS = 1;

    /**
     * 组团失败
     */
    const STATUS_FAILED = 2;
}
