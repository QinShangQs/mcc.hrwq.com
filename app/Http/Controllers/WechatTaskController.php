<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\WechatTemplateTask;
use Wechat;

class WechatTaskController extends Controller {

    private $_rules = [
        'template_id' => 'required',
        'template_name' => 'required',
        'url' => 'required|url',
        'content' => 'required',
        'user_type' => 'required',
        'task_run_time' => 'required',
    ];
    private $_customAttributes = [
        'template_id' => '模版ID',
        'template_name' => '模版名称',
        'url' => '链接地址',
        'content' => 'JSON内容',
        'user_type' => '用户类型',
        'task_run_time' => '发送时间',
    ];

    public function index(Request $request) {
        $words = WechatTemplateTask::select('*')
                        ->orderBy('id', 'desc')->get();
        return view('wechat_task.index', ['words' => $words, 'user_types' => config('constants.user_types')]);
    }

    public function create() {
        $templates = Wechat::notice()->getPrivateTemplates();
        return view('wechat_task.create', ['templates' => $templates['template_list'],
            'user_types' => config('constants.user_types'),
            'templatesJSON' => json_encode($templates['template_list'])]);
    }

    public function sendTest(Request $request) {
        $data = json_decode($request->input('content'), true);
        if (empty($data)) {
            return response()->json(['code' => 1, 'message' => 'JSON格式错误']);
        }
        if (empty($request->input('openid'))) {
            return response()->json(['code' => 2, 'message' => '微信ID不能为空']);
        }

        try {
            $notice = Wechat::notice();
            $messageId = $notice->send([
                'touser' => $request->input('openid'),
                'template_id' => $request->input('template_id'),
                'url' => $request->input('url'),
                'topcolor' => $request->input('topcolor'),
                'data' => $data,
            ]);
            return response()->json(['code' => $messageId['errmsg'] == 'ok' ? 0 : '400', 'message' => $messageId['errmsg']]);
        } catch (\Exception $ex) {
            return response()->json(['code' => 500, 'message' => $ex->getMessage()]);
        }
    }

    public function store(Request $request) {
        $validator = $this->validate($request, $this->_rules, [], $this->_customAttributes);
        if ($validator != null && $validator->fails()) {
            return redirect('wechat_task.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        if (empty(json_decode($request->input('content'), true))) {
            return redirect('wechat_task.create')
                            ->withErrors(['JSON格式错误']);
        }

        $data = array();
        $data['wechat_appid'] = config('wechat.app_id');
        $data['template_id'] = $request->input('template_id');
        $data['template_name'] = $request->input('template_name');
        $data['url'] = $request->input('url');
        $data['topcolor'] = $request->input('topcolor', '');
        $data['content'] = base64_encode($request->input('content'));
        $data['task_run_time'] = $request->input('task_run_time');
        $data['openid'] = $request->input('openid');
        $data['user_type'] = $request->input('user_type');
        $data['task_type'] = WechatTemplateTask::TASK_TYPE_ONLYONE;
        $data['task_status'] = WechatTemplateTask::TASK_STATUS_WAITING;

        WechatTemplateTask::create($data);
        return redirect()->route('wechat_task.index');
    }

    public function detail($id){
        $word = WechatTemplateTask::find($id);
        if(empty($word)){
            abort(404);
        }
        return view('wechat_task.detail', ['word' => $word, 'user_types' => config('constants.user_types')]);
    }
    
    /** 删除 */
    public function delete(Request $request) {
        $id = $request->input('id');
        $word = WechatTemplateTask::find($id);

        if (!$word) {
            return response()->json(['code' => 1, 'message' => '数据不存在!']);
        }

        $word->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }

}
