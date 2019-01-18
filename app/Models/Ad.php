<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model {

    protected $table = 'ad';

    use SoftDeletes;
    
    protected $fillable = ['title', 'ad_type', 'show_type', 'display_url', 'video_original','redirect_url'];

    /**
     * 广告类型-图片
     */
    const AD_TYPE_IMAGE = 1;

    /**
     * 广告类型-视频
     */
    const AD_THYP_VIDEO = 2;

    /**
     * 是否显示-是
     */
    const SHOW_TYPE_YES = 1;

    /**
     * 是否显示-否
     */
    const SHOW_TYPE_NO = 2;
    const AD_TYPES = [
        self::AD_TYPE_IMAGE => '暂停广告',
        self::AD_THYP_VIDEO => '前置广告'
    ];

}
