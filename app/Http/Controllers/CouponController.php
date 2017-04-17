<?php
/**
 *  优惠券模板管理
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Agency;
use App\Models\Course;
use App\Models\User;
use App\Models\Vcourse;
use App\Models\Area;
use Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    private $_rule = [
        'name'       => 'required|min:2|max:20|unique:coupon,name,NULL,id,deleted_at,NULL',
        'type'       => 'required',
        'full_money' => 'required_if:type,1',
        'cut_money'  => 'required_if:type,1',
        'discount'   => 'required_if:type,2',
        'use_scope'  => 'required',
        'use_scope_val' => 'required_if:use_scope,6',
        'available_period_type' => 'required',
        'available_days' => 'required_if:available_period_type,1|integer',
        'available_start_time' => 'required_if:available_period_type,2',
        'available_end_time' => 'required_if:available_period_type,2',
    ];

    private $_message = [
        'full_money.required_if' => '当 类型 为 优惠券 时 满多少 不能为空',
        'cut_money.required_if' => '当 类型 为 优惠券 时 减多少 不能为空',
        'discount.required_if' => '当 类型 为 折扣券 时 折扣 不能为空',

        'available_days.required_if' => '当 有效期类型 为 天数 时 有效期天数 不能为空',
        'available_start_time.required_if' => '当 有效期类型 为 起止时间 时 有效期开始时间 不能为空',
        'available_end_time.required_if' => '当 有效期类型 为 起止时间 时 有效期截止时间 不能为空',
    ];

    private $_customAttributes = [
        'name' => '名称',
        'type' => '类型',
        'use_scope' => '适用范围',
        'available_period_type' => '有效期类型',
        "available_days" => "有效期天数",
        "available_start_time" => "有效期开始时间",
        "available_end_time" => "有效期截止时间",
        "use_scope_val"   => "适用范围分类或指定课程"
    ];

    /** 列表 */
    public function index(Request $request)
    {
        $builder = Coupon::with('coupon_user');
        /** 名称 */
        if ($search_name = trim($request->input('search_name'))) {
            $builder->where('name', 'like', '%' . $search_name . '%');
        }

        $coupons = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $coupons->appends($input, $value);
            }
        }

        return view('coupon.index', ['coupons' => $coupons,'type'=>config('constants.coupon_type'),'coupon_use_scope'=>config('constants.coupon_use_scope')]);
    }

    /** 新增 */
    public function create()
    {
        return view('coupon.create',[
            'type'               => config('constants.coupon_type'),
            'use_scope'          => config('constants.coupon_use_scope'),
            'coupon_period_type' => config('constants.coupon_period_type')
        ]);
    }

    /** 新增-保存 */
    public function store(Request $request)
    {
        $data = $request->all();
        $rule = $this->_rule;

        if (in_array($request->input('use_scope') ,[6,7,8,9])) {
            $rule['use_scope_val'] = 'required';
        }

        if ($request->input('available_period_type') == 2) {
            $rule['available_start_time'] .= '|date';
            $rule['available_end_time']   .= '|date|after:available_start_time';
        }

        if(isset($data['use_scope_val']) && $data['use_scope_val']){
            $data['use_scope_val'] = implode(",",$data['use_scope_val']);
        }

        $this->validate($request, $rule, $this->_message, $this->_customAttributes);

        Coupon::create($data);
        return redirect()->route('coupon');
    }

    /**
     * 优惠券模板详情 + 发放详情
     *
     */
    public function show($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon == null)
            abort(404, '不存在');

        //发放日志
        $builder = CouponUser::withTrashed()->select('created_at','coupon_id',\DB::raw('COUNT(id) as num'))->where('coupon_id', $id)->where('come_from', 4);
        $coupon_log = $builder->groupBy('created_at')->get();

        $use_scope_val = $coupon['use_scope_val'] ? explode(',',$coupon['use_scope_val']) :[];
        return view('coupon.show',[
            'type'               => config('constants.coupon_type'),
            'use_scope'          => config('constants.coupon_use_scope'),
            'coupon_period_type' => config('constants.coupon_period_type'),
            'use_scope_val'     =>  $use_scope_val,
            'coupon'            =>  $coupon,
            'coupon_log'        => $coupon_log
        ]);
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon == null)
            abort(404, '不存在');

        $use_scope_val = $coupon['use_scope_val'] ? explode(',',$coupon['use_scope_val']) :[];
        return view('coupon.edit',[
            'type'               => config('constants.coupon_type'),
            'use_scope'          => config('constants.coupon_use_scope'),
            'coupon_period_type' => config('constants.coupon_period_type'),
            'use_scope_val'     =>  $use_scope_val,
            'coupon'            =>  $coupon
        ]);
    }


    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if ($coupon == null)
            abort(404, '不存在！');

        $data = $request->all();
        $rule = $this->_rule;

        if (in_array($request->input('use_scope') ,[6,7,8,9])) {
            $rule['use_scope_val'] = 'required';
        }

        $rule['name'] = 'required|min:2|max:20|unique:coupon,name,'.$id.',id,deleted_at,NULL';

        if ($request->input('available_period_type') == 2) {
            $rule['available_start_time'] .= '|date';
            $rule['available_end_time']   .= '|date|after:available_start_time';
        }

        if(isset($data['use_scope_val']) && $data['use_scope_val']){
            $data['use_scope_val'] = implode(",",$data['use_scope_val']);
        }

        $this->validate($request, $rule, $this->_message, $this->_customAttributes);

        $coupon->update($data);
        return redirect()->route('coupon');
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->input('id'));

        if (!$coupon) {
            return response()->json(['code' => 1, 'message' => '不存在!']);
        }

        $coupon->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }

    /** 优惠券发放 */
    public function distribute(Request $request,$id)
    {
        $coupon = Coupon::find($id);
        if ($coupon == null)
            abort(404, '不存在！');
        elseif($coupon->available_period_type==2 && time()>strtotime($coupon->available_end_time))
            abort(404, '优惠券已过期，请重新选择！');


        //筛选发放用户
        $builder = User::with('c_province','c_city');

        /** 省 */
        if ($province = trim($request->input('province'))) {
            $builder->where('province',$province);
        }

        /** 市 */
        if ($province = trim($request->input('city'))) {
            $builder->where('city',$province);
        }

        /** 性别 */
        if ($province = trim($request->input('c_sex'))) {
            $builder->where('c_sex',$province);
        }
        /** 注册手机号 */
        if ($has_mobile = trim($request->input('has_mobile'))) {
		if ($has_mobile == 'yes') $builder->where('mobile','>','');
		else if ($has_mobile == 'no') $builder->where('mobile','');
        }

        /** 最小年龄 */
        if ($c_age_min = trim($request->input('c_age_min'))) {
            $builder->where('c_age','>=',$c_age_min);
        }

        /** 最大年龄 */
        if ($c_age_max = trim($request->input('c_age_max'))) {
            $builder->where('c_age','<=',$c_age_max);
        }

        /** 最小成长值 */
        if ($grow_min = trim($request->input('grow_min'))) {
            $builder->where('grow','>=',$grow_min);
        }

        /** 最大成长值 */
        if ($grow_max = trim($request->input('grow_max'))) {
            $builder->where('grow','<=',$grow_max);
        }

        /** 开始时间 */
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        /** 结束时间 */
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        //用户名称
        if ($nickname = trim($request->input('nickname'))) {
		$builder->where(function($query) use($nickname) {
		    $query->where('nickname', 'like', '%' . $nickname . '%')
			  ->orWhere('realname', 'like', '%' . $nickname . '%');
		});
        }


        $all_data = $builder->get();
        $data = $builder->paginate(20);
        //发放给所有筛选用户 使用
        $user_ids = $all_data->lists('id')->toArray();
        $user_ids_str = implode(',',$user_ids);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('coupon.distribute',[
            'item_coupon'=>$coupon,
            'coupon_use_scope'=> config('constants.coupon_use_scope'),
            'users' => $data,
            'user_role'=> config('constants.user_role'),
            'user_sex'=> config('constants.user_sex'),
            'user_ids_str' => $user_ids_str
        ]);
    }

    /** 用户优惠券 发放 */
    public function distribute_selected(Request $request)
    {
        $ids = explode(',',trim($request->input('id'),','));
        $coupon_id = $request->input('coupon_id');

        //计算过期时间
        $coupon = Coupon::find($coupon_id);
        if($coupon->available_period_type == 1)
        {
            $expire_at = date('Y-m-d h:i:s',time() + 86400 * $coupon->available_days);
        }else{
            $expire_at = $coupon->available_end_time;
        }

        $data = [];

        foreach($ids as $k=>$v){
            $data[$k]['coupon_id'] = $coupon_id;
            $data[$k]['user_id']   = $v;
            $data[$k]['come_from'] = 4;
            $data[$k]['expire_at'] = $expire_at;
            $data[$k]['created_at'] = date('Y-m-d h:i:s');
        }

        if(CouponUser::insert($data))
        {
            if($request->input('content')) {
                try {
                    $userMobiles = User::whereIn('id', $ids)->lists('mobile');
                    send_sms($userMobiles, $request->input('content'));
                } catch (\Exception $e) {
                    return response()->json(['code' => 1, 'message' => '优惠券发放成功，短信发送失败!']);
                }
            }
            return response()->json(['code' => 0, 'message' => '发放成功!']);
        }
        else
        {
            return response()->json(['code' => 0, 'message' => '发放失败!']);
        }
    }

    /**
     * 用户优惠券
     */
    public function record(Request $request)
    {
        $builder = CouponUser::with('c_user','c_coupon');

        if ($user_name = trim($request->input('user_name'))) {
            $builder->whereHas('c_user', function ($query) use ($user_name) {
                $query->where('nickname', 'like', '%' . $user_name . '%');
            });
        }

        if ($coupon_name = trim($request->input('coupon_name'))) {
            $builder->whereHas('c_coupon', function ($query) use ($coupon_name) {
                $query->where('name', 'like', '%' . $coupon_name . '%');
            });
        }

        if ($coupon_type = trim($request->input('coupon_type'))) {
            $builder->whereHas('c_coupon', function ($query) use ($coupon_type) {
                $query->where('type', $coupon_type);
            });
        }

        if ($coupon_get_from = trim($request->input('coupon_get_from'))) {
            $builder->where('come_from',$coupon_get_from);
        }

        //发放时间 查看发放记录使用
        if ($record_date = $request->input('record_date')) {
            $builder->where('created_at',$record_date['date']);
            $builder->where('come_from',4);
        }

        if ($coupon_id = trim($request->input('coupon_id'))) {
            $builder->whereHas('c_coupon', function ($query) use ($coupon_id) {
                $query->where('id', $coupon_id);
            });
        }

        if ($coupon_use_scope = trim($request->input('coupon_use_scope'))) {
            $builder->whereHas('c_coupon', function ($query) use ($coupon_use_scope) {
                $query->where('use_scope', $coupon_use_scope);
            });
        }

        //使用状态
        if ($coupon_use_status = $request->input('coupon_use_status')) {
            if ($coupon_use_status == 1) {
                $builder->where('is_used', 1);
            } elseif ($coupon_use_status == 2){
                //未使用 & 过期时间大于当前时间
                $builder->where('is_used', 2)->where('expire_at', '>', Carbon::now());
            } elseif ($coupon_use_status == 3) {
               //未使用 & 过期时间小于当前时间
                $builder->where('is_used', 2)->where('expire_at', '<', Carbon::now());
            }
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //开始时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        $data = $builder->orderBy('coupon_user.id','DESC')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        //状态 1已使用 2未使用 3已过期
        foreach ($data as $k => $item) {
            if ($item->is_used == 2 && $item->expire_at < Carbon::now()) {
                $item->is_used = 3;
            }
        }

        return view('coupon.record',[
            'data'  => $data,
            'coupon_type'=> config('constants.coupon_type'),
            'coupon_use_status' => config('constants.coupon_use_status'),
            'coupon_get_from' => config('constants.coupon_get_from'),
            'coupon_use_scope' => config('constants.coupon_use_scope'),
        ]);
    }

    /** 用户优惠券 删除 */
    public function record_delete(Request $request)
    {
        $coupon_user = CouponUser::find($request->input('id'));

        if (!$coupon_user) {
            return response()->json(['code' => 1, 'message' => '不存在!']);
        }

        $coupon_user->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }


    /** 适用范围 ：好课，好看 类别 */
    public function select_agency(Request $request)
    {
        $ids = json_decode($request->input('ids'),true);

        $select_agency = '';
        $data = Agency::lists('agency_name','id')->toArray();
        if($data){
            $select_agency .= '<div class="row" id="select_scope_child"><div class="col-sm-2"><div class="form-group fg-line"><label>适用分类</label>';
            $select_agency .= '<select id="use_scope_val" name="use_scope_val[]" multiple="multiple" class="selectpicker">';
            foreach($data as $k=>$v){
                $select_agency .='<option value="'.$k.'"  '.($ids && in_array($k,$ids) ? 'selected':'').' >'.$v.'</option>';
            }
            $select_agency .= '</select></div></div></div>';
        }
        echo $select_agency;
    }

    /** 适用范围 ：某课  某视频 集合*/
    public function select_course(Request $request)
    {
        $select_course = '';

        $ids = json_decode($request->input('ids'),true);
        $type_id = $request->input('type_id');

        if($type_id == 7)
        {
            $data = Course::lists('title','id')->toArray();
        }elseif($type_id == 9){
            $data = VCourse::lists('title','id')->toArray();
        }

        if($data){
            $select_course .= '<div class="row" id="select_scope_child"><div class="col-sm-12"><div class="form-group fg-line"><label>适用课程</label>';
            $select_course .= '<select id="use_scope_val" name="use_scope_val[]" multiple="multiple" class="selectpicker">';
            foreach($data as $k=>$v){
                $select_course .='<option value="'.$k.'" '.($ids && in_array($k,$ids) ? 'selected':'').' >'.$v.'</option>';
            }
            $select_course .= '</select></div></div></div>';
        }
        echo $select_course;
    }


    /** 发放-选择市区 */
    public function select_city(Request $request)
    {
        echo select_city($request->input('province_id'),$request->input('selected_city_id'));
    }

}
