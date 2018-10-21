<?php
/**
 * 提现申请处理
 */

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCash;
use App\Models\MerchantPayLog;
use App\Models\User;
use App\Models\UserBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Wechat, DB;

class WithdrawController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 提现申请 - 指导师、普通用户
     */
    public function index(Request $request)
    {
        //指导师、普通用户
        $builder = IncomeCash::with(['user' => function ($query) {
            $query->withTrashed();
        }, 'user.c_province', 'user.c_city'])->orderBy('id', 'desc');
        //手机号
        if ($search_phone = trim($request->input('search_phone'))) {
            $builder->whereHas('user', function ($query) use ($search_phone) {
                $query->where('mobile', 'like', '%' . $search_phone . '%');
            });
        }
        // 省
        if ($province = trim($request->input('province'))) {
            $builder->whereHas('user', function ($query) use ($province) {
                $query->where('province', $province);
            });
        }
        //市
        if ($city = trim($request->input('city'))) {
            $builder->whereHas('user', function ($query) use ($city) {
                $query->where('city', $city);
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
        
        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }
        
        return view('income.cash_index', [
            'data' => $data,
            'user_role' => config('constants.user_role'),
            'income_cash_state' => config('constants.income_cash_state')
        ]);
    }

    public function show($id)
    {
        $data = IncomeCash::with(['user' => function ($query) {
            $query->withTrashed();
        }])->where('id', $id)->first();

        return view('income.cash_show', [
            'data' => $data,
            'user_role' => config('constants.user_role'),
            'income_cash_state' => config('constants.income_cash_state')
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 处理微信转账-自动记账
     */
    public function bulkApprove(Request $request)
    {
        $merchantPay = Wechat::merchant_pay();
        $ids = explode(',', trim($request->input('id'), ','));
        $incomeCashes = IncomeCash::whereIn('id', $ids)->where('apply_status', 1)->get();

        $currentCashAmount = get_platform_current_amount();
        foreach ($incomeCashes as $incomeCash) {
            DB::beginTransaction();
            try {
                $currentCashAmount -= $incomeCash->cash_amount;
                //平台收支记录
                $income = new Income();
                $income->user_id = $incomeCash->user_id;
                $income->income_type = 6; //提现
                $income->log_type = 2; //支出
                $income->remark = '微信企业付款';
                $income->amount = $incomeCash->cash_amount;
                $income->pay_mod = 3;
                $income->total_amount = $currentCashAmount;
                $income->save();
                //微信企业支付
                $merchantPayData = [
                    'partner_trade_no' => '07' . date('YmdHis') . rand(1000, 9999),
                    'openid' => $incomeCash->user->openid,
                    'check_name' => 'NO_CHECK',  //文档中有三种校验实名的方法 NO_CHECK OPTION_CHECK FORCE_CHECK
                    're_user_name' => '',
                    'amount' => $incomeCash->cash_amount * 100,
                    'desc' => '企业付款',
                    'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
                ];
                $result = $merchantPay->send($merchantPayData);
                if ($result->return_code != 'SUCCESS' || $result->result_code != 'SUCCESS') {
                    DB::rollBack();
                    return response()->json(['code' => 3, 'message' => $result->err_code.' '.$result->err_code_des]);
                }
                MerchantPayLog::create($merchantPayData);
                //处理提现记录与用户提现总额
                $incomeCash->user->increment('cash_amount', $incomeCash->cash_amount);
                $incomeCash->update(['apply_status' => 3, 'operated_at'=>(string)Carbon::now()]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['code' => 3, 'message' => '转账发生异常!']);
            }
        }
        return response()->json(['code' => 0, 'message' => '转账处理成功!']);
    }

    public function approve()
    {
        $merchantPay = Wechat::merchant_pay();
        $incomeCash = IncomeCash::where('apply_status', 1)->find(intval(request('id')));
        if($incomeCash==null){
            return response()->json(['code'=>1, 'message'=>'提现申请查询失败！']);
        }
        $currentCashAmount = get_platform_current_amount();
        DB::beginTransaction();
        try {
            $currentCashAmount -= $incomeCash->cash_amount;
            //平台收支记录
            $income = new Income();
            $income->user_id = $incomeCash->user_id;
            $income->income_type = 6; //提现
            $income->log_type = 2; //支出
            $income->remark = '微信企业付款';
            $income->amount = $incomeCash->cash_amount;
            $income->pay_mod = 3;
            $income->total_amount = $currentCashAmount;
            $income->save();
            //微信企业支付
            $merchantPayData = [
                'partner_trade_no' => '07' . date('YmdHis') . rand(1000, 9999),
                'openid' => $incomeCash->user->openid,
                'check_name' => 'NO_CHECK',  //文档中有三种校验实名的方法 NO_CHECK OPTION_CHECK FORCE_CHECK
                're_user_name' => '',
                'amount' => $incomeCash->cash_amount *100,
                'desc' => '企业付款',
                'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
            ];
            $result = $merchantPay->send($merchantPayData);
            if ($result->return_code != 'SUCCESS' || $result->result_code != 'SUCCESS') {
                DB::rollBack();
                return response()->json(['code' => 3, 'message' => $result->err_code.' '.$result->err_code_des]);
            }
            $merchantPayData['income_cash_id'] = $incomeCash->id;
            $merchantPayData['user_id'] = $incomeCash->user_id;
            MerchantPayLog::create($merchantPayData);
            //处理提现记录与用户提现总额
            $incomeCash->user->increment('cash_amount', $incomeCash->cash_amount);
            $incomeCash->update(['apply_status' => 3, 'operated_at'=>(string)Carbon::now()]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 3, 'message' => '转账发生异常!']);
        }
        return response()->json(['code' => 0, 'message' => '提现处理成功!']);
    }

    public function reject()
    {
        //查询提现记录与用户信息
        $incomeCash = IncomeCash::find(intval(request('id')));
        if ($incomeCash == null || $incomeCash->apply_status !== 1) {
            return response()->json(['code' => 1, 'message' => '非待处理状态!']);
        }
        $user = User::find($incomeCash->user_id);
        if ($user == null) {
            return response()->json(['code' => 2, 'message' => '用户查询失败!']);
        }

        $this->validate(request(), [
            'refuse_reason' => 'required|min:2|max:255',
        ], [], [
            'refuse_reason' => '驳回原因',
        ]);

        $incomeCash->apply_status = 2;
        $incomeCash->operated_at = Carbon::now();
        $incomeCash->refuse_reason = request('refuse_reason');

        $userBalance = new UserBalance();
        $userBalance->user_id = $incomeCash->user_id;
        $userBalance->amount = $incomeCash->cash_amount;
        $userBalance->operate_type = 1;
        $userBalance->source = 8;

        DB::beginTransaction();
        try {
            //驳回状态
            $incomeCash->save();
            //退款记录
            $userBalance->save();
            //余额变更-退款
            $user->increment('current_balance', $incomeCash->cash_amount);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 1, 'message' => '提现申请驳回失败！']);
        }
        return response()->json(['code' => 0, 'message' => '提现申请已驳回!']);
    }
}
