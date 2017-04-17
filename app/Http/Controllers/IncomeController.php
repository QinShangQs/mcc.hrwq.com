<?php
/**
 * 收益管理
 */

namespace App\Http\Controllers;

use App\Models\MerchantPayLog;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\IncomeScale;
use App\Models\Income;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserBalance;
use DB;

class IncomeController extends Controller
{
    /**
     * 用户收益
     */
    public function user(Request $request)
    {
        $builder = User::with('c_province', 'c_city');
        //手机号
        if ($search_phone = trim($request->input('search_phone'))) {
            $builder->where('mobile', 'like', '%' . $search_phone . '%');
        }

        //昵称
        if ($search_name = trim($request->input('search_name'))) {
		$builder->where(function($query) use($search_name) {
		    $query->where('nickname', 'like', '%' . $search_name . '%')
			->orWhere('realname', 'like', '%' . $search_name . '%');
		});
        }

        //角色
        if ($search_role = trim($request->input('search_role'))) {
            $builder->where('role', $search_role);
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //结束时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        //当前余额
        if ($score = trim($request->input('current_balance'))) {
            $pick_mod = $request->input('pick_mod');
            $mod = '>';
            if ($pick_mod > 0) {
                if ($pick_mod == 2) {
                    $mod = '=';
                } elseif ($pick_mod == 3) {
                    $mod = '<';
                }
                $builder->where('current_balance', $mod, $score);
            }
        }

        // 省
        if ($province = trim($request->input('province'))) {
            $builder->where('province', $province);
        }

        //市
        if ($city = trim($request->input('city'))) {
            $builder->where('city', $city);
        }

        //市
        if ($orderBy = trim($request->input('orderby'))) {
            $data = $builder->orderBy($orderBy, $request->input('ordertype'))->paginate(10);
        }else{
            $data = $builder->orderBy('user.id', 'desc')->paginate(10);
        }

        return view('income.user_index', [
            'data' => $data,
            'user_role' => config('constants.user_role'),
            'get_province' => get_province()
        ]);
    }

    /**
     * 用户收益-卡片账
     */
    public function user_show($id)
    {
        $user = User::with(['user_balance' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($id);

        if ($user == null)
            abort(404, '查找失败！');

        return view('income.user_show', [
            'user' => $user,
            'user_role' => config('constants.user_role'),
            'income_point_source' => config('constants.income_point_source')
        ]);
    }

    /**
     *  余额增减
     *  1.user-current_balance/balance ++   2.user_balance 用户记录
     *
     */
    public function user_balance_mod(Request $request)
    {
        $id = $request->input('id');
        $item = User::find($id);

        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '不存在的账号!']);
        }

        $this->validate($request, [
            'operate_type' => 'required',
            'amount' => 'required|numeric',
            'source' => 'required',
            'remark' => 'required|min:2|max:255',
        ], [], [
            'remark' => '备注',
            'amount' => '金额',
            'operate_type' => '操作',
            'source' => '类型'
        ]);

        //用户余额记录
        $user_balance = [];
        $user_balance['user_id'] = $item->id;
        $user_balance['amount'] = $amount = $request->input('amount');
        $user_balance['operate_type'] = $request->input('operate_type');
        $user_balance['source'] = $request->input('source');
        $user_balance['remark'] = $request->input('remark');

	if ($amount && $user_balance['operate_type'] == 2) $amount *= -1;

        DB::beginTransaction();
        try {
            UserBalance::create($user_balance);
            $item->increment('current_balance', $amount);  //总收益 & 余额 ++
            $item->increment('balance', $amount);

            DB::commit();
            return response()->json(['code' => 0, 'message' => '余额增减成功!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 3, 'message' => '余额增减发生异常!']);
        }
    }


    /**
     *  提现申请 - 合伙人
     */
    /*public function cash_partner(Request $request)
    {
        $builder = IncomeCash::with(['user' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc');

        //合伙人
        $builder->whereHas('user', function ($query) {
            $query->where('role', 3);
        });

        //手机号
        if ($search_phone = trim($request->input('search_phone'))) {
            $builder->whereHas('user', function ($query) use ($search_phone) {
                $query->where('mobile', 'like', '%' . $search_phone . '%');
            });
        }

        //昵称
        if ($search_name = trim($request->input('search_name'))) {
            $builder->whereHas('user', function ($query) use ($search_name) {
                $query->where('nickname', 'like', '%' . $search_name . '%');
            });
        }

        //apply_status
        if ($apply_status = trim($request->input('apply_status'))) {
            $builder->where('apply_status', $apply_status);
        }


        //提现金额
        if ($cash_amount = trim($request->input('cash_amount'))) {
            $pick_mod = $request->input('pick_mod');
            $mod = '>';
            if ($pick_mod > 0) {
                if ($pick_mod == 2) {
                    $mod = '=';
                } elseif ($pick_mod == 3) {
                    $mod = '<';
                }
                $builder->where('cash_amount', $mod, $cash_amount);
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

        $data = $builder->paginate(10);

        return view('income.cash_partner_index', [
            'data' => $data,
            'income_cash_state' => config('constants.income_cash_state')
        ]);
    }*/

    /*public function cash_partner_show($id)
    {
        return $this->cash_show($id);
    }*/

    /**
     *  合伙人提现-已转账记账
     *  1.平台收益表(income)记录支出 2.提现申请表(income_cash)变更状态 3.用户提现变更
     *  余额在用户申请时，已扣除，无需操作
     *
     */
    /*public function cash_partner_log(Request $request)
    {
        $id = $request->input('id');
        $item = IncomeCash::find($id);

        if ($item == null || $item->apply_status !== 1) {
            return response()->json(['code' => 1, 'message' => '非待处理状态!']);
        }

        $this->validate($request, [
            'remark' => 'required|min:2|max:255',
        ], [], [
            'remark' => '备注',
        ]);

        //平台收益表
        $income = [];
        $income['user_id'] = $item->user_id;
        $income['income_type'] = 6; //提现
        $income['log_type'] = 2; //支出
        $income['remark'] = $remark = $request->input('remark');
        $income['amount'] = $amount = $request->input('amount');
        $income['pay_mod'] = $request->input('pay_mod');
        $income['total_amount'] = get_platform_current_amount() - $income['amount']; //计算当前总金额

        //状态变更
        $income_cash = [];
        $income_cash['apply_status'] = 3;

        DB::beginTransaction();
        try {
            Income::create($income);
            $item->update($income_cash);
            $item->user->increment('cash_amount', $amount);

            DB::commit();
            return response()->json(['code' => 0, 'message' => '记账成功!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 3, 'message' => '记账发生异常!']);
        }
    }*/

    /**
     * 合伙人提现-驳回    1.用户余额 user   2.用户余额记录 user_balance类型为 8退款
     *
     */
    /*public function cash_refuse(Request $request)
    {
        $id = $request->input('id');
        $item = IncomeCash::find($id);

        if ($item == null || $item->apply_status !== 1) {
            return response()->json(['code' => 1, 'message' => '非待处理状态!']);
        }

        $this->validate($request, [
            'refuse_reason' => 'required|min:2|max:255',
        ], [], [
            'refuse_reason' => '驳回原因',
        ]);


        $data = [];
        $data['apply_status'] = 2;
        $data['operator_at'] = date('Y-m-d H:i:s');
        $data['refuse_reason'] = $request->input('refuse_reason');


        $user_balance = [];
        $user_balance['user_id'] = $item->user_id;
        $user_balance['amount'] = $item->cash_amount;
        $user_balance['operate_type'] = 1;
        $user_balance['source'] = 8;

        $user = User::find($item->user_id);

        DB::beginTransaction();
        try {
            //驳回状态
            $item->update($data);
            //退款记录
            UserBalance::create($user_balance);
            //余额变更-退款
            $user->increment('current_balance', $item->cash_amount);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 1, 'message' => '驳回失败！']);
        }

        return response()->json(['code' => 0, 'message' => '已驳回!']);
    }*/

    /**
     * 平台收益-列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function platform(Request $request)
    {
        $builder = Income::with(['user' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc');

        //手机号
        if ($search_phone = trim($request->input('search_phone'))) {
            $builder->whereHas('user', function ($query) use ($search_phone) {
                $query->where('mobile', 'like', '%' . $search_phone . '%');
            });
        }

        //昵称
        if ($search_name = trim($request->input('search_name'))) {
            $builder->whereHas('user', function ($query) use ($search_name) {
		$query->where(function($query) use($search_name) {
		    $query->where('nickname', 'like', '%' . $search_name . '%')
			->orWhere('realname', 'like', '%' . $search_name . '%');
		});
            });
        }

        //角色
        if ($search_role = trim($request->input('search_role'))) {
            $builder->whereHas('user', function ($query) use ($search_role) {
                $query->where('role', $search_role);
            });
        }


        if ($log_type = trim($request->input('income_log_type'))) {
            $builder->where('log_type', $log_type);
        }

        if ($income_type = trim($request->input('income_type'))) {
            $builder->where('income_type', $income_type);
        }

        if ($pay_mod = trim($request->input('income_pay_type'))) {
            $builder->where('pay_mod', $pay_mod);
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //开始时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }
        $smsBalance = get_sms_balance();
        return view('income.platform_index', [
            'data' => $data,
            'smsBalance' => $smsBalance,
            'user_role' => config('constants.user_role'),
            'income_in_type' => config('constants.income_in_type'),
            'income_log_type' => config('constants.income_log_type'),
            'income_pay_type' => config('constants.income_pay_type')
        ]);
    }


    /**
     *  平台记账
     *  1.平台收益表 income
     */
    public function platform_log(Request $request)
    {
        $this->validate($request, [
            'log_type' => 'required',
            'pay_mod' => 'required',
            'amount' => 'required|numeric',
            'income_type' => 'required',
            'remark' => 'required|min:2|max:255',
        ], [], [
            'remark' => '备注',
            'amount' => '金额',
            'log_type' => '记录类型',
            'income_type' => '来源',
            'pay_mod' => '支付方式'
        ]);

        //平台收益表
        $income = [];
        $income['income_type'] = $request->input('income_type');
        $income['log_type'] = $request->input('log_type');
        $income['remark'] = $request->input('remark');
        $income['amount'] = $request->input('amount');
        $income['pay_mod'] = $request->input('pay_mod');
        $income['total_amount'] = $income['log_type'] == 1 ? get_platform_current_amount() + $income['amount'] : get_platform_current_amount() - $income['amount'];

        if (Income::create($income)) {
            return response()->json(['code' => 0, 'message' => '平台记账成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '平台记账失败!']);
        }
    }


    public function platform_show($id)
    {
        $income = Income::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'order' => function ($query) {
                $query->withTrashed();
            }
        ])->where('id', $id)->first();

        if ($income == null)
            abort(404, '不存在');

        return view('income.platform_show', [
            'income' => $income,
            'income_in_type' => config('constants.income_in_type'),
            'income_log_type' => config('constants.income_log_type'),
            'income_pay_type' => config('constants.income_pay_type'),
            'order_belong_category' => config('constants.order_belong_category')
        ]);
    }


    /**
     * 收益比例维护
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function scale_index()
    {
        $data = IncomeScale::all();
        return view('income.scale_index', ['data' => $data, 'income_scale_keys' => config('constants.income_scale_keys')]);
    }


    public function scale_add()
    {
        $income_scale_keys = config('constants.income_scale_keys');
        return view('income.scale_add', ['income_scale_keys' => $income_scale_keys]);
    }

    public function scale_store(Request $request)
    {
        $data = $request->all();

        $this->validate($request, [
            'key' => 'required|unique:income_scale,key,NULL,id,deleted_at,NULL',
            'p_scale' => 'numeric',
            't_scale' => 'numeric',
            'a_scale' => 'numeric'
        ], [], [
            'key' => '此收益类型',
            'p_scale' => '平台',
            't_scale' => '指导师',
            'a_scale' => '提问人'
        ]);

        $val = [];
        $val['p_scale'] = $data['p_scale'];
        $val['t_scale'] = $data['t_scale'];
        $val['a_scale'] = $data['a_scale'];

        $data['value'] = serialize($val);

        IncomeScale::create($data);
        return redirect()->route('income.scale');
    }

    public function scale_edit($id)
    {
        $scale = IncomeScale::find($id);
        if ($scale == null)
            abort(404, '查找失败！');
        $income_scale_keys = config('constants.income_scale_keys');
        return view('income.scale_edit', ['income_scale_keys' => $income_scale_keys, 'scale' => $scale]);
    }

    public function scale_update(Request $request, $id)
    {
        $scale = IncomeScale::find($id);
        if ($scale == null)
            abort(404, '查找失败！');

        $data = $request->all();

        $this->validate($request, [
            'key' => 'required|unique:income_scale,key,' . $id . ',id,deleted_at,NULL',
        ], [], [
            'key' => '此收益类型',
        ]);

        $val = [];
        $val['p_scale'] = $data['p_scale'];
        $val['t_scale'] = $data['t_scale'];
        $val['a_scale'] = $data['a_scale'];

        $data['value'] = serialize($val);

        $scale->update($data);
        return redirect()->route('income.scale');
    }


    /**
     * 积分列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function point(Request $request)
    {
        $builder = User::with('c_province', 'c_city');

        //手机号
        if ($search_phone = trim($request->input('search_phone'))) {
            $builder->where('mobile', 'like', '%' . $search_phone . '%');
        }

        //昵称
        if ($search_name = trim($request->input('search_name'))) {
		$builder->where(function($query) use($search_name) {
		    $query->where('nickname', 'like', '%' . $search_name . '%')
			->orWhere('realname', 'like', '%' . $search_name . '%');
		});
        }

        //角色
        if ($search_role = trim($request->input('search_role'))) {
            $builder->where('role', $search_role);
        }

        //积分值
        if ($score = trim($request->input('score'))) {
            $pick_mod = $request->input('pick_mod');
            $mod = '>';
            if ($pick_mod > 0) {
                if ($pick_mod == 2) {
                    $mod = '=';
                } elseif ($pick_mod == 3) {
                    $mod = '<';
                }
                $builder->where('score', $mod, $score);
            }
        }

        // 省
        if ($province = trim($request->input('province'))) {
            $builder->where('province', $province);
        }

        //市
        if ($province = trim($request->input('city'))) {
            $builder->where('city', $province);
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //结束时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        $data = $builder->orderBy('user.id', 'desc')->paginate(10);

        return view('income.point_index', ['data' => $data, 'user_role' => config('constants.user_role')]);
    }

    /**
     * 修改积分-ajax
     *
     */
    public function point_update(Request $request)
    {
        $id = $request->input('id');
        $point_value = $request->input('point_value');
        $remark = $request->input('remark');

        $user = User::find($id);
        if ($user == null)
            return response()->json(['code' => 1, 'message' => '不存在的账号!']);

        $this->validate($request, [
            'point_value' => 'required|numeric',
            'remark' => 'required|min:2|max:120'
        ], [], [
            'point_value' => '增加的积分值',
            'remark' => '理由'
        ]);

        DB::beginTransaction();
        try {
            //更新总积分
            $user->increment('score', $point_value);
            //记录积分更改记录
            $log = [];
            $log['user_id'] = $user->id;
            $log['point_value'] = $point_value;
            $log['source'] = 9;
            $log['move_way'] = 1;
            $log['remark'] = $remark;
            UserPoint::create($log);
            DB::commit();
            return response()->json(['code' => 0, 'message' => '积分操作成功!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 2, 'message' => '积分操作发生异常!']);
        }
    }

    /**
     * 积分清空  1.清空user - score字段  2.user_point 记录 清空
     */
    public function point_empty()
    {
        $user = User::lists('score', 'id')->toArray();

        $user_point = [];
        $i = 0;
        foreach ($user as $k => $v) {
            if ($v > 0) {
                $user_point[$i]['user_id'] = $k;
                $user_point[$i]['point_value'] = $v;
                $user_point[$i]['source'] = 11;
                $user_point[$i]['move_way'] = 2;
                $user_point[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        DB::beginTransaction();
        try {
            UserPoint::insert($user_point);
            DB::table('user')->update(['score' => 0]);
            DB::commit();
            return response()->json(['code' => 0, 'message' => '积分清零成功!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 1, 'message' => '积分清零失败!']);
        }
    }


    /**
     * 积分详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function point_show($id)
    {
        $user = User::with(['user_point' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($id);

        if ($user == null)
            abort(404, '查找失败！');

        return view('income.point_show', [
            'user' => $user,
            'user_role' => config('constants.user_role'),
            'income_point_source' => config('constants.income_point_source')
        ]);
    }


}
