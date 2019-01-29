<?php
/**
 * 后台线下活动课程管理
 */
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\Area;
use App\Models\Agency;
use App\Models\User;
use App\Library\UploadFile;

class CourseController extends Controller
{

    private $_rule = [
        'title' => 'required|min:2|max:15|unique:course,title,NULL,id,deleted_at,NULL',
        'picture' => 'required',
        'hardware' => 'required',
        'type' => 'required',
        'agency_id' => 'required',
        'price' => 'required_if:type,2',
        'original_price' => 'required_if:type,2',
//        'package_price' => 'required_if:type,2',
        'city' => 'required_if:head_flg,2',
        'allow_num' => 'integer',
        'participate_num' => 'integer',
        'verify_password' => 'required',
        'sort' => 'numeric',
        'tuangou_price' => 'required_if:type,3',
        'tuangou_peoples' => 'required_if:type,3',
        'tuangou_days' => 'required_if:type,3',
    ];

    private $_message = [
        'price.required_if' => '当 收费类别 为 收费 时 当前价 不能为空',
        'original_price.required_if' => '当 收费类别 为 收费 时 市场价 不能为空',
//        'package_price.required_if' => '当 收费类别 为 收费 时 套餐价 不能为空',
        'city.required_if' => '当 是否由总部发起 为 否 时 城市 不能为空',
    ];

    private $_customAttributes = [
        'title' => '课程标题',
        'picture' => '图片',
        'hardware' => '是否有硬件',
        'type' => '收费类别',
        'agency_id' => '课程类别',

        'price' => '当前价',
        'original_price' => '市场价',
//        'package_price' => '套餐价',
        
        'city' => '城市',
        
        'allow_num' => '名额',
        'participate_num' => '参与人数',
        'verify_password' => '验证密码',
        
        'tuangou_price' => '团购价',
        'tuangou_peoples' => '团购人数',
        'tuangou_days' => '截团天数',
    ];

    // 课程管理列表
    public function index(Request $request)
    {
        $builder = Course::select('course.*')->with(['user', 'area', 'agency']);

        if ($search_title = trim($request->input('search_title'))) {
            $builder->where('title', 'like', '%' . $search_title . '%');
        }

        if ($search_city = trim($request->input('search_city'))) {
            $builder->where('city', $search_city);
        }
        
        if ($search_type = trim($request->input('search_type'))) {
            $builder->where('type', $search_type);
        }

        if ($search_agency_id = trim($request->input('search_agency_id'))) {
            $builder->where('agency_id', $search_agency_id);
        }
        if ($search_promoter = trim($request->input('search_promoter'))) {
            $builder->where('promoter', $search_promoter);
        }
        if ($search_status = trim($request->input('search_status'))) {
            $builder->where('status', $search_status);
        }

        $courses = $builder->orderBy('sort', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $courses->appends($input, $value);
            }
        }

        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        // 获取所有合伙人的城市
        $partnerCitys = User::select('area.area_id', 'area.area_name')
            ->leftjoin('area', 'area.area_id', '=', 'user.partner_city')
            ->where('user.role', 3)
            ->where('user.block', 1)
            ->whereNotNull('user.partner_city')
            ->get();

        // 获取所有合伙人
        $partners = User::select('id', 'realname')
            ->where('user.role', 3)
            ->where('user.block', 1)
            ->whereNotNull('user.partner_city')
            ->get();

        return view('course.index', ['courses' => $courses, 'type_list' => $type_list, 'status_list' => $status_list, 'partnerCitys' => $partnerCitys, 'partners' => $partners,
            'agencyArr' => Agency::lists('agency_name', 'id')]);
    }

    /** 添加课程 页面 */
    public function create()
    {

        $hardware_list = config('constants.hardware_list');
        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        // 课程类别
        $agencys = Agency::select('id', 'agency_name')->get();

        // 获取所有合伙人的城市
        $partnerCitys = User::select('area.area_id', 'area.area_name')
            ->leftjoin('area', 'area.area_id', '=', 'user.partner_city')
            ->where('user.role', 3)
            ->where('user.block', 1)
            ->whereNotNull('user.partner_city')
            ->get();

        // dd($partnerCitys);
        // 获取所有合伙人的城市对应的合伙人
        $arrPartners = array();
        $arrPartners2 = array();
        if ($partnerCitys) {
            $arrPartners = $partnerCitys->toArray();
            foreach ($arrPartners as &$value) {
                $partnets = User::select('id', 'realname')->where('partner_city', $value['area_id'])->get();
                $partner = array();
                if ($partnets) {
                    $partner = $partnets->toArray();
                }
                $arrPartners2[$value['area_id']] = $partner;
            }
        }
        // dd($arrPartners2);exit;
        $arrPartners = json_encode($arrPartners2);

        return view('course.create', ['hardware_list' => $hardware_list, 'type_list' => $type_list, 'status_list' => $status_list, 'agencys' => $agencys, 'partnerCitys' => $partnerCitys, 'arrPartners' => $arrPartners]);
    }

    /** 添加课程 */
    public function store(Request $request)
    {
        $this->validate($request, $this->_rule, $this->_message, $this->_customAttributes);
        //验证只有一个指导师培训课程
//        $tutorCourse = Course::where('is_tutor_course', 1)->count();
//        if ($tutorCourse && $request->input('is_tutor_course') == 1)
//            return back()->withErrors('指导师培训课程已存在！');

        $course = new Course;
        $course->title = $request->input('title');
        $course->picture = $request->input('picture');
        $course->hardware = $request->input('hardware');
        $course->is_tutor_course = $request->input('is_tutor_course');
        $course->agency_id = $request->input('agency_id');
        $course->type = $request->input('type');
        $course->price = $request->input('price');
        $course->original_price = $request->input('original_price');
        $course->package_price = $request->input('package_price');
        $course->tuangou_price = $request->input('tuangou_price');
        $course->tuangou_peoples = $request->input('tuangou_peoples');
        $course->tuangou_days = $request->input('tuangou_days');
        $course->course_date = $request->input('course_date');
        $course->head_flg = $request->input('head_flg');
        if ($request->input('head_flg')=='1') {
            $course->distribution_flg = $request->input('distribution_flg');
        } else {
            $course->city = $request->input('city');
            $course->promoter = $request->input('promoter');
        }
        $course->course_addr = $request->input('course_addr');
        $course->allow_num = $request->input('allow_num');
        $course->participate_num = $request->input('participate_num');
        $course->status = 1;// 默认是未发布
        $course->recommend = 1;// 默认是未被推荐
        $course->tel = $request->input('tel');
        $course->suitable = $request->input('suitable');
        $course->teacher_intr = $request->input('teacher_intr');
        $course->course_target = $request->input('course_target');
        $course->course_arrange = $request->input('course_arrange');
        $course->verify_password = $request->input('verify_password');
        $course->sort = $request->input('sort')?$request->input('sort'):null;

        if ($request->input('type') == '1') {
            $course->price = 0;
            $course->original_price = 0;
            $course->package_price = 0;
        }

        if ($course->save()) {
            return redirect()->route('course.index');
        } else {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }

    }


    /** 课程编辑 页面 */
    public function edit($id)
    {
        $id = intval($id);
        $course = Course::with(['user'])->find($id);

        if ($course == null)
            abort(404, '不存在该课程！');

        $hardware_list = config('constants.hardware_list');
        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        // 课程类别
        $agencys = Agency::select('id', 'agency_name')->get();

        // 获取所有合伙人的城市
        $partnerCitys = User::select('area.area_id', 'area.area_name')
            ->leftjoin('area', 'area.area_id', '=', 'user.partner_city')
            ->where('user.role', 3)
            ->where('user.block', 1)
            ->whereNotNull('user.partner_city')
            ->get();

        // dd($partnerCitys);
        // 获取所有合伙人的城市对应的合伙人
        $arrPartners = array();
        $arrPartners2 = array();
        if ($partnerCitys) {
            $arrPartners = $partnerCitys->toArray();
            foreach ($arrPartners as &$value) {
                $partnets = User::select('id', 'realname')->where('partner_city', $value['area_id'])->get();
                $partner = array();
                if ($partnets) {
                    $partner = $partnets->toArray();
                }
                $arrPartners2[$value['area_id']] = $partner;
            }
        }
        //dd($arrPartners2);exit;
        $arrPartners = json_encode($arrPartners2);

        return view('course.edit', ['course' => $course, 'hardware_list' => $hardware_list, 'type_list' => $type_list, 'status_list' => $status_list, 'agencys' => $agencys, 'partnerCitys' => $partnerCitys, 'arrPartners' => $arrPartners]);
    }

    /** 课程编辑 */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);
        if ($course == null)
            abort(404, '不存在该课程！');

        $rule = $this->_rule;
        $rule['title'] = 'required|min:2|max:15|unique:course,title,' . $id . ',id,deleted_at,NULL';
        $this->validate($request, $rule, $this->_message, $this->_customAttributes);
        //验证只有一个指导师培训课程
//        $tutorCourse = Course::where('is_tutor_course', 1)->count();
//        if ($tutorCourse && $request->input('is_tutor_course') == 1 && $course->is_tutor_course != 1)
//            return back()->withErrors('指导师培训课程已存在！');

        $course->title = $request->input('title');
        $course->picture = $request->input('picture');
        $course->hardware = $request->input('hardware');
        $course->is_tutor_course = $request->input('is_tutor_course');
        $course->agency_id = $request->input('agency_id');
        $course->type = $request->input('type');
        $course->price = $request->input('price');
        $course->original_price = $request->input('original_price');
        $course->package_price = $request->input('package_price');
        $course->tuangou_price = $request->input('tuangou_price');
        $course->tuangou_peoples = $request->input('tuangou_peoples');
        $course->tuangou_days = $request->input('tuangou_days');
        $course->course_date = $request->input('course_date');
        $course->head_flg = $request->input('head_flg');
        if ($request->input('head_flg')=='1') {
            $course->distribution_flg = $request->input('distribution_flg');
            $course->city = null;
            $course->promoter = null;
        } else {
            $course->city = $request->input('city');
            $course->promoter = $request->input('promoter');
            $course->distribution_flg = null;
        }
        $course->course_addr = $request->input('course_addr');
        $course->allow_num = $request->input('allow_num');
        $course->participate_num = $request->input('participate_num');
        // $course->status = $request->input('status'); 课程状态不是通过这边修改
        // $course->recommend = 1;课程状态不是通过这边修改
        $course->tel = $request->input('tel');
        $course->suitable = $request->input('suitable');
        $course->teacher_intr = $request->input('teacher_intr');
        $course->course_target = $request->input('course_target');
        $course->course_arrange = $request->input('course_arrange');
        $course->verify_password = $request->input('verify_password');
        $course->sort = $request->input('sort')?$request->input('sort'):null;

        if ($request->input('type') == '1') {
            $course->price = 0;
            $course->original_price = 0;
            $course->package_price = 0;
        }

        if ($course->save()) {
            return redirect()->route('course.index');
        } else {
            return redirect()->back()->withInput()->withErrors('编辑失败');
        }

    }


    /** 删除课程 */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        // 只有未发布的课程才允许删除
        if ($course->status != 1) {
            return response()->json(['code' => 1, 'message' => '已发布的课程不可删除哦!']);
        }

        $course->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);

    }

    /** 保存并发布课程 */
    public function release(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $course->status = 2;
        if ($course->save()) {
            return response()->json(['code' => 0, 'message' => '发布成功!']);
        }

    }

    /** 保存并下架课程 */
    public function off(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $course->status = 3;
        if ($course->save()) {
            return response()->json(['code' => 0, 'message' => '下架成功!']);
        }

    }

    /** 保存并上架课程 */
    public function on(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        $course->status = 2;
        if ($course->save()) {
            return response()->json(['code' => 0, 'message' => '上架成功!']);
        }

    }

    /** 课程详情 */
    public function show($id)
    {
        $id = intval($id);
        $course = Course::with(['user'])->find($id);

        if ($course == null)
            abort(404, '不存在该课程！');

        $hardware_list = config('constants.hardware_list');
        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        // 课程类别
        $agencys = Agency::select('id', 'agency_name')->get();

        // 获取所有合伙人的城市
        $partnerCitys = User::select('area.area_id', 'area.area_name')
            ->leftjoin('area', 'area.area_id', '=', 'user.partner_city')
            ->where('user.role', 3)
            ->where('user.block', 1)
            ->whereNotNull('user.partner_city')
            ->get();

        // dd($partnerCitys);
        // 获取所有合伙人的城市对应的合伙人
        $arrPartners = array();
        $arrPartners2 = array();
        if ($partnerCitys) {
            $arrPartners = $partnerCitys->toArray();
            foreach ($arrPartners as &$value) {
                $partnets = User::select('id', 'realname')->where('partner_city', $value['area_id'])->get();
                $partner = array();
                if ($partnets) {
                    $partner = $partnets->toArray();
                }
                $arrPartners2[$value['area_id']] = $partner;
            }
        }
        // dd($arrPartners2);exit;
        $arrPartners = json_encode($arrPartners2);

        return view('course.show', ['course' => $course, 'hardware_list' => $hardware_list, 'type_list' => $type_list, 'status_list' => $status_list, 'agencys' => $agencys, 'partnerCitys' => $partnerCitys, 'arrPartners' => $arrPartners]);

    }

    /**
     * web uploader  server process ,POST
     */
    public function uploadImages()
    {
        $upload = new UploadFile();// 实例化上传类
        $upload->savePath = 'uploads/course/';// 设置附件上传目录
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

    // 评论管理列表
    public function comment_list(Request $request)
    {
        $builder = CourseComment::select('course_comment.*')->with('course', 'user');

        if ($search_course = trim($request->input('search_course'))) {
            $builder->whereHas('course', function ($query) use ($search_course) {
                $query->where('title', 'like', '%' . $search_course . '%');
            });
        }

        if ($search_user = trim($request->input('search_user'))) {
            $builder->whereHas('user', function ($query) use ($search_user) {
                $query->where('nickname', 'like', '%' . $search_user . '%');
            });
        }

        if ($search_time_s = trim($request->input('search_time_s'))) {
            $builder->where('created_at', '>=', $search_time_s);
        }

        if ($search_time_e = trim($request->input('search_time_e'))) {
            $builder->where('created_at', '<=', $search_time_e);
        }


        $course_comments = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $course_comments->appends($input, $value);
            }
        }

        return view('course.comment_list', ['course_comments' => $course_comments]);
    }


    /** 删除评论 */
    public function comment_delete(Request $request)
    {
        $id = $request->input('id');

        $del_ids = explode(',', trim($id, ','));

        if (!CourseComment::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }

    }

    /** 评论详情 */
    public function comment_show($id)
    {
        $id = intval($id);
        $course_comment = CourseComment::find($id);

        if (!$course_comment) {
            abort(404, '不存在该课程评论！');
        }

        // 课程list
        $courses = Course::withTrashed()->get();
        $arrCourses = array();
        foreach ($courses as &$value) {
            $arrCourses[$value['id']] = $value['title'];
        }

        // 评论人list
        $users = User::withTrashed()->get();
        $arrUsers = array();
        foreach ($users as &$value) {
            $arrUsers[$value['id']] = $value['nickname'];
        }


        return view('course.comment_show', ['course_comment' => $course_comment, 'arrCourses' => $arrCourses, 'arrUsers' => $arrUsers]);
    }


    // 推荐管理列表
    public function recommend_list(Request $request)
    {
        $builder = Course::select('course.*')->where('recommend', '=', 2);

        $courses = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $courses->appends($input, $value);
            }
        }

        $type_list = config('constants.type_list');
        $status_list = config('constants.status_list');

        $areas = Area::select('area_id', 'area_name')->get();
        $arrArea = array();
        foreach ($areas as &$value) {
            $arrArea[$value['area_id']] = $value['area_name'];
        }

        return view('course.recommend_list', ['courses' => $courses, 'type_list' => $type_list, 'status_list' => $status_list, 'areas' => $arrArea]);
    }

    /** 推荐课程 页面 */
    public function recommend_create()
    {
        $courses = Course::where('recommend', '=', 1)->get();


        return view('course.recommend_create', ['courses' => $courses]);
    }

    /** 推荐课程 */
    public function recommend_store(Request $request)
    {
        $this->validate($request, ['id' => 'required'], ['id.required' => '请选择课程标题'], ['id' => '课程标题']);
        $ids = $request->input('id');
        try {
            Course::whereIn('id', $ids)->update(['recommend'=>2]);
            return redirect()->route('course.recommend');
        } catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 取消推荐课程
     */
    public function recommend_cancel(Request $request)
    {
        $id = $request->input('id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }

        // 只有被推荐的课程才允许取消推荐
        if ($course->recommend == 1) {
            return response()->json(['code' => 1, 'message' => '该课程已经被取消!']);
        }

        $course->recommend = 1;
        if ($course->save()) {
            return response()->json(['code' => 0, 'message' => '取消推荐成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '取消推荐失败!']);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * 发送开课提醒
     */
    public function sendNotify()
    {
        $id = intval(request('id'));
        $content = request('content');
        $course = Course::find($id);
        if ($course == null) {
            return response()->json(['code' => 1, 'message' => '不存在该课程!']);
        }
        $courseOrders = Order::where('pay_type', 1)
            ->where('order_type', 2)
            ->where('pay_id', $id)
            ->with('user')
            ->get();
        if ($courseOrders->count()) {
            $mobiles = [];
            foreach ($courseOrders as $order) {
                $mobiles[] = $order->user->mobile;
            }
            $collection = collect($mobiles);
            $chunks = $collection->chunk(200);
            foreach ($chunks as $chunk) {
                send_sms($chunk->toArray(), $content);
            }
            return response()->json(['code' => 0, 'message' => '短信发送成功']);
        } else {
            return response()->json(['code' => 1, 'message' => '尚未有用户参加课程']);
        }
    }


}
