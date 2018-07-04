<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\WechatPush;

class WechatPushController extends Controller
{
	private $_rules = [
			'title' => 'required',
			'url' => 'required|url',
			'image_url' => 'required',
			'description' => 'required',			
			'ymd' => 'required',
			'hours' => 'required|integer',
	];
	
	private $_customAttributes = [
			'title' => '文章标题',
			'url' => '文章链接',
			'image_url' => '图片',
			'description' => '文章简介',
			'ymd' => '发送日期',
			'hours' => '发送时间',
	];
	
    public function index(Request $request)
    {
    	$words = WechatPush::select('*')
    	        	->orderBy('id','desc')->get();
    	
        return view('wechat_push.index', ['words' => $words]);
    }

    public function create(){
    	return view('wechat_push.create');
    }
   
    public function store(Request $request){
    	$validator = $this->validate($request, $this->_rules, [], $this->_customAttributes);
    	if ($validator != null && $validator->fails()) {
    		return redirect('wechat_push.create')
	    		->withErrors($validator)
	    		->withInput();
    	}
    	$data = array();
    	$data['title'] = $request->input('title');
    	$data['url'] = $request->input('url');
    	$data['picurl'] =  $request->input('image_url');
    	$data['description'] = $request->input('description');
    	$data['push_time'] = $request->input('ymd')."_".$request->input('hours');
    	$data['created_at'] = time();
    	$data['updated_at'] = time();
    	WechatPush::create($data);
    	return redirect()->route('wechat_push.index');
    }
    
    /** 删除*/
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $word = WechatPush::find($id);

        if (!$word) {
            return response()->json(['code' => 1, 'message' => '不存在的该留言!']);
        }

        $word->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }
    
    public function showLove(){
        $instance = \App\Models\Tooler::getByType(\App\Models\Tooler::TYPE_LOVE_BG);
        return view('wechat_push.show-lovebg', ['instance' => (array)$instance['content']]);
    }
    
    public function updateLove(Request $request){
         $data = array(
             'base64' =>   $request->input('base64'),
             'name_color' =>  $request->input('name_color')
         );

         \App\Models\Tooler::lovebgMerge((json_encode($data)) );
         return redirect()->route('wechat_push.showLove');
    }
}
