<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatTemplateTask extends Model {

    protected $table = 'wechat_template_task';
    protected $fillable = ['wechat_appid', 'template_id', 'template_name', 'url', 'topcolor', 'content', 'remark'
        , 'user_type', 'task_type', 'task_status', 'task_run_time', 'finish_time','openid'
        , 'cnts', 'send_total_num', 'send_success_num', 'send_fail_num','created_at','deleted_at','updated_at'];

    const TASK_TYPE_ONLYONE = 1;
    const TASK_TYPE_EVERYDAY = 2;
    const TASK_STATUS_WAITING = 1;
    const TASK_STATUS_RUNNING = 2;
    const TASK_STATUS_STOPED = 3;
    const TASK_STATUS_FINISHED = 4;
    
    const USER_TYPE_ALL = 'all';//全部粉丝
    const USER_TYPE_VIP = 'vip';//和会员
    
}
