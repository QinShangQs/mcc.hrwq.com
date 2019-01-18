<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;

/**
 * 广告管理
 */
class AdController extends Controller {

    private $_rules = [
        'title' => 'required',
        'ad_type' => 'required',
        'image_url' => 'required_if:ad_type,1',
        'redirect_url' => 'required',
    ];
    private $_customAttributes = [
        'title' => '名称',
        'ad_type' => '显示位置',
        'image_url' => '图片',
        'redirect_url' => '跳转地址',
    ];

    public function index() {
        $data = Ad::orderBy('id', 'desc')->get();

        return view('ad.index', ['datas' => $data, 'ad_types' => Ad::AD_TYPES]);
    }

    public function create() {
        return view('ad.create', ['ad_types' => Ad::AD_TYPES]);
    }

    public function store(Request $request) {
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();
        $this->validate($request, $this->_rules, [], $this->_customAttributes);
        
        if ($data['ad_type'] == Ad::AD_TYPE_IMAGE) {
            $data['display_url'] = thumb_uri($data['image_url'], config('upload.carousel.thumbPrefix'));
            unset($data['image_url']);
        } else {
            $data['display_url'] = $data['video_tran'];
            $data['video_original'] = $data['video_original'];
            unset($data['video_original']);
            unset($data['video_tran']);
            unset($data['video_free']);
            unset($data['file']);
        }
        $data['show_type'] = Ad::SHOW_TYPE_YES;
        Ad::create($data);
        return redirect()->route('ad');
    }

    public function isShow(Request $request) {
        $article = Ad::find($request->input('id'));
        if (!$article) {
            return response()->json(['code' => 1, 'message' => '未找到该广告!']);
        }

        $data = [];
        $data['show_type'] = $article->show_type == Ad::SHOW_TYPE_YES ? Ad::SHOW_TYPE_NO : Ad::SHOW_TYPE_YES;
        $article->update($data);

        return response()->json(['code' => 0, 'message' => '修改成功!']);
    }

    public function delete(Request $request) {
        $id = $request->input('id');
        $article = Ad::find($id);

        if (!$article) {
            return response()->json(['code' => 1, 'message' => '不存在该广告']);
        }

        $article->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }

}
