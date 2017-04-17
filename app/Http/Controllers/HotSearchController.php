<?php
/**
 *  热门搜索管理
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\HotSearch;

class HotSearchController extends Controller
{
    /** 列表 */
    public function index(Request $request)
    {
        $builder = HotSearch::select();

        if ($s_type = trim($request->input('type'))) {
            $builder->where('type',$s_type);
        }

        $data = $builder->orderBy('id','desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('hot_search.index', ['data' => $data,'type'=>config('constants.hot_search_type')]);
    }

    /** 添加 */
    public function create()
    {
        return view('hot_search.create',['type'=>config('constants.hot_search_type')]);
    }

    /** 添加-保存 */
    public function store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));

        $this->validate($request, [
            'title' => 'required|min:2|max:20|unique:hot_search,title,NULL,id,deleted_at,NULL,type,'.$request->input('type'),
            'sort'  => 'required|numeric',
            'type'  => 'required'
        ], [], [
            'title' => '名称',
            'sort' => '排序',
            'type' => '所属类型'
        ]);

        HotSearch::create($request->all());
        return redirect()->route('hot_search');
    }

    /** 编辑 */
    public function edit($id)
    {
        $data = HotSearch::find($id);
        if ($data == null)
            abort(404, '查找失败！');
        return view('hot_search.edit', ['data'=>$data,'type'=>config('constants.hot_search_type')]);
    }

    /**编辑 - 保存*/
    public function update(Request $request,$id)
    {
        $obj = HotSearch::find($id);
        if ($obj == null)
            abort(404, '查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $this->validate($request, [
            'title' => 'required|min:2|max:20|unique:hot_search,title,' . $id . ',id,deleted_at,NULL,type,'.$request->input('type'),
            'sort'  => 'required|numeric',
            'type'  => 'required'
        ], [], [
            'title' => '名称',
            'sort' => '排序',
            'type' => '所属类型'
        ]);

        $obj->update($request->all());
        return redirect()->route('hot_search');
    }

    public function delete(Request $request)
    {
        $obj = HotSearch::find($request->input('id'));

        if (!$obj) {
            return response()->json(['code' => 1, 'message' => '不存在!']);
        }

        $obj->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }


}
