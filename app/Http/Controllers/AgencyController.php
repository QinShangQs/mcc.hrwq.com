<?php
/**
 * 课程类别管理
 */
namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AgencyController extends Controller
{
    /** 课程类别管理列表 */
    public function index(Request $request)
    {
        $agencys = Agency::select('agency.*')->orderBy('agency.id','desc')->get();
        return view('agency.index', ['agencys' => $agencys]);
    }

    /** 课程类别详情 */
    public function show($id)
    {
        $agency = Agency::find($id);
        return view('agency.show',['agency'=>$agency]);
    }

    /** 课程类别添加 */
    public function create()
    {
        return view('agency.create');
    }

    /** 课程类别-保存 */
    public function store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $this->validate($request, [
            'agency_name' => 'required|min:2|max:20|unique:agency,agency_name,NULL,id,deleted_at,NULL',
            'agency_title'  => 'required'
        ], [], [
            'agency_name' => '类别名',
            'agency_title' => '类别介绍',
        ]);

        Agency::create($request->all());
        return redirect()->route('agency.index');
    }

    /** 课程类别编辑 */
    public function edit($id)
    {
        $agency = Agency::find($id);
        if ($agency == null)
            abort(404, '课程类别查找失败！');
        return view('agency.edit', ['agency' => $agency]);
    }

    /** 课程类别编辑 - 保存*/
    public function update(Request $request,$id)
    {
        $agency = Agency::find($id);
        if ($agency == null)
            abort(404, '标签查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $this->validate($request, [
            'agency_name' => 'required|min:2|max:20|unique:agency,agency_name,' . $id . ',id,deleted_at,NULL',
            'agency_title'  => 'required'
        ], [], [
            'agency_name' => '类别名',
            'agency_title' => '类别介绍',
        ]);

        $agency->update($request->all());
        return redirect()->route('agency.index');
    }

    /** 课程类别 - 删除*/
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $agency = Agency::find($id);

        if (!$agency) {
            return response()->json(['code' => 1, 'message' => '不存在的课程类别!']);
        }

        $agency->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }
}
