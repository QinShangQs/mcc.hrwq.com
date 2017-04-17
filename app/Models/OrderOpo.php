<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderOpo extends Model
{
    use SoftDeletes;
    protected $table = 'order_opo';
    protected $fillable = ['service_url', 'bucket'];
}
