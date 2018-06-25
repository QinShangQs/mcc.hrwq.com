<?php
/**
 * 用户管理
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Models\User;
use App\Models\Area;
use App\Models\UserTutorApply;
use App\Models\UserPartnerApply;
use Wechat, Excel;
use Cache;


class UserController extends Controller
{
    // 用户列表
    public function index(Request $request)
    {
        $builder = User::select('user.*')->with('lover');

        if ($search_mobile = trim($request->input('search_mobile'))) {
            $builder->where('mobile', 'like', '%' . $search_mobile . '%');
        }

        if ($search_role = trim($request->input('search_role'))) {
            $builder->where('role', '=', $search_role);
        }
        if ($search_province = trim($request->input('search_province'))) {
            $builder->where('province', '=', $search_province);
        }
        if ($search_city = trim($request->input('search_city'))) {
            $builder->where('city', '=', $search_city);
        }
        if ($search_vip = trim($request->input('search_vip'))) {
            $builder->where('vip_flg', '=', $search_vip);
        }
        if ($search_time_s = trim($request->input('search_time_s'))) {
            $builder->where('register_at', '>=', $search_time_s);
        }
        if ($search_time_e = trim($request->input('search_time_e'))) {
            $builder->where('register_at', '<=', $search_time_e);
        }
        if ($search_grow_s = trim($request->input('search_grow_s'))) {
            $builder->where('grow', '>', $search_grow_s);
        }
        if ($search_grow_e = trim($request->input('search_grow_e'))) {
            $builder->where('grow', '<', $search_grow_e);
        }
        if($search_left_day_s = trim($request->input('search_left_day_s'))){
        	$builder->where('vip_left_day','>' ,date('Y-m-d',strtotime("+ {$search_left_day_s} day")) );
        }
        if($search_left_day_e = trim($request->input('search_left_day_e'))){
        	$builder->where('vip_left_day','<' , date('Y-m-d',strtotime("+ {$search_left_day_e} day")) );
        }
        
        if ($search_lover = trim($request->input('search_lover'))) {
        	$builder->where('lover_id', ($search_lover == 'yes' ? '!=':'='), 0);
        }
        
        if ($search_lover_time_s = trim($request->input('search_lover_time_s'))) {
        	$builder->where('lover_time', '>=', $search_lover_time_s ." 00:00:00");
        }
        if ($search_lover_time_e = trim($request->input('search_lover_time_e'))) {
        	$builder->where('lover_time', '<=', $search_lover_time_e ." 23:59:59");
        }
        
        //爱心大使
        if ($lover_key = trim($request->input('lover_key'))) {
        	$builder->whereHas('lover', function ($query) use ($lover_key) {
        		$query->where('mobile', 'like', '%' . $lover_key . '%');
        	});
        }
        
    	//用户
        if ($nickname = trim($request->input('nickname'))) {
            $builder->where('nickname', 'like', '%' . $nickname . '%')->orWhere('realname', 'like', '%' . $nickname . '%');
        }
        /** 注册手机号 */
        if ($has_mobile = trim($request->input('has_mobile'))) {
		if ($has_mobile == 'yes') $builder->where('mobile','>','');
		else if ($has_mobile == 'no') $builder->where('mobile','');
        }

        // 角色
        $user_role = config('constants.user_role');
        // 称呼
        $user_label = config('constants.user_label');
        // 是否为和会员
        $user_vip_flg = config('constants.user_vip_flg');

        // 城市(所有的省以及市)
        $areaKey = "area_user_index";
        $arrArea = Cache::get($areaKey);
        if(empty($arrArea)){
            $areas = Area::select('area_id', 'area_name')->get();
            $arrArea = array();
            foreach ($areas as &$value) {
                $arrArea[$value['area_id']] = $value['area_name'];
            }
            Cache::put($areaKey, $arrArea, 60*24*7);
        }
        
	if ($request->input('export')) {
	    $data = [
                [   'ID', '昵称', '姓名', '手机号', 
                    '角色', '称呼',
                    '城市',
                    '首次登录时间', '成长值',
                    '是否为和会员', '和会员激活码','和会员天数',
                    '爱心大使昵称','爱心大使手机号','爱心大使关联时间'
                ],
            ];
            $builder->orderBy('id', 'desc')->chunk(100, function($users) use(&$data, $arrArea, $user_role, $user_label, $user_vip_flg) {
                if ($users) foreach ($users as $user) {
                        $data[] = [
                            $user->id, $user->nickname, $user->realname, $user->mobile,
                            $user->role ? ($user_role[$user->role] ?: '') : '', $user->label ? ($user_label[$user->label] ?: '') : '',
                            ($user->province ? ($arrArea[$user->province] ?: '') : '').($user->city ? ($arrArea[$user->city] ?: '') : ''),
                            $user->created_at, $user->grow,
                            $user->vip_flg ? ($user_vip_flg[$user->vip_flg] ?: '') : '', $user->vip_code,
                        	computer_vip_left_day($user->vip_left_day),
                        	@$user->lover->nickname,
                        	@$user->lover->mobile,
                                @$user->lover->lover_time
                        ];
                }
            });

            return Excel::create('用户信息列表-'.date('Ymd'), function($excel) use($data) {
                $excel->sheet('用户列表', function($sheet) use($data) {
                    $sheet->rows($data);
                });
            })->download('xlsx');
	}

        $users = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $users->appends($input, $value);
            }
        }

        // 省
        $areaPsKey = "area_ps_user_index";
        $areaPs = Cache::get($areaPsKey);
        if(empty($areaPs)){
            $areaPs = Area::select('area_id', 'area_name')->where('area_deep', '=', 1)->get();
            Cache::put($areaPsKey, $areaPs, 60*24*7);
        }
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
                $areaCsKey = "area_cs_user_index_" . $value['area_id'];
                $areaCs = Cache::get($areaCsKey);
                if(empty($areaCs)){
                    $areaCs = Area::select('area_id', 'area_name')->where('area_parent_id', $value['area_id'])->get();
                    Cache::put($areaCsKey, $areaCs, 60*24*7);
                }
      
                $arrareaC = array();
                if ($areaCs) {
                    $arrareaC = $areaCs->toArray();
                }
                $arrareaCs2[$value['area_id']] = $arrareaC;
            }
        }
        $arrareaCs = json_encode($arrareaCs2);

        return view('user.index', ['users' => $users, 'user_role' => $user_role, 'user_label' => $user_label, 'user_vip_flg' => $user_vip_flg, 'areas' => $arrArea, 'areaPs' => $areaPs,
            'areaC_search' => $areaC_search, 'arrareaCs' => $arrareaCs]);
    }


    /** 用户编辑 页面 */
    public function edit($id)
    {
        $id = intval($id);
        $user = User::find($id);

        if ($user == null) {
            abort(404, '不存在该用户！');
        }

        // 角色
        $user_role = config('constants.user_role');

        // 称呼
        $user_label = config('constants.user_label');

//        // 城市(所有的省以及市)
//        $areas = Area::select('area_id', 'area_name')->get();
//        $arrArea = array();
//        foreach ($areas as &$value) {
//            $arrArea[$value['area_id']] = $value['area_name'];
//        }
		//获取所有省
		$province_list = Area::select('area_id', 'area_name')->where('area_deep', '=', 1)->get();
		//获取所有市
		$city_list = Area::select('area_id', 'area_name')->where('area_deep', '=', 2)->get();
        // 是否为和会员
        $user_vip_flg = config('constants.user_vip_flg');

        // 性别/孩子性别
        $user_sex = config('constants.user_sex');


        return view('user.edit', ['user' => $user, 'province_list' => $province_list,'city_list' => $city_list,'user_role' => $user_role, 'user_label' => $user_label, 'user_vip_flg' => $user_vip_flg, 'user_sex' => $user_sex]);
    }

    
     /** 获取某省下城市列表 */
    public function getcitylist(Request $request)
    {
    	$id = intval($request->input('id'));
    	$city_list = Area::select('area_id', 'area_name')->where('area_parent_id', '=', $id)->get();
    	return response()->json(['data' => $city_list, 'message' => '获取成功!']);
    }
    
    /** 用户编辑 */
    public function update(Request $request)
    {

        $this->validate($request, [
            'role' => 'required',
            'grow' => 'required|integer',

        ], [], [
            'role' => '角色',
            'grow' => '成长值',
        ]);

        $id = $request->input('id');
        $user = User::find($id);
        $originalRole = $user->role;

        $user->role = $request->input('role');
        $user->province = $request->input('province');
        $user->city = $request->input('city');
        $user->grow = $request->input('grow');
        $user->vip_flg = $request->input('vip_flg');
        $user->vip_code = $request->input('vip_code');
        $user->block = $request->input('block');
        $user->sort = $request->input('sort')?$request->input('sort'):null;
		$user->vip_left_day = $request->input('vip_left_day');
        if ($user->save()) {
            if (($originalRole == 1 || $originalRole == 3) && $request->input('role') == 2) {
                //发送微信模板消息通知
                if(config('app.debug') === false){
                    $notice = Wechat::notice();
                    $messageId = $notice->send([
                        'touser' => $user->openid,
                        'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                        'url' => front_url('tutor/complete'),
                        'topcolor' => '#f7f7f7',
                        'data' => [
                            'first' => '身份变更',
                            'keyword1' => '完善指导师资料',
                            'keyword2' => '待完善',
                            'remark' => '点击前往完善指导师资料,审核通过后方能成为指导师。'
                        ],
                    ]);
                }
            }
            if (($originalRole == 1 || $originalRole == 2) && $request->input('role') == 3) {
                //发送微信模板消息通知
                if(config('app.debug') === false){
                    $notice = Wechat::notice();
                    $messageId = $notice->send([
                        'touser' => $user->openid,
                        'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                        'url' => front_url('partner/complete'),
                        'topcolor' => '#f7f7f7',
                        'data' => [
                            'first' => '身份变更',
                            'keyword1' => '完善合伙人资料',
                            'keyword2' => '待完善',
                            'remark' => '点击前往完善合伙人资料,审核通过后方能成为合伙人。'
                        ],
                    ]);
                }
            }
            return redirect()->route('user.index');
        } else {
            return redirect()->back()->withInput()->withErrors('编辑失败');
        }

    }

    /** 禁用用户 */
    public function block(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        if ($user == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户!']);
        }

        $user->block = 2;
        if ($user->save()) {
            return response()->json(['code' => 0, 'message' => '禁用成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '禁用失败!']);
        }

    }

    /** 启用用户 */
    public function unlock(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        if ($user == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户!']);
        }

        $user->block = 1;
        if ($user->save()) {
            return response()->json(['code' => 0, 'message' => '启用成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '启用失败!']);
        }

    }

    /** 用户详情 */
    public function show($id)
    {

        $id = intval($id);
        $user = User::find($id);

        if ($user == null) {
            abort(404, '不存在该产品！');
        }

        // 角色
        $user_role = config('constants.user_role');

        // 称呼
        $user_label = config('constants.user_label');

        // 是否为和会员
        $user_vip_flg = config('constants.user_vip_flg');

        // 城市(所有的省以及市)
        $areas = Area::select('area_id', 'area_name')->get();
        $arrArea = array();
        foreach ($areas as &$value) {
            $arrArea[$value['area_id']] = $value['area_name'];
        }

        // 孩子性别
        $user_sex = config('constants.user_sex');

        $lover = $user->lover_id == 0 ? null : (User::find($user->lover_id));

        return view('user.show', ['user' => $user, 'user_role' => $user_role, 'user_label' => $user_label, 'user_vip_flg' => $user_vip_flg, 
        		'areas' => $arrArea, 'user_sex' => $user_sex,'lover'=>$lover]);
    }

    // 合伙人审核列表
    public function partner_list(Request $request)
    {
        $builder = UserPartnerApply::select('user_partner_apply.*', 'area.area_name')
            ->with(['user'])
            ->leftjoin('area', 'area.area_id', '=', 'user_partner_apply.city')
            ->where('progress', '=', 1);

        if ($search_nickname = trim($request->input('search_nickname'))) {
            $builder->whereHas('user', function ($query) use ($search_nickname) {
                $query->where('nickname', 'like', '%' . $search_nickname . '%');
            });
        }
        if ($search_realname = trim($request->input('search_realname'))) {
            $builder->where('realname', 'like', '%' . $search_realname . '%');
        }
        if ($search_mobile = trim($request->input('search_mobile'))) {
            $builder->whereHas('user', function ($query) use ($search_mobile) {
                $query->where('mobile', 'like', '%' . $search_mobile . '%');
            });
        }
        if ($search_sex = trim($request->input('search_sex'))) {
            $builder->where('sex', '=', $search_sex);
        }
        if ($search_email = trim($request->input('search_email'))) {
            $builder->where('email', 'like', '%' . $search_email . '%');
        }
        if ($search_address = trim($request->input('search_address'))) {
            $builder->where('address', 'like', '%' . $search_address . '%');
        }
        if ($search_apply_reason = trim($request->input('search_apply_reason'))) {
            $builder->where('apply_reason', 'like', '%' . $search_apply_reason . '%');
        }
        if ($search_time_s = trim($request->input('search_time_s'))) {
            $builder->where('created_at', '>=', $search_time_s);
        }
        if ($search_time_e = trim($request->input('search_time_e'))) {
            $builder->where('created_at', '<=', $search_time_e);
        }


        $user_tutors = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $user_tutors->appends($input, $value);
            }
        }

        // 性别
        $user_sex = config('constants.user_sex');
        // 合伙人审核进度
        $partner_apply_progress = config('constants.partner_apply_progress');


        return view('user.partner_list', ['user_tutors' => $user_tutors, 'user_sex' => $user_sex, 'partner_apply_progress' => $partner_apply_progress]);
    }


    /** 合伙人查看与审核 页面 */
    public function partner_check($id)
    {
        $id = intval($id);
        $userpartner = UserPartnerApply::select('user_partner_apply.*', 'area.area_name')
            ->with(['user'])
            ->leftjoin('area', 'area.area_id', '=', 'user_partner_apply.city')
            ->find($id);

        if ($userpartner == null) {
            abort(404, '不存在该用户申请！');
        }

        // 性别
        $user_sex = config('constants.user_sex');
        // 合伙人审核进度
        $partner_apply_progress = config('constants.partner_apply_progress');

        // 判断该申请中的期望城市是否已经有合伙人
        //$user_has_partner = User::where('partner_city', $userpartner->city)->where('role', 3)->where('id','!=',$userpartner->user_id)->first();
        $user_has_partner = false;
        return view('user.partner_check', ['userpartner' => $userpartner, 'user_sex' => $user_sex, 'partner_apply_progress' => $partner_apply_progress, 'user_has_partner' => $user_has_partner,]);
    }

    /** 合伙人资料审核通过 */
    public function partner_pass(Request $request)
    {
        $id = $request->input('id');
        $userpartner = UserPartnerApply::find($id);

        if ($userpartner == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户申请!']);
        }

        $userpartner->progress = 2;

        // 保存user表中
        $user = User::find($userpartner->user_id);
        if ($user == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户!']);
        }
        // 判断该用户的角色是否是指导师
        if ($user->role == 2) {
            return response()->json(['code' => 1, 'message' => '该用户是指导师，不可再次申请为合伙人!']);
        }
        // 判断该申请中的期望城市是否已经有合伙人
        /*$user_has_partner = User::where('partner_city', $userpartner->city)->where('role', 3)->where('id','!=',$userpartner->user_id)->first();
        if ($user_has_partner) {
            return response()->json(['code' => 1, 'message' => '该申请中的期望城市已经有合伙人，昵称为' . $user_has_partner->nickname . '!']);
        }*/

        $user->realname = $userpartner->realname;
        $user->sex = $userpartner->sex;
        $user->email = $userpartner->email;
        $user->address = $userpartner->address;
        $user->partner_city = $userpartner->city;
        $user->role = 3;// 只有在资料审核通过时，才能成为真正的合伙人角色

        if ($userpartner->save() && $user->save()) {
            if(config('app.debug') === false){
                try {
                //发送模板消息通知用户
                    $notice = Wechat::notice();
                    $messageId = $notice->send([
                        'touser' => $user->openid,
                        'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                        'url' => front_url('partner/operate' ),
                        'topcolor' => '#f7f7f7',
                        'data' => [
                            'first' => '合伙人资料审核通过！',
                            'keyword1' => '合伙人资料更新',
                            'keyword2' => '合伙人资料审核通过',
                            'remark' => '点击前往合伙人中心查看详情'
                        ],
                    ]);
                }catch (\Exception $e){

                }
            }
            
            return response()->json(['code' => 0, 'message' => '资料审核通过成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '资料审核通过失败!']);
        }

    }

    /** 合伙人资料审核不通过 */
    public function partner_reject(Request $request)
    {
        $id = $request->input('id');
        $fali_cause = $request->input('fali_cause');
        $userpartner = UserPartnerApply::find($id);
        $user = User::find($userpartner->user_id);
        if ($userpartner == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户申请!']);
        }
        if ($user == null) {
            return response()->json(['code' => 2, 'message' => '不存在该用户!']);
        }

        $userpartner->progress = 3;
        $userpartner->fali_cause = $fali_cause;
        if ($userpartner->save()) {
            try{
                //发送模板消息通知用户
                $notice = Wechat::notice();
                $messageId = $notice->send([
                    'touser' => $user->openid,
                    'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                    'url' => front_url('partner/complete'),
                    'topcolor' => '#f7f7f7',
                    'data' => [
                        'first' => '合伙人资料审核未通过！',
                        'keyword1'=>'合伙人资料更新',
                        'keyword2'=> '合伙人资料审核未通过',
                        'remark'=> '未通过原因：'.$fali_cause ."。点击前往完善"
                    ],
                ]);
            }catch (\Exception $e){

            }
            return response()->json(['code' => 0, 'message' => '资料审核驳回成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '资料审核驳回失败!']);
        }

    }

    /** 合伙人审核冻结 */
    public function partner_frozen(Request $request)
    {
        $id = $request->input('id');
        $usertutor = UserPartnerApply::find($id);

        if ($usertutor == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户申请!']);
        }

        $usertutor->progress = 4;
        if ($usertutor->save()) {
            return response()->json(['code' => 0, 'message' => '审核冻结成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '审核冻结失败!']);
        }

    }


    /** 合伙人审核解冻 */
    public function partner_unfreeze(Request $request)
    {
        $id = $request->input('id');
        $usertutor = UserPartnerApply::where('progress', 4)->find($id);

        if ($usertutor == null) {
            return response()->json(['code' => 1, 'message' => '不存在该需要解冻的用户申请!']);
        }

        $usertutor->progress = 1;
        if ($usertutor->save()) {
            return response()->json(['code' => 0, 'message' => '审核解冻成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '审核解冻失败!']);
        }

    }

}
