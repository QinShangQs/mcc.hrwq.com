<?php
/**
 * 订单管理
 */
namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderCourse;
use App\Models\OrderOpo;
use App\Models\OrderVip;
use App\Models\User;
use App\Models\Area;
use App\Models\Express;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Excel;
use Qiniu\Auth as Qiniu_Auth;
use Qiniu\Storage\BucketManager;
use Wechat, Event, DB;
use App\Library\UploadFile;

class OrderController extends Controller
{
    /** 好课订单列表 */
    public function order_course(Request $request)
    {
        //关联模型
        $builder = Order::withTrashed()->with('course.area', 'order_course', 'user');
    	//获取所有省
		$province_list = Area::select('area_id', 'area_name')->where('area_deep', '=', 1)->get()->toArray();;
		//获取所有市
		$city_list2 = Area::select('area_id', 'area_name')->where('area_deep', '=', 2)->get()->toArray();;
        // 城市
        $areas = Area::select('area_id', 'area_name')->where('area_deep', '=', 2)->get();
        $arrArea = array();
        foreach ($areas as &$value) {
            $arrArea[$value['area_id']] = $value['area_name'];
        }
        //课程名称
        if ($order_name = trim($request->input('order_name'))) {
            $builder->where('order_name', 'like', '%'.$order_name.'%');
        }
        //课程名称
        if ($order_name = trim($request->input('order_name'))) {
            $builder->where('order_name', 'like', '%' . $order_name . '%');
        }
        //发起人
        if ($promoter = trim($request->input('promoter'))) {
            $builder->whereHas('course', function ($query) use ($promoter) {
                $query->where('promoter', '=', $promoter);
            });
        }
        //城市
    	if ($city = trim($request->input('city'))) {
             $builder->whereHas('order_course', function ($query) use ($city) {
                $query->where('user_city', '=', $city);
             });
        }
        //付款状态
        if ($order_type = trim($request->input('order_type'))) {
            $builder->where('order_type', '=', $order_type);
        }
        //课程状态
        if ($status = trim($request->input('status'))) {
            $builder->whereHas('course', function ($query) use ($status) {
                $query->where('status', '=', $status);
            });
        }
        //报到状态
        if ($report_flg = trim($request->input('report_flg'))) {
            $builder->whereHas('order_course', function ($query) use ($report_flg) {
                $query->where('report_flg', '=', $report_flg);
            });
        }
        //支付方式
        if ($pay_method = trim($request->input('pay_method'))) {
            $builder->where('pay_method', '=', $pay_method);
        }
        //订单类型
        if ($package_type = trim($request->input('package_type'))) {
            $builder->whereHas('order_course', function ($query) use ($package_type) {
                $query->where('package_flg', '=', $package_type);
            });
        }
        //订单号
        if ($order_code = trim($request->input('order_code'))) {
            $builder->where('order_code', '=', $order_code);
        }
        //用户名称
        if ($nickname = trim($request->input('nickname'))) {
            $builder->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            });
        }
        //手机号
        if ($consignee_tel = trim($request->input('consignee_tel'))) {
            $builder->whereHas('order_course', function ($query) use ($consignee_tel) {
                $query->where('consignee_tel', '=', $consignee_tel);
            });
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->whereHas('order_course', function ($query) use ($s_time) {
                $query->where('created_at', '>=', $s_time);
            });
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->whereHas('order_course', function ($query) use ($e_time) {
                $query->where('created_at', '<=', $e_time);
            });
        }
        $builder->wherePayType('1');
        $builder->orderBy('order.id', 'desc');

        //支付方式
        $pay_type = config('constants.pay_type');
        //单人or套餐
        $package_type = config('constants.package_type');
        //付款状态
        $order_type = config('constants.order_type');
        //课程状态
        $status_list = config('constants.status_list');
        //报道状态
        $report_flg = config('constants.report_flg');
        //发起人
        $partner_list = User::whereRole('3')->lists('realname', 'id');
        //城市
        $city_list = User::whereRole('3')->with('partner_city')->get()->toArray();

        if ($request->input('export')) {
            $data = [
                [   '订单号', '课程名称',
                    '课程状态', '发起人',
                    '城市', '用户',
                    '总价(元)',
                    '付款状态', '报到状态',
                    '手机号',
                    '真实姓名',
//                    '注册时间',
                    '付款时间',
                    '积分减免金额',
                    '优惠券减免金额',
                    '余额减免金额',
                    '实际支付金额',
                ],
            ];
            $builder->chunk(100, function($orders) use(&$data, $arrArea, $status_list, $partner_list, $order_type, $report_flg) {
                if ($orders) foreach ($orders as $order) {
                    $data[] = [
                        $order->order_code, $order->order_name, 
                        @$status_list[$order->course->status], @$partner_list[$order->course->promoter],
                        @$arrArea[$order->order_course->user_city], $order->user->nickname,
                        $order->free_flg == 2 ? $order->total_price : '免费',
                        @$order_type[$order->order_type], @$report_flg[$order->order_course->report_flg],
                        $order->user->mobile,
                        $order->user->realname,
//                        $order->user->created_at,
                        $order->pay_time,
                        $order->point_price,
                        $order->coupon_price,
                        $order->balance_price,
                        $order->price,
                    ];
                }
            });

            return $this->export('好课订单列表', $data);
        }
        $data = $builder->paginate(10);
        
        trim($request->input('province'));

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('order.order_course', compact('data', 'pay_type', 'package_type', 'order_type','province_list','city_list2', 'status_list', 'report_flg', 'partner_list','city_list','arrArea'));
    }

    /** 好课订单详情 */
    public function order_course_show($id)
    {
        $order = Order::withTrashed()->with('course.area', 'order_course', 'user')->wherePayType('1')->find($id);
    	$areas = Area::select('area_id', 'area_name')->where('area_deep', '=', 2)->get();
        $arrArea = array();
        foreach ($areas as &$value) {
            $arrArea[$value['area_id']] = $value['area_name'];
        }
        if ($order == null)
            abort(404, '订单查找失败！');
        //支付方式
        $pay_type = config('constants.pay_type');
        //单人or套餐
        $package_type = config('constants.package_type');
        //付款状态
        $order_type = config('constants.order_type');
        //课程状态
        $status_list = config('constants.status_list');
        //报道状态
        $report_flg = config('constants.report_flg');
        //发起人
        $partner_list = User::whereRole('3')->lists('realname', 'id');
        //城市
        $city_list = User::whereRole('3')->with('partner_city')->get()->toArray();
        //快递公司
        $express_list = Express::orderBy('e_order', 'asc')->lists('e_name', 'e_code');


        return view('order.order_course_show', compact('order', 'pay_type', 'package_type', 'order_type', 'status_list', 'report_flg', 'partner_list', 'city_list', 'express_list','arrArea'));
    }

    /** 好课订单详情编辑 - 保存*/
    public function order_course_update(Request $request, $id)
    {
        $order = Order::wherePayType('1')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();
        $oldPayStatus = $order->order_type;
        if ($data['order_type']) {
            $order->update(array_filter($data));
        }
        if ($oldPayStatus == 1 && request('order_type') == 2) {
            DB::beginTransaction();
            try {
                //上传凭证
                if(!empty($data['cert_pic'])){
                    $order->cert_pic = $data['cert_pic'];
                }
                //上传备注
                if(!empty($data['pay_remark'])){
                    $order->pay_remark = $data['pay_remark'];
                }
                $this->_order_update($order);
                //消费加积分
                $this->_plus_score('13', $order->price, $order->user_id);
                //平台收益
                $this->_log_income($order);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            Event::fire(new OrderPaid($order));
        }
        return redirect()->route('order.order_course');
    }

    /** 好看订单列表 */
    public function order_vcourse(Request $request)
    {
        //关联模型
        $builder = Order::withTrashed()->with('vcourse.agency', 'user');

        //用户名称
        if ($nickname = trim($request->input('nickname'))) {
            $builder->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            });
        }
        //手机号
        if ($consignee_tel = trim($request->input('consignee_tel'))) {
            $builder->whereHas('user', function ($query) use ($consignee_tel) {
                $query->where('mobile', 'like', '%' . $consignee_tel . '%');
            });
        }

        //课程名称
        if ($order_name = trim($request->input('order_name'))) {
            $builder->where('order_name', 'like', '%' . $order_name . '%');
        }
        //付款状态
        if ($order_type = trim($request->input('order_type'))) {
            $builder->where('order_type', '=', $order_type);
        }
        //订单号
        if ($order_code = trim($request->input('order_code'))) {
            $builder->where('order_code', '=', $order_code);
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->where('created_at', '>=', $s_time);
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->where('created_at', '<=', $e_time);
        }


        $builder->wherePayType('2');
        $builder->orderBy('order.id', 'desc');

        //付款状态
        $order_type = config('constants.order_type_vcourse');

        if ($request->input('export')) {
            $data = [
                [   '订单号', '课程名称',
                    '总价(元)', '用户',
                    '付款状态',
                    '手机号',
                    '真实姓名',
//                    '注册时间',
                    '付款时间',
                    '积分减免金额',
                    '优惠券减免金额',
                    '余额减免金额',
                    '实际支付金额',
                ],
            ];
            $builder->chunk(100, function($orders) use(&$data, $order_type) {
                if ($orders) foreach ($orders as $order) {
                    $data[] = [
                        $order->order_code, $order->order_name, 
                        $order->free_flg == 2 ? $order->total_price : '免费', $order->user->nickname,
                        @$order_type[$order->order_type],
                        $order->user->mobile,
                        $order->user->realname,
//                        $order->user->created_at,
                        $order->pay_time,
                        $order->point_price,
                        $order->coupon_price,
                        $order->balance_price,
                        $order->price,
                    ];
                }
            });

            return $this->export('好看订单列表', $data);
        }

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('order.order_vcourse', compact('data', 'order_type'));
    }

    /** 好看订单详情 */
    public function order_vcourse_show($id)
    {
        $order = Order::withTrashed()->with('vcourse.agency', 'user')->wherePayType('2')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');

        //支付方式
        $pay_type = config('constants.pay_type');
        //付款状态
        $order_type = config('constants.order_type_vcourse');

        return view('order.order_vcourse_show', compact('order', 'order_type', 'pay_type'));
    }

    /** 好问订单列表 */
    public function order_question(Request $request)
    {
        //关联模型
        $builder = Order::withTrashed()->with('question', 'user');

        //问题名称
        if ($order_name = trim($request->input('order_name'))) {
            $builder->where('order_name', 'like', '%' . $order_name . '%');
        }
        //订单号
        if ($order_code = trim($request->input('order_code'))) {
            $builder->where('order_code', '=', $order_code);
        }
        //订单属性
        if ($pay_type = trim($request->input('pay_type'))) {
            $builder->where('pay_type', '=', $pay_type);
        }
        //回答状态
        if ($answer_flg = trim($request->input('answer_flg'))) {
            $builder->whereHas('question', function ($query) use ($answer_flg) {
                $query->where('answer_flg', '=', $answer_flg);
            });
        }
        //用户名称
        if ($realname = trim($request->input('realname'))) {
            $builder->whereHas('question', function ($query) use ($realname) {
                $query->whereIn('tutor_id',  User::select('id')->withTrashed()->where('realname', 'like', '%' . $realname . '%')->get());
            });
        }
        //手机号
        if ($consignee_tel = trim($request->input('consignee_tel'))) {
        	$builder->whereHas('question', function ($query) use ($consignee_tel) {
                $query->whereIn('user_id',  User::select('id')->withTrashed()->where('mobile', 'like', '%' . $consignee_tel . '%')->get());
            });
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->where('created_at', '>=', $s_time. ' 00:00:00');
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->where('created_at', '<=', $e_time.' 23:59:59');
        }

        $builder->whereIn('pay_type', [4, 5]);
        $builder->where('order_type', '2');
        $builder->orderBy('order.id', 'desc');

        //好问订单属性
        $order_type_question = config('constants.order_type_question');
        $answer_state = config('constants.answer_state');
        if ($request->input('export')) {
            $data = [
                [   '订单号', '问题名称',
                    '提问人', '指导师', '下单人',
                    '订单属性', '金额(元)', '下单时间',
                    '问题标签',
                    '手机号',
                    '真实姓名',
//                    '注册时间',
                    '付款时间',
                    '积分减免金额',
                    '优惠券减免金额',
                    '余额减免金额',
                    '实际支付金额',
                ],
            ];
            $builder->chunk(100, function($orders) use(&$data, $order_type_question) {
                if ($orders) foreach ($orders as $order) {
                    $data[] = @[
                        $order->order_code, $order->order_name, 
                        $order->question->ask_user->nickname, $order->question->answer_user->nickname, $order->user->nickname, 
                        $order_type_question[$order->pay_type], $order->price, $order->created_at,

                        $order->question ? implode(' ', array_map(function($tag) {
                            return $tag->title;
                        }, $order->question->tags->toArray())) : '',
                        $order->user->mobile,
                        $order->user->realname,
//                        $order->user->created_at,
                        $order->pay_time,
                        $order->point_price,
                        $order->coupon_price,
                        $order->balance_price,
                        $order->price,
                    ];
                }
            });

            return $this->export('好问订单列表', $data);
        }
        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('order.order_question', compact('data', 'order_type_question','answer_state'));
    }

    /** 和会员订单列表 */
    public function order_vip(Request $request)
    {
        //关联模型
        $builder = Order::with('user')->with("order_vip")->with('lover');

        //订单号
        if ($order_code = trim($request->input('order_code'))) {
            $builder->where('order_code', '=', $order_code);
        }
        
        if ($search_province = trim($request->input('search_province'))) {
        	$builder->whereHas('user', function ($query) use ($search_province) {
        		$query->where('province', '=', $search_province);
        	});
        }
        if ($search_city = trim($request->input('search_city'))) {
        	$builder->whereHas('user', function ($query) use ($search_city) {
        		$query->where('city', '=', $search_city);
        	});
        }
        
        //用户名称
        if ($nickname = trim($request->input('nickname'))) {
            $builder->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            });
        }
        //手机号
        if ($consignee_tel = trim($request->input('consignee_tel'))) {
            $builder->whereHas('user', function ($query) use ($consignee_tel) {
                $query->where('mobile', 'like', '%' . $consignee_tel . '%');
            });
        }
        
        //爱心大使筛选项
        if ($lover_key = trim($request->input('lover_key'))) {
        	//dd($lover_key);
        	$builder->whereHas('lover', function ($query) use ($lover_key) {
        		$query->where('nickname', 'like', '%' . $lover_key . '%');
        	})->orWhereHas('lover', function ($query) use ($lover_key) {
        		$query->where('mobile', 'like', '%' . $lover_key . '%');
        	});
        }
        //关联爱心大使
        if ($has_lover = trim($request->input('has_lover'))) {
        	$builder->where('lover_id', ($has_lover == 1  ? '!=':'='), 0);
        }
        //付款状态
        if ($order_type = trim($request->input('order_type'))) {
            $builder->where('order_type', '=', $order_type);
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->where('created_at', '>=', $s_time);
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->where('created_at', '<=', $e_time);
        }

        $builder->wherePayType('6');
        //$builder->orderBy('order.id', 'desc')->orderBy('pay_time','desc');
        $builder->orderBy('pay_time','desc');
        //付款状态
        $order_type = config('constants.order_type_vip');

        // 城市(所有的省以及市)
        $areas = Area::select('area_id', 'area_name')->get();
        $arrArea = array();
        foreach ($areas as &$value) {
        	$arrArea[$value['area_id']] = $value['area_name'];
        }
        
        if ($request->input('export')) {
            $data = [
                [   '订单号', '用户','城市',
                    '总价(元)', 
                    '付款状态', '支付时间',
                    '手机号',
                    '真实姓名',
//                    '注册时间',
                    '付款时间',
                    '积分减免金额',
                    '优惠券减免金额',
                    '余额减免金额',
                    '实际支付金额',
                    '收货人',
                    '地址',
                	'关联爱心大使',
                	'爱心大使手机号',
                	'爱心大使姓名',
                ],
            ];
            $builder->chunk(100, function($orders) use(&$data, $order_type, $arrArea) {
                if ($orders) foreach ($orders as $order) {
                    $data[] = [
                        $order->order_code, @$order->user->nickname, 
                    	@$arrArea[@$order->user->province]." ".@$arrArea[@$order->user->city],
                        $order->free_flg == 2 ? $order->total_price : '免费',
                        @$order_type[$order->order_type], $order->pay_time,
                        @$order->user->mobile,
                        @$order->user->realname,
//                        $order->user->created_at,
                        $order->pay_time,
                        $order->point_price,
                        $order->coupon_price,
                        $order->balance_price,
                        $order->price,
                        @$order->order_vip->consignee,
                        @$order->order_vip->consignee_address,
                    	($order->lover?'是':'否'),
                    	(@$order->lover->mobile),
                    	(@$order->lover->nickname),
                    ];
                }
            });

            return $this->export('和会员订单列表', $data);
        }
       
        $data = $builder->paginate(10);
        
        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        
        // 省
        $areaPs = Area::select('area_id', 'area_name')->where('area_deep', '=', 1)->get();
        
        // 获得选中省下面的城市
        $areaC_search = null;
        if ($search_province = trim($request->input('search_province'))) {
        	$areaC_search = Area::select('area_id', 'area_name')->where('area_parent_id', $search_province)->get();
        }
        
        // 市
        $arrareaCs = array();
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
        
        return view('order.order_vip', ['data'=>$data, 'order_type'=>$order_type,'areas' => $arrArea, 'areaPs' => $areaPs,
            'areaC_search' => $areaC_search, 'arrareaCs' => $arrareaCs]);
    }

    /** 和会员订单详情 */
    public function order_vip_show($id)
    {
        $order = Order::withTrashed()->with('user','order_vip','lover')->wherePayType('6')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');

        //支付方式
        $pay_type = config('constants.pay_type');
        //付款状态
        $order_type = config('constants.order_type_vip');

        $express_list = Express::orderBy('e_order', 'asc')->lists('e_name', 'e_code');

        return view('order.order_vip_show', compact('order', 'order_type', 'pay_type', 'express_list'));
    }

    /** 和会员订单详情编辑 - 保存*/
    public function order_vip_update(Request $request, $id)
    {
        $order = Order::wherePayType('6')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();
        if ($data['delivery_com'] && $data['delivery_nu']) {
            $orderVip = OrderVip::whereOrderId($id)->first();
            if ($orderVip) {
                $orderVip->delivery_com = $data['delivery_com'];
                $orderVip->delivery_nu = $data['delivery_nu'];
                $orderVip->delivery_flg = '2';
                $orderVip->save();
            }
        }
        return redirect()->route('order.order_vip');
    }

    /** 壹家壹订单列表 */
    public function order_opo(Request $request)
    {
        //关联模型
        $builder = Order::withTrashed()->with('opo', 'order_opo', 'user');

        //用户名称
        if ($nickname = trim($request->input('nickname'))) {
            $builder->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            });
        }
        //订单号
        if ($order_code = trim($request->input('order_code'))) {
            $builder->where('order_code', '=', $order_code);
        }
        //手机号
        if ($mobile = trim($request->input('mobile'))) {
            $builder->whereHas('user', function ($query) use ($mobile) {
                $query->where('mobile', '=', $mobile);
            });
        }
        //付款状态
        if ($order_type = trim($request->input('order_type'))) {
            $builder->where('order_type', '=', $order_type);
        }
        //支付方式
        if ($pay_method = trim($request->input('pay_method'))) {
            $builder->where('pay_method', '=', $pay_method);
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->where('created_at', '>=', $s_time);
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->where('created_at', '<=', $e_time);
        }

        $builder->wherePayType('3');
        $builder->orderBy('order.id', 'desc');

        //付款状态
        $order_type = config('constants.order_type');
        //支付方式
        $pay_type = config('constants.pay_type');
        //服务流程进度
        $opo_process = config('constants.opo_process');

        if ($request->input('export')) {
            $data = [
                [   '订单号', '用户',
                    '总价(元)',
                    '付款状态', '流程进度',
                    '支付时间',
                    '手机号',
                    '真实姓名',
//                    '注册时间',
                    '付款时间',
                    '积分减免金额',
                    '优惠券减免金额',
                    '余额减免金额',
                    '实际支付金额',
                ],
            ];
            $builder->chunk(100, function($orders) use(&$data, $order_type, $opo_process) {
                if ($orders) foreach ($orders as $order) {
                    $data[] = [
                        $order->order_code, $order->user->nickname,

                        $order->free_flg == 2 ? $order->total_price : '免费',
                        @$order_type[$order->order_type], @$opo_process[$order->order_opo->process],
                        $order->pay_time,
                        $order->user->mobile,
                        $order->user->realname,
//                        $order->user->created_at,
                        $order->pay_time,
                        $order->point_price,
                        $order->coupon_price,
                        $order->balance_price,
                        $order->price,
                    ];
                }
            });

            return $this->export('壹家壹订单列表', $data);
        }

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('order.order_opo', compact('data', 'order_type', 'pay_type', 'opo_process'));
    }

    /** 壹家壹订单详情 */
    public function order_opo_show($id)
    {
        $order = Order::withTrashed()->with('opo', 'order_opo', 'user')->wherePayType('3')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');

        //付款状态
        $order_type = config('constants.order_type');
        //支付方式
        $pay_type = config('constants.pay_type');
        //服务流程进度
        $opo_process = config('constants.opo_process');

        return view('order.order_opo_show', compact('order', 'order_type', 'pay_type', 'opo_process'));
    }

    /** 壹家壹订单详情编辑 - 保存*/
    public function order_opo_update(Request $request, $id)
    {
        $order = Order::with('order_opo')->wherePayType('3')->find($id);

        if ($order == null)
            abort(404, '订单查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();

        $this->validate($request, [
            'service_comment' => 'required_if:process,5',
            'video_original' => 'required_if:process,5|unique:order_opo,service_url,' . $order->order_opo->id . ',id,deleted_at,NULL',
        ], [
            'service_comment.required_if' => '当服务流程进度为 报告生成 时 服务日志描述 不能为空',
            'video_original.required_if' => '当服务流程进度为 报告生成 时 服务日志不能为空',
        ], [
            'service_comment' => '服务日志描述',
            'video_original' => '服务日志',
        ]);
        $oldPayStatus = $order->order_type;
        if ($data['order_type']) {
            $order->update(array_filter($data));
        }
        //处理线下支付成功回调
        if ($oldPayStatus == 1 && request('order_type') == 2) {
            try {
                //上传凭证
                if(!empty($data['cert_pic'])){
                    $order->cert_pic = $data['cert_pic'];
                }
                //上传备注
                if(!empty($data['pay_remark'])){
                    $order->pay_remark = $data['pay_remark'];
                }
                $this->_order_update($order);
                //消费加积分
                $this->_plus_score('13', $order->price, $order->user_id);
                //平台收益
                $this->_log_income($order);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            Event::fire(new OrderPaid($order));
        }
        //处理壹加壹服务进度更新
        if ($data['process']) {
            $orderOpo = OrderOpo::whereOrderId($id)->first();
            if ($orderOpo) {
                $oldProcess = $orderOpo->process;
                if (isset($data['service_comment']) && isset($data['video_original'])) {
                    $orderOpo->service_comment = $data['service_comment'];
                    $orderOpo->service_url = $data['video_original'];
                    $orderOpo->bucket = config('qiniu.BUCKET_NAME_FILE');
                }
                $orderOpo->process = $data['process'];
                $orderOpo->save();
                if ($oldProcess != $data['process']) {
                    try {
                        //微信通知客户进度更新
                        $notice = Wechat::notice();
                        $notice->send([
                            'touser' => $order->user->openid,
                            'template_id' => 'k5GM6FkjqoDVXZKHGd321TnZ661T92qPG_A0fUDv84U',
                            'url' => front_url('opo'),
                            'topcolor' => '#f7f7f7',
                            'data' => [
                                'first' => '服务进度提示',
                                'keyword1' => '壹家壹',
                                'keyword2' => $orderOpo->process != 5 ? '尊敬的家人，你预约的壹家壹服务已完成' . config('constants.opo_process')[$orderOpo->process] . '，感谢你的配合。' : '尊敬的家人，你预约的壹家壹服务报告已生成，感谢你的配合。',
                                'remark' => '学习是最好的陪伴，陪伴是最好的管教。'
                            ],
                        ]);
                    }catch (\Exception $e){

                    }
                }
            }
        }

        return redirect()->route('order.order_opo');
    }

    /** qiniu文件上传 */
    public function qiniu_uptoken()
    {
        header('Access-Control-Allow-Origin:*');
        $bucket = config('qiniu.BUCKET_NAME_FILE');
        $auth = new Qiniu_Auth(config('qiniu.AK'), config('qiniu.SK'));

        //要进行的操作
        $fops = "yifangyun_preview/v2/format=jpg";

        $policy = array(
            'persistentOps' => $fops,
            'persistentPipeline' => config('qiniu.PIPELINE'),
        );
        $upToken = $auth->uploadToken($bucket, null, 3600, $policy);

        return response()->json(['uptoken' => $upToken]);
    }

    /** qiniu文件删除 */
    public function qiniu_delete(Request $request)
    {
        $bucket = config('qiniu.BUCKET_NAME_VIDEO');
        $auth = new Qiniu_Auth(config('qiniu.AK'), config('qiniu.SK'));
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        //原始key
        $keya = $request->input('keya');
        $vid = $request->input('vid');
        $queryBuilder = OrderOpo::whereServiceUrl($keya);
        if ($vid) {
            $queryBuilder->where('id', '!=', $vid);
            $orderOpo = OrderOpo::find($vid);
            if ($orderOpo) {
                $orderOpo->update(['service_url' => '', 'bucket' => '']);
            }
        }
        //存在别的记录中不能删除
        if ($queryBuilder->first()) {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }

        $errA = $bucketMgr->delete($bucket, $keya);

        if ($errA !== null) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }
    }

    /**
     * 订单状态更新
     * @param $order
     */
    private function _order_update($order)
    {
        $order->pay_method = '2';
        $order->save();
    }

    private function _plus_score($type, $money = 0, $user_id)
    {
        $type = intval($type);
        $money = intval($money);
        //来源:1注册 2分享 4发帖 5评论 6作业 7笔记 8推荐好友注册  12观看视频 13消费
        $score_list = array('1' => '10', '2' => '10', '4' => '5', '5' => '5', '6' => '5', '7' => '5', '8' => '10', '12' => '5', '13' => '1', '14' => '10');

        $date_start = date('Y-m-d 00:00:00');
        $date_end = date('Y-m-d 23:59:59');
        $score_taday = UserPoint::select('point_value')
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where('move_way', 1)
            ->where('user_id', $user_id)
            ->where('source', '<>', 10)//10为取消订单等返还积分  不计在内
            ->get();

        // 计算当天已经获得的总的积分
        $score_total = 0;
        foreach ($score_taday as &$value) {
            $score_total += $value->point_value;
        }

        // 每天的积分上限是200
        if ($score_total >= 200) {
            return true;
        } else {
            $score = $score_list[$type];
            if ($type == 13) {
                $score = intval($money / 10);
            }
            if ($score == '0') {
                return false;
            }
            $userpoint = new UserPoint();
            $userpoint->user_id = $user_id;
            $userpoint->point_value = $score;
            $userpoint->source = $type;
            $userpoint->move_way = 1;

            $user = User::find($user_id);
            $user->score += $score;
            $user->grow += $score;

            if ($userpoint->save() && $user->save()) {
                //发送微信提醒
                try{
                    $scoreSources = config('constants.income_point_source');
                    if (isset($scoreSources[$type])) {
                        $notice = \Wechat::notice();
                        $notice->send([
                            'touser' => $user->openid,
                            'template_id' => 'oxk8-ixLvD_XqQ8enFSy1wJ6qrwziLdeHv7KJqybfwE',
                            'url' => route('user.score'),
                            'topcolor' => '#f7f7f7',
                            'data' => [
                                'first' => '恭喜你获得和贝奖励',
                                'keyword1' => '+' . $score,
                                'keyword2' => $scoreSources[$type],
                                'keyword3' => (string)\Carbon\Carbon::now(),
                                'keyword4' => $user->score,
                                'remark' => '和贝可抵扣听课费，点击查看积分详情'
                            ],
                        ]);
                    }
                }catch (\Exception $e){

                }
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 产生支付，记录平台收益
     *
     * @param object $order 订单ar
     */
    private function _log_income($order)
    {
        $income = [];
        $income['user_id'] = $order->user_id;
        $income['log_type'] = 1;
        $income['order_id'] = $order->id;
        $income['order_no'] = $order->order_code;
        $income['amount'] = $order->price;
        $income['remark'] = '订单在线支付';
        $income['pay_mod'] = 1;
        //订单类型 对应的 收益类型 值
        $order_income_type_map = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 4, 6 => 5];
        $income['income_type'] = $order_income_type_map[$order->pay_type];
        //计算当前的平台收益
        $income['total_amount'] = get_platform_current_amount() + $order->price;
        Income::create($income);
    }


    private function export($title, $data)
    {
        return Excel::create($title.'-'.date('Ymd'), function($excel) use($title,$data) {
            $excel->sheet(str_replace('列表','',$title), function($sheet) use($data) {
                $sheet->rows($data);
            });
        })->download('xlsx');
    }

    /**
     * web uploader  server process ,POST
     */
    public function uploadImages()
    {
        $upload = new UploadFile();// 实例化上传类
        $upload->savePath = 'uploads/order/';// 设置附件上传目录
        $upload->thumb = true;//是否开启图片文件缩略图
        $upload->thumbPrefix = 'thum_';
        $upload->thumbMaxWidth = 140;
        $upload->thumbMaxHeight = 140;
        $upload->maxSize = 2097152;  //2MB

        $message = array();
        if (!$upload->upload()) {
            $message['type'] = 'error';
            $message['content'] = $upload->getErrorMsg();
        } else {
            $info_arr = $upload->getUploadFileInfo();
            foreach ($info_arr as $info) {
                $file_arr[] = $info['savepath'] . $info['savename'];
            }
            $message['type'] = 'right';
            $message['content'] = $file_arr;
        }
        die(json_encode($message, JSON_UNESCAPED_UNICODE));
    }
    
    public function vipRemove(Request $request){
        $id = $request->input('id');
        $white = Order::find($id);
        $white->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }

}
