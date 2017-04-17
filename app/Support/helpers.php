<?php

if (!function_exists('is_mobile')) {
    /**
     * 粗略判断是否移动端浏览器
     *
     * @return bool
     */
    function is_mobile()
    {
        $regex = '/(iPhone|iPod|iPad|Android|BlackBerry|mobile|MicroMessenger)/';
        return preg_match($regex, $_SERVER['HTTP_USER_AGENT'])?true:false;
    }
}

if (!function_exists('is_wechat')) {
    /**
     * 判断是否微信浏览器
     *
     * @return bool
     */
    function is_wechat()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }
}

if (!function_exists('thumb_uri')) {
    /**
     * 根据原图 uri 获取缩略图 uri
     * 
     * @param $uri
     * @param $prefix
     * @return string
     */
    function thumb_uri($uri, $prefix)
    {
        $uriParts = explode('/', $uri);
        $partsCount = count($uriParts);
        if($partsCount) {
            $uriParts[$partsCount - 1] = $prefix . $uriParts[$partsCount - 1];
        }
        $thumbUri = implode('/', $uriParts);
        return $thumbUri;
    }
}

if (!function_exists('get_month_days')) {
    /**
     * 根据 年月 获取当月的具体天
     *
     * @param string $year_month
     * @return array
     */
    function get_month_days($year_month='')
    {
        $year_month = $year_month ? : date('Y-m');
        $times = strtotime($year_month);

        $day_arr = [];
        $days=date('t',$times);
        for($i=1;$i<=$days;$i++){
            if($i<10){
                $i = '0'.$i;
            }
            $day_arr[] = date('Y-m-',$times).$i;
        }
        return $day_arr;
    }

}


if(!function_exists('get_area_name')) {
    /**
     * 根据 省市区id 获取相应的名称
     *
     * @param $area_id
     * @return mixed
     */
    function get_area_name($area_id){
        return App\Models\Area::where('area_id',$area_id)->pluck('area_name');
    }
}

if(!function_exists('get_platform_current_amount')) {
    /**
     * 获取平台当前总金额
     *
     */
    function get_platform_current_amount(){
        return App\Models\Income::orderBy('id','desc')->limit(1)->pluck('total_amount');
    }
}

if(!function_exists('get_province')) {
    /**
     * 获取所有省
     *
     */
    function get_province(){
        return App\Models\Area::where('area_deep',1)->lists('area_name','area_id')->toArray();
    }
}


if(!function_exists('select_city')) {
    /**
     * @param $p_id
     * @param int $c_id  选中的市区
     */
    function select_city($p_id,$c_id=0)
    {
        $select_city = '<option value="">选择市</option>';
        $data = [];
        if($p_id > 0){
            $data = App\Models\Area::whereAreaParentId($p_id)->lists('area_name','area_id')->toArray();
        }

        if($data){
            foreach($data as $k=>$v){
                $select_city .='<option value="'.$k.'" '.($c_id && $c_id == $k ? 'selected':'').' >'.$v.'</option>';
            }
        }
        echo $select_city;
    }
}


function download_by_path($path_name, $save_name){
    ob_end_clean();
    $hfile = fopen($path_name, "rb") or die("Can not find file: $path_name\n");
    Header("Content-type: application/octet-stream");
    Header("Content-Transfer-Encoding: binary");
    Header("Accept-Ranges: bytes");
    Header("Content-Length: ".filesize($path_name));
    Header("Content-Disposition: attachment; filename=\"$save_name\"");
    while (!feof($hfile)) {
        echo fread($hfile, 32768);
    }
    fclose($hfile);
}

if (!function_exists('front_url')) {
    /**
     * 获取前台地址
     *
     * @param $uri
     * @param $host
     * @return string
     */
    function front_url($uri, $host = NULL)
    {
        return $host ? $host : config('constants.front_url') . $uri;
    }
}

if (!function_exists('send_sms')) {
    /**
     * @param $mobiles
     * @param $content
     * @return int
     *
     * 发送手机短信 返回0表示正常
     */
    function send_sms($mobiles, $content) {
        require_once(app_path('Library/SmsClient.php'));
        $client = new \SmsClient(config('sms.gwUrl'), config('sms.serialNumber'), config('sms.password'), config('sms.sessionKey'));
        $res = $client->sendSMS($mobiles, $content);
        return $res;
    }
}

if (!function_exists('get_sms_balance')) {
    /**
     * @return float
     */
    function get_sms_balance() {
        require_once(app_path('Library/SmsClient.php'));
        $client = new \SmsClient(config('sms.gwUrl'), config('sms.serialNumber'), config('sms.password'), config('sms.sessionKey'));
        $res = $client->getBalance();
        return $res;
    }
}

if(!function_exists('qiniu_previews')) {
    /**
     * @param $file
     * @return array|bool
     *
     * 获取七牛文件经过 yifangyun_preview 的预览图
     */
    function qiniu_previews($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $prefix = substr($file, 0, -(strlen($extension) + 1)).'.';

        $auth = new \Qiniu\Auth(config('qiniu.AK'), config('qiniu.SK'));
        $bucketMgr = new \Qiniu\Storage\BucketManager($auth);

        $bucket = config('qiniu.BUCKET_NAME_FILE');

        $marker = '';
        $limit = 1000;

        list($items, $marker, $err) = $bucketMgr->listFiles($bucket, $prefix, $marker, $limit);
        if ($err !== null) {
            return false;
        } else {
            $fileList = [];
            if(!empty($items)) {
                $count = count($items);
                for ($i=1; $i<= $count-1;++$i) {
                    $fileList[] = $prefix.$i.'.jpg';
                }
            }
            return $fileList;
        }
    }
}