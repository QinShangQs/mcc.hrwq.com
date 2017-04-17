<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\UploadFile;

class CarouselController extends Controller
{
    private $_rules = [
        'title' => 'required',
        'use_type' => 'required',
        'image_url' => 'required',
        'redirect_type' => 'required',
        'sort' => 'required|integer',
        'redirect_url' => 'required_if:redirect_type,2',
        'redirect_content' => 'required_if:redirect_type,3',
    ];

    private $_customAttributes = [
        'title' => '名称',
        'use_type' => '显示位置',
        'image_url' => '轮播图片',
        'redirect_type' => '跳转类型',
        'sort' => '排序',
        'redirect_url' => '跳转地址',
        'redirect_content' => '图片静态内容',
    ];

    public function index()
    {
        $data = Carousel::orderBy('use_type','asc')->orderBy('sort','desc')->get();
        return view('carousel.index', ['datas' => $data, 'type' => config('constants.carousel_redirect_type'),'use_type'=>config('constants.carousel_type')]);
    }


    public function create()
    {
        return view('carousel.create', ['type' => config('constants.carousel_redirect_type'),'use_type'=>config('constants.carousel_type')]);
    }

    public function store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();

        $this->validate($request, $this->_rules, [], $this->_customAttributes);

        $data['add_uid']         = $data['update_uid'] = $request->user()->id;
        $data['image_thumb_url'] = thumb_uri($data['image_url'], config('upload.carousel.thumbPrefix'));

        Carousel::create($data);
        return redirect()->route('carousel');
    }

    public function edit($id)
    {
        $data = Carousel::find($id);
        if ($data == null)
            abort(404, '未找到该轮播图！');
        return view('carousel.edit', ['data' => $data, 'type' => config('constants.carousel_redirect_type'),'use_type'=>config('constants.carousel_type')]);
    }

    public function update(Request $request, $id)
    {
        $article = Carousel::find($id);
        if ($article == null)
            abort(404, '未找到该轮播图！');
        $request->merge(array_map('trim', $request->all()));

        $data = $request->all();

        $this->validate($request, $this->_rules, [], $this->_customAttributes);

        $data['update_uid'] = $request->user()->id;
        $data['image_thumb_url'] = thumb_uri($data['image_url'], config('upload.carousel.thumbPrefix'));


        $article->update($data);
        return redirect()->route('carousel');
    }


    public function delete(Request $request)
    {
        $data = Carousel::find($request->input('id'));
        if (!$data) {
            return response()->json(['code' => 1, 'message' => '未找到该轮播图!']);
        }

        $data->delete();

        if ($data->trashed()) {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        }
    }

    /**
     * web uploader  server process ,POST
     */
    public function uploadImages()
    {
        $cfg = config('upload.carousel');

        $upload = new UploadFile();// 实例化上传类
        $upload->savePath = 'uploads/carousel/';// 设置附件上传目录
        $upload->thumb = true;//是否开启图片文件缩略图
        $upload->thumbPrefix = $cfg['thumbPrefix'];
        $upload->thumbMaxWidth = $cfg['thumbMaxWidth'];
        $upload->thumbMaxHeight = $cfg['thumbMaxHeight'];
        $upload->maxSize = $cfg['maxSize'];

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


}
