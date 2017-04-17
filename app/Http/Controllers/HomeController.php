<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Agency;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        //开始年份 ，当前年份 计算 允许检索的年份
        $begin_year = 2016;
        //当前年份
        $cur_year = date('Y');

        $limit_num = $cur_year - $begin_year;
        $limit_years = [];
        for($j=0;$j<=$limit_num;$j++)
        {
            $limit_years[] = $cur_year - $j;
        }

        //当前月份
        $cur_month = date('m');

        //当天
        $cur_day = date('Y-m-d');

        $cur_month = (int)($cur_month);
        //省份和城市
        $areaPs = Area::select(['area_id', 'area_name'])->where('area_deep', '=', 1)->get();

        $arrareaCs2 = array();
        if ($areaPs) {
            $arrareaCs = $areaPs->toArray();
            foreach ($arrareaCs as &$value) {
                $areaCs = Area::select('area_id', 'area_name')->where('area_parent_id', $value['area_id'])->get();
                $arrareaC = array();
                if ($areaCs) {
                    $arrareaC = $areaCs->toArray();
                }
                $arrareaCs2[$value['area_id']] = $arrareaC;
            }
        }
        $arrareaCs = json_encode($arrareaCs2);

        return view('welcome',/**/[
            'cur_year' => $cur_year,
            'cur_month' => (int)($cur_month),
            'limit_years'=> $limit_years,
            'cur_day'=> $cur_day,
            'areaPs' => $areaPs,
            'arrareaCs' => $arrareaCs,
        ]);
    }

    //新增用户统计
    public function stat_user(Request $request)
    {
        $select_s_year  = $request->input('select_s_year');
        $select_s_month = $request->input('select_s_month');
        $select_s_province = $request->input('select_s_province');
        $select_s_city = $request->input('select_s_city');

        //月份为0,按年份搜索
        if($select_s_month == 0){
            $this->_stat_user_by_year($select_s_year, $select_s_province, $select_s_city);
        }
        $this->_stat_user_by_month($select_s_year.'-'.$select_s_month, $select_s_province, $select_s_city);
    }

    //新增订单统计
    public function stat_order(Request $request)
    {
        $stat_order_type  = $request->input('stat_order_type');
        $s_time  = $request->input('s_time');
        $e_time  = $request->input('e_time');
        $province = $request->input('order_province');
        $city = $request->input('order_city');

        //1大类  2小类
        if($stat_order_type == 1)
        {
            $this->_stat_order_by_parent($s_time, $e_time, $province, $city);
        }
        $this->_stat_order_by_child($s_time,$e_time, $province, $city);
    }


    private function _stat_order_by_child($s_time, $e_time, $province, $city)
    {
        $barData = $tick = $message = [];

        $builder = Order::withTrashed()->select('pay_type','agency_id',DB::raw('COUNT(id) as num'))->where('order_type', '!=', 3);
        if ($s_time) {
            $builder->where('created_at','>=', $s_time. ' 00:00:00');
        }
        if ($e_time) {
            $builder->where('created_at','<=', $e_time.' 23:59:59');
        }
        if($province){
            $builder->whereHas('user', function ($query) use ($province, $city){
                $query->where('province', $province);
                if($city)
                    $query->where('city', $city);
            });
        }
        $data = $builder->groupBy('pay_type','agency_id')->get()->toArray();

        $num_arr =[];
        if($data){
            foreach($data as $k=>$v){
                $num_arr[$v['pay_type'].'-'.$v['agency_id']] = $v['num'];
            }
        }

        //x轴的 维度 数据
        $agency = Agency::lists('agency_name','id')->toArray();
        $order_belong_category = config('constants.order_belong_category');

        $x_arr = [];
        foreach($order_belong_category as $k=>$v)
        {
            if($k==1 || $k==2)
            {
                foreach($agency as $kk=>$vv)
                {
                    $x_arr[$k.'-'.$kk] = $v.'-'.$vv;
                }
            }else{
                $x_arr[$k.'-0'] = $v;
            }
        }

        $i = 0;
        foreach($x_arr as $k=>$v)
        {
            $barData[$i]['data'] = [[$i+1, isset($num_arr[$k]) ? $num_arr[$k] : 0 ]];
            $barData[$i]['bars'] = [
                'show' => true
            ];
            $tick[$i] = [$i+1.5,$v];
            $i++;
        }

        $message['code'] = 0;
        $message['content'] = $barData;
        $message['tick'] = $tick;
        die(json_encode($message, JSON_UNESCAPED_UNICODE));
    }

    private function _stat_order_by_parent($s_time, $e_time, $province, $city)
    {
        $barData = $tick = $message = [];
        DB::enableQueryLog();
        //类型->数量  键值对
        $builder = Order::withTrashed()->select('pay_type',DB::raw('COUNT(id) as num'))->where(function($query){
                $query->where(function($query){
                    $query->where('order_type', '!=', 3)->where('pay_type', '!=', 4)->where('pay_type', '!=', 5);
                })->orWhere(function ($query) {
                    $query->orWhere(function($query){
                        $query->orWhere('pay_type', '=', 4)->orWhere('pay_type', '=', 5);
                    })->where('order_type', '=', 2 );
                });
            });
        if ($s_time) {
            $builder->where('created_at','>=', $s_time. ' 00:00:00');
        }
        if ($e_time) {
            $builder->where('created_at','<=', $e_time.' 23:59:59');
        }
        if($province){
            $builder->whereHas('user', function ($query) use ($province, $city){
                $query->where('province', $province);
                if($city)
                    $query->where('city', $city);
            });
        }
        $data = $builder->groupBy('pay_type')->lists('num','pay_type')->toArray();

        $order_belong_category = config('constants.order_belong_category');

        foreach($order_belong_category as $k=>$v)
        {
            $i = $k-1;

            $barData[$i]['data'] = [[$k, isset($data[$k]) ? $data[$k] : 0 ]];
            $barData[$i]['url'] = route('coupon');
            $barData[$i]['bars'] = [
                'show' => true
            ];
            $tick[$i] = [$i+1.5,$v];
        }

        $message['code'] = 0;
        $message['content'] = $barData;
        $message['tick'] = $tick;
        die(json_encode($message, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 按年份统计用户
     *
     * @param $select_s_year
     */
    private function _stat_user_by_year($select_s_year, $select_s_province, $select_s_city)
    {
        $barData = $tick = $message = [];

        $day_num_arr = $this->_stat_year_user_add($select_s_year, $select_s_province, $select_s_city);

        for($i=0;$i<12;$i++)
        {
            $m = $i+1;
            if ($m <= 9) {
                $m = '0' . $m;
            }
            $i_month = $select_s_year . '-' . $m;

            $barData[0]['data'][] = [$i+1, isset($day_num_arr[$i_month]) ? $day_num_arr[$i_month] : 0 ];
            //todo 用户列表 链接待完善
            $tick[$i] = [$i+1, ($i+1).'月'];
        }

        $message['code'] = 0;
        $message['content'] = $barData;
        $message['tick'] = $tick;
        die(json_encode($message, JSON_UNESCAPED_UNICODE));
    }


    /**
     * 按月份统计用户
     *
     * @param $select_year_month
     */
    private function _stat_user_by_month($select_year_month, $select_s_province, $select_s_city)
    {
        $barData = $tick = $message = [];
        $day_arr = get_month_days($select_year_month);

        $day_num_arr = $this->_stat_month_user_add($day_arr, $select_s_province, $select_s_city);

        foreach($day_arr as $k=>$day)
        {
            $barData[0]['data'][] = [$k + 1, isset($day_num_arr[$day]) ? $day_num_arr[$day] : 0 ];
            //todo 用户列表 链接待完善
            $tick[$k] = [$k+1, date('n-j',strtotime($day))];
        }

        $message['code'] = 0;
        $message['content'] = $barData;
        $message['tick'] = $tick;
        die(json_encode($message, JSON_UNESCAPED_UNICODE));
    }


    private function _stat_year_user_add($year, $select_s_province, $select_s_city)
    {
        $day_num_arr = [];
        $query =  User::withTrashed()->whereBetween('created_at', [$year.'-01-01'. ' 00:00:00',$year.'-12-31'.' 23:59:59']);
        //添加省市判断
        if($select_s_province) {
            $query->where('province', $select_s_province);
            if($select_s_city)
                $query->where('city', $select_s_city);
        }
        $user_add = $query->lists('created_at')->toArray();

        foreach($user_add as $v)
        {
            $tmp = explode('-',$v);
            $day_key = $tmp[0].'-'.$tmp[1];

            $day_num_arr[$day_key] = isset($day_num_arr[$day_key]) ? $day_num_arr[$day_key]+1 :1;
        }

        return $day_num_arr;
    }


    /**
     * 统计当月所有注册用户 数组分组  日期=》注册次数
     *
     * @param $day_arr
     * @return array
     */
    private function _stat_month_user_add($day_arr, $select_s_province, $select_s_city)
    {
        $day_num_arr = [];
        $query =  User::withTrashed()->whereBetween('created_at', [$day_arr[0]. ' 00:00:00',end($day_arr).' 23:59:59']);

        if($select_s_province) {
            $query->where('province', $select_s_province);
            if($select_s_city)
                $query->where('city', $select_s_city);
        }
        $month_user_add = $query->lists('created_at')->toArray();
        foreach($month_user_add as $v)
        {
            $day_key = explode(' ',$v)[0];
            $day_num_arr[$day_key] = isset($day_num_arr[$day_key]) ? $day_num_arr[$day_key]+1 :1;
        }

        return $day_num_arr;
    }

}
