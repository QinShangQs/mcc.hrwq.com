<?php
/**
 * 视频课程管理
 */
namespace App\Http\Controllers;

use App\Models\Vcourse;
use App\Models\Agency;
use App\Models\VcourseMark;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Qiniu\Auth as Qiniu_Auth;
use Qiniu\Storage\BucketManager;
use App\Library\UploadFile;
use DB;

class VcourseController extends Controller
{
    private $_rule = [
        'title' => 'required|min:2|max:15|unique:vcourse,title,NULL,id,deleted_at,NULL',
        'cover' => 'required',
        'agency_id' => 'required',
        'type' => 'required',
        'price' => 'required_if:type,2',
        'current_class' => 'integer',
        'total_class' => 'integer',
        'work' => 'required',
        'free_time' => 'integer',
        'video_original' => 'required',
        'video_tran' => 'required_with:video_original',
    ];

    private $_message = [
        'price.required_if' => '当 收费类别 为 收费 时 价格 不能为空',
        'video_original.required' => '课程视频还未上传',
        'video_tran.required_with' => '课程视频转码还未结束',
    ];

    private $_customAttributes = [
        'title' => '课程名称',
        'cover' => '图片',
        'agency_id' => '课程类别',
        'type' => '收费类别',
        'price' => '价格',
        'teacher' => '讲师',
        'teacher_intr' => '讲师简介',
        'current_class' => '当前课时',
        'total_class' => '总课时',
        'suitable' => '适合对象',
        'vcourse_obj' => '课程目标',
        'vcourse_des' => '课程简介',
        'work' => '课程作业',
        'free_time' => '试看时长',
    ];

    /** 课程列表 */
    public function index(Request $request)
    {
        //关联模型
        $builder = Vcourse::withTrashed()->select('vcourse.*', DB::raw('COUNT(order.id) as num'))->orderBy('vcourse.sort', 'desc')->with(['agency']);
        $builder->leftJoin('order', function ($join) {
            $join->on('vcourse.id', '=', 'order.pay_id')->where('pay_type', '=', '2');
        });

        //课程名称
        if ($title = trim($request->input('title'))) {
            $builder->where('title', 'like', '%' . $title . '%');
        }

        //课程类别
        if ($agency_id = trim($request->input('agency_id'))) {
            $builder->where('agency_id', '=', $agency_id);
        }

        //上线时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //上线时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        //讲师
        if ($teacher = trim($request->input('teacher'))) {
            $builder->where('teacher', 'like', '%' . $teacher . '%');
        }

        $builder->groupBy('vcourse.id');

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        $status_list = config('constants.status_list');

        return view('vcourse.index', ['data' => $data, 'agencyArr' => Agency::lists('agency_name', 'id'), 'status_list' => $status_list]);
    }

    /** 课程详情 */
    public function show($id)
    {
        $vcourse = Vcourse::withTrashed()->with(['agency'])->find($id);
        return view('vcourse.show', ['vcourse' => $vcourse, 'agencyArr' => Agency::lists('agency_name', 'id')]);
    }

    /** 课程添加 */
    public function create()
    {
        return view('vcourse.create', ['agencyArr' => Agency::lists('agency_name', 'id')]);
    }

    /** 课程-保存 */
    public function store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();
        $this->validate($request, $this->_rule, $this->_message, $this->_customAttributes);

        if ($data['type'] == '1') {
            unset($data['price']);
        }
        $data['bucket'] = config('qiniu.BUCKET_NAME_VIDEO');
        Vcourse::create(array_filter($data));
        return redirect()->route('vcourse.index');
    }

    /** 课程编辑 */
    public function edit($id)
    {
        $vcourse = Vcourse::withTrashed()->with(['agency'])->find($id);
        if ($vcourse == null)
            abort(404, '课程查找失败！');
        return view('vcourse.edit', ['vcourse' => $vcourse, 'agencyArr' => Agency::lists('agency_name', 'id')]);
    }

    /** 课程编辑 - 保存*/
    public function update(Request $request, $id)
    {
        $vcourse = Vcourse::find($id);
        if ($vcourse == null)
            abort(404, '标签查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();
        $rule = $this->_rule;
        $rule['title'] = 'required|min:2|max:15|unique:vcourse,title,' . $id . ',id,deleted_at,NULL';
        $this->validate($request, $rule, $this->_message, $this->_customAttributes);

        if ($data['type'] == '1') {
            unset($data['price']);
        }
        $data['bucket'] = config('qiniu.BUCKET_NAME_VIDEO');
        $vcourse->update(array_filter($data));
        return redirect()->route('vcourse.index');
    }

    /** 课程 - 下架*/
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $vcourse = Vcourse::find($id);

        if (!$vcourse) {
            return response()->json(['code' => 1, 'message' => '不存在的课程!']);
        }

        $vcourse->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }

    /** 发布课程 */
    public function release(Request $request)
    {
        $id = $request->input('id');
        $vcourse = Vcourse::find($id);

        if (!$vcourse) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $vcourse->status = 2;
        if ($vcourse->save()) {
            return response()->json(['code' => 0, 'message' => '发布成功!']);
        }

    }

    /** 下架课程 */
    public function off(Request $request)
    {
        $id = $request->input('id');
        $vcourse = Vcourse::find($id);

        if (!$vcourse) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $vcourse->status = 3;
        if ($vcourse->save()) {
            return response()->json(['code' => 0, 'message' => '下架成功!']);
        }

    }

    /** 上架课程 */
    public function on(Request $request)
    {
        $id = $request->input('id');
        $vcourse = Vcourse::find($id);

        if (!$vcourse) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $vcourse->status = 2;
        if ($vcourse->save()) {
            return response()->json(['code' => 0, 'message' => '上架成功!']);
        }

    }

    /** 作业列表 */
    public function task_list(Request $request)
    {
        //关联模型
        $builder = VcourseMark::with(['user', 'vcourse'])->whereMarkType('2')->orderBy('vcourse_mark.id', 'desc');

        //课程名称
        if ($title = trim($request->input('title'))) {
            $builder->whereHas('vcourse', function ($query) use ($title) {
                $query->where('title', 'like', '%' . $title . '%');
            });
        }

        //提交作业时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('vcourse_mark.created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //提交作业时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('vcourse_mark.created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('vcourse.task_list', ['data' => $data]);
    }

    /** 课程作业详情 */
    public function task_show($id)
    {
        $vcourseMark = VcourseMark::with(['user', 'vcourse'])->whereMarkType('2')->find($id);
        return view('vcourse.task_show', ['vcourseMark' => $vcourseMark]);
    }

    /** 课程作业 - 删除*/
    public function task_delete(Request $request)
    {
        $id = $request->input('id');

        $del_ids = explode(',', trim($request->input('id'), ','));

        if (!VcourseMark::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }
    }

    /** 笔记列表 */
    public function mark_list(Request $request)
    {
        //关联模型
        $builder = VcourseMark::with(['user', 'vcourse'])->whereMarkType('1')->orderBy('vcourse_mark.id', 'desc');

        //课程名称
        if ($title = trim($request->input('title'))) {
            $builder->whereHas('vcourse', function ($query) use ($title) {
                $query->where('title', 'like', '%' . $title . '%');
            });
        }

        //提交笔记时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('vcourse_mark.created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //提交笔记时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('vcourse_mark.created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        //私密or公开
        if ($visible = trim($request->input('visible'))) {
            $builder->where('visible', '=', $visible);
        }

        $data = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        $visible_list = config('constants.visible_list');

        return view('vcourse.mark_list', ['data' => $data, 'visible_list' => $visible_list]);
    }

    /** 课程笔记详情 */
    public function mark_show($id)
    {
        $vcourseMark = VcourseMark::with(['user', 'vcourse'])->whereMarkType('1')->find($id);
        $visible_list = config('constants.visible_list');
        return view('vcourse.mark_show', ['vcourseMark' => $vcourseMark, 'visible_list' => $visible_list]);
    }

    /** 课程笔记 - 删除*/
    public function mark_delete(Request $request)
    {
        $id = $request->input('id');

        $del_ids = explode(',', trim($request->input('id'), ','));

        if (!VcourseMark::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }
    }

    // 推荐管理列表
    public function recommend_list(Request $request)
    {
        $builder = Vcourse::select('vcourse.*')->where('recommend', '=', 2)->orderBy('vcourse.id', 'desc');

        $vcourse = $builder->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $vcourse->appends($input, $value);
            }
        }

        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        return view('vcourse.recommend_list', ['vcourse' => $vcourse, 'type_list' => $type_list, 'status_list' => $status_list]);
    }

    /** 推荐课程 页面 */
    public function recommend_create()
    {
        $vcourses = Vcourse::where('recommend', '=', 1)->get();

        return view('vcourse.recommend_create', ['vcourses' => $vcourses]);
    }

    /** 推荐课程 */
    public function recommend_store(Request $request)
    {
        $ids = $request->input('vcourse_id');
        $this->validate($request, [
            'vcourse_id' => 'required',
        ], [], [
            'vcourse_id' => '课程',
        ]);

        try {
            Vcourse::whereIn('id', $ids)->update(['recommend'=>2]);
            return redirect()->route('vcourse.recommend');
        } catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    /** 取消推荐课程 */
    public function recommend_cancel(Request $request)
    {
        $id = $request->input('id');
        $vcourse = Vcourse::find($id);

        if (!$vcourse) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        // 只有被推荐的课程才允许取消推荐
        if ($vcourse->recommend == 1) {
            return response()->json(['code' => 1, 'message' => '该课程已经被取消!']);
        }

        $vcourse->recommend = 1;
        if ($vcourse->save()) {
            return response()->json(['code' => 0, 'message' => '取消推荐成功!']);
        }
    }

    /** qiniu视频上传 */
    public function qiniu_uptoken(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $bucket = config('qiniu.BUCKET_NAME_VIDEO');
        $auth = new Qiniu_Auth(config('qiniu.AK'), config('qiniu.SK'));
        //notify url
        //$wmImg = Qiniu\base64_urlSafeEncode('http://rwxf.qiniudn.com/logo-s.png');
        $wmText = $this->base64_urlSafeEncode('试看');
        $wmColor = $this->base64_urlSafeEncode('#f39800');

        $pfopOps = "avthumb/mp4/s/640x360/vb/1.25m;avthumb/mp4/s/640x360/vb/1.25m/ss/0/t/" . config('qiniu.FREE_TIME') . "/wmFontColor/" . $wmColor . "/wmFontSize/60/wmText/" . $wmText;
        $policy = array(
            'persistentOps' => $pfopOps,
            'persistentNotifyUrl' => route('vcourse.qiniu_notify'),
            'persistentPipeline' => config('qiniu.PIPELINE'),
        );

        $upToken = $auth->uploadToken($bucket, null, 3600, $policy);

        return response()->json(['uptoken' => $upToken]);
    }

    /** qiniu持久化处理状态查询 */
    public function pfop_status(Request $request)
    {
        $id = $request->input('id');
        $url = "http://api.qiniu.com/status/get/prefop?id=$id";

        $resp = file_get_contents($url);

        header("Access-Control-Allow-Origin:*");
        echo $resp;
    }

    /** qiniu文件删除 */
    public function qiniu_delete(Request $request)
    {
        $bucket = config('qiniu.BUCKET_NAME_VIDEO');
        $auth = new Qiniu_Auth(config('qiniu.AK'), config('qiniu.SK'));
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        //原始key
        $keya = $request->input('keya');
        $keyb = $request->input('keyb');
        $keyf = $request->input('keyf');
        $vid = $request->input('vid');
        $queryBuilder = Vcourse::whereVideoOriginal($keya);
        if ($vid) {
            $queryBuilder->where('id', '!=', $vid);
            $vcourse = Vcourse::find($vid);
            if ($vcourse) {
                $vcourse->update(['video_original' => null, 'video_tran' => null, 'video_free' => null, 'bucket' => null]);
            }
        }
        //存在别的记录中不能删除
        if ($queryBuilder->first()) {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }

        $errA = $bucketMgr->delete($bucket, $keya);
        $errB = $bucketMgr->delete($bucket, $keyb);
        $errF = $bucketMgr->delete($bucket, $keyf);

//        if ($errA !== null || $errB !== null) {
//            return response()->json(['code' => 1, 'message' => '删除失败!']);
//        } else {
              return response()->json(['code' => 0, 'message' => '删除成功!']);
//        }
    }

    /**
     * web uploader  server process ,POST
     */
    public function uploadImages()
    {
        $upload = new UploadFile();// 实例化上传类
        $upload->savePath = 'uploads/vcourse/';// 设置附件上传目录
        $upload->thumb = true;//是否开启图片文件缩略图
        $upload->thumbPrefix = 'thum_';
        $upload->thumbMaxWidth = 140;
        $upload->thumbMaxHeight = 140;
        $upload->maxSize = 2097152;  //2MB

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

    function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
}
