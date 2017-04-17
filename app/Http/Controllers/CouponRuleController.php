<?php
/**
 *  优惠券获取规则
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Agency;
use App\Models\CouponRule;

class CouponRuleController extends Controller
{
    private $_rule = [
        'name' => 'required|min:2|max:20|unique:coupon_rule,name,NULL,id,deleted_at,NULL',
        'rule_id'  => 'required',
        'agency_id' => 'required_if:rule_id,1',
        'bouns'     => 'required_if:rule_id,1',
        'coupon_id' => 'required'
    ];

    private $_message = [
        'agency_id.required_if' => '当 获取规则 为 邀请注册 时 可领取红包的分类 不能为空',
        'bouns.required_if' => '当 获取规则 为 邀请注册 时 获赠的现金红包 不能为空',
    ];

    private $_customAttributes = [
        'name' => '名称',
        'rule_id' => '获取规则',
        'coupon_id' => '优惠券模板'
    ];

    /** 列表 */
    public function index()
    {
        $agency = $this->_get_agency();

        $builder = CouponRule::withTrashed();

        $coupon_rules = $builder->paginate(10);
        foreach ($coupon_rules as $rule) {
            $rule->coupons = Coupon::whereIn('id', explode(',', $rule->coupon_id))->get();
        }
        return view('coupon_rule.index', [
            'coupon_rules' => $coupon_rules,
            'type'=>config('constants.coupon_type'),
            'coupon_get_rule'=>config('constants.coupon_get_rule'),
            'agency'=>$agency,
            'coupon_use_scope'=>config('constants.coupon_use_scope')
         ]);
    }

    /** 新增 */
    public function create()
    {
        $agency = $this->_get_agency();
        $coupon = $this->_get_coupon();

        return view('coupon_rule.create',[
            'coupon_get_rule' => config('constants.coupon_get_rule'),
            'agency'=>$agency,
            'coupon'=>$coupon
        ]);
    }

    /** 新增-保存 */
    public function store(Request $request)
    {
        $rule = $this->_rule;

        if ($request->input('bouns')) {
            $rule['bouns'] .= '|numeric';
        }
        $request->merge(['coupon_id'=>implode(',', $request->input('coupon_id'))]);
        $this->validate($request,$rule, $this->_message,$this->_customAttributes);
        $request->merge(array_map('trim', $request->all()));

        CouponRule::create($request->all());
        return redirect()->route('coupon_rule');
    }

    public function show($id)
    {
        $coupon_rule = CouponRule::find($id);
        if ($coupon_rule == null)
            abort(404, '不存在或未开启状态');

        $coupon_rule->coupons = Coupon::whereIn('id', explode(',', $coupon_rule->coupon_id))->get();

        $agency = $this->_get_agency();
        $coupon = $this->_get_coupon();

        return view('coupon_rule.show',[
            'coupon_rule'=>$coupon_rule,
            'coupon_get_rule' => config('constants.coupon_get_rule'),
            'agency'=>$agency,
            'coupon'=>$coupon
        ]);
    }

    public function edit($id)
    {
        $coupon_rule = CouponRule::find($id);
        if ($coupon_rule == null)
            abort(404, '不存在或未开启状态');

        $coupon_rule->coupon_id = explode(',', $coupon_rule->coupon_id);
        $agency = $this->_get_agency();
        $coupon = $this->_get_coupon();

        return view('coupon_rule.edit',[
            'coupon_rule'=>$coupon_rule,
            'coupon_get_rule' => config('constants.coupon_get_rule'),
            'agency'=>$agency,
            'coupon'=>$coupon
        ]);
    }


    public function update(Request $request, $id)
    {
        $coupon_rule = CouponRule::find($id);
        if ($coupon_rule == null)
            abort(404, '不存在或未开启状态！');

        $rule = $this->_rule;
        $rule['name'] = 'required|min:2|max:20|unique:coupon_rule,name,'.$id.',id,deleted_at,NULL';

        if ($request->input('bouns')) {
            $rule['bouns'] .= '|numeric';
        }
        $request->merge(['coupon_id'=>implode(',', $request->input('coupon_id'))]);
        $request->merge(array_map('trim', $request->all()));

        $this->validate($request,$rule, $this->_message,$this->_customAttributes);

        $coupon_rule->update( $request->all());
        return redirect()->route('coupon_rule');
    }

    /**
     * 规则关闭
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $item = CouponRule::find($id);
        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '查找失败!']);
        }

        $item->delete();
        if ($item->trashed()) {
            return response()->json(['code' => 0, 'message' => '关闭成功!']);
        } else {
            return response()->json(['code' => 3, 'message' => '关闭失败!']);
        }
    }

    /**
     * 规则开启
     */
    public function restore(Request $request)
    {
        $id = $request->input('id');
        $item = CouponRule::withTrashed()->whereId($id)->first();
        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '查找失败!']);
        }

        if ($item->restore()) {
            return response()->json(['code' => 0, 'message' => '开启成功!']);
        } else {
            return response()->json(['code' => 3, 'message' => '开启失败!']);
        }
    }

    /** 获取分类 */
    private function _get_agency()
    {
        return Agency::lists('agency_name','id')->toArray();
    }

    /** 获取优惠券模板 */
    private function _get_coupon()
    {
        return Coupon::lists('name','id')->toArray();
    }



}
