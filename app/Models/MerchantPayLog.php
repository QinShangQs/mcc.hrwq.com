<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantPayLog extends Model
{
    protected $table = 'merchant_pay_log';

    protected $guarded = ['id'];
}
