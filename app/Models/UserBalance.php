<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBalance extends Model
{
    use SoftDeletes;

    protected $table = 'user_balance';

    protected $fillable = ['user_id', 'amount', 'operate_type', 'source', 'remark', 'read_flg'];
}
