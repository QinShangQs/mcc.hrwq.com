<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\VipTv;
use Illuminate\Http\Request;

set_time_limit(0);

class VipTvController extends Controller {

    // 和会员激活码列表
    public function index(Request $request) {
        $builder = VipTv::select('vip_tv.*')->orderBy('id', 'desc')->with('user');
        //和会员激活码
        if ($code = trim($request->input('code'))) {
            $builder->where('code', 'like', '%' . $code . '%');
        }
        //是否被激活 1否 2是
        if ($is_activated = trim($request->input('is_activated'))) {
            $builder->where('is_activated', '=', $is_activated);
        }
        //开始时间
        if ($s_time = trim($request->input('s_time'))) {
            $builder->where('updated_at', '>=', $s_time);
        }
        //结束时间
        if ($e_time = trim($request->input('e_time'))) {
            $builder->where('updated_at', '<=', $e_time);
        }

        if ($nickname = trim($request->input('nickname'))) {
            $builder->whereHas('user', function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            });
        }

        if ($request->input('export')) {
            $data = [
                ['ID', '直播激活码',
                    '是否被激活', '导入时间',
                    '用户昵称', '激活时间'
                ],
            ];
            $builder->chunk(100, function($codes) use(&$data) {
                if ($codes)
                    foreach ($codes as $code) {
                        $data[] = @[
                            $code->id,
                            $code->code,
                            ($code->is_activated == 1 ? "否" : "是"),
                            $code->created_at,
                            @$code->user->nickname,
                            (!empty($code->user) ? $code->updated_at : '')
                        ];
                    }
            });
            return $this->export('直播激活码列表', $data);
        }
        $vips = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $vips->appends($input, $value);
            }
        }
        return view('vip.tv-index', ['vips' => $vips]);
    }

    private function export($title, $data) {
        return Excel::create($title . '-' . date('Ymd'), function($excel) use($title, $data) {
                    $excel->sheet(str_replace('列表', '', $title), function($sheet) use($data) {
                        $sheet->rows($data);
                    });
                })->download('xlsx');
    }

    public function create() {
        return view('vip.tv-create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'code' . 1 => 'required'
                ], [], [
            'code' . 1 => '激活码' . 1
        ]);
        for ($i = 1; $i <= 10; $i++) {
            if ($request->input('code' . $i) && !empty($request->input('code' . $i))) {
                $this->_save_code($request->input('code' . $i));
            }
        }
        return redirect()->route('vip.tv_index');
    }

    /** 删除激活码 */
    public function delete(Request $request) {
        $id = $request->input('id');

        $del_ids = explode(',', trim($id, ','));

        if (!VipTv::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }
    }

    //Excel文件导入功能
    public function import() {
        return view('vip.tv-import');
    }

    //Excel文件导入功能
    public function do_import(Request $request) {
        $filePath = $request->file('file');
        if ($filePath == null) {
            return redirect()->back()->withInput()->withErrors('请选择EXCEL文件！');
        } else {
            Excel::load($filePath, function ($reader) {
                $data = $reader->getSheet(0)->toArray();
                foreach ($data as $key => $value) {
                    if ($key > 0) {
                        $this->_save_code($value[0]);
                    }
                }
            }); //解决导出来的文件无法导入的问题     },'utf-8');
            return redirect()->route('vip.tv_index');
        }
    }

    //排重，最后优化批量插入  insert
    private function _save_code($code) {
        if (!VipTv::withTrashed()->where('code', $code)->first()) {
            $vip = new VipTv();
            $vip->code = $code;
            $vip->is_activated = 1;
            $vip->save();
        }
    }

}
