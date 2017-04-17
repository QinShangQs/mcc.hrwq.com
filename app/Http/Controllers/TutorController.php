<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTutorApply;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Wechat;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $builder = UserTutorApply::select('user_tutor_apply.*')
            ->with(['user'])
            ->whereIn('progress', [1, 4]);

        if ($search_nickname = trim($request->input('search_nickname'))) {
            $builder->whereHas('user', function ($query) use ($search_nickname) {
                $query->where('nickname', 'like', '%' . $search_nickname . '%');
            });
        }
        if ($search_realname = trim($request->input('search_realname'))) {
            $builder->where('realname', 'like', '%' . $search_realname . '%');
        }
        if ($search_mobile = trim($request->input('search_mobile'))) {
            $builder->whereHas('user', function ($query) use ($search_mobile) {
                $query->where('mobile', 'like', '%' . $search_mobile . '%');
            });
        }
        if ($search_sex = trim($request->input('search_sex'))) {
            $builder->where('sex', '=', $search_sex);
        }
        if ($search_email = trim($request->input('search_email'))) {
            $builder->where('email', 'like', '%' . $search_email . '%');
        }
        if ($search_address = trim($request->input('search_address'))) {
            $builder->where('address', 'like', '%' . $search_address . '%');
        }
        if ($search_time_s = trim($request->input('search_time_s'))) {
            $builder->where('created_at', '>=', $search_time_s);
        }
        if ($search_time_e = trim($request->input('search_time_e'))) {
            $builder->where('created_at', '<=', $search_time_e);
        }


        $user_tutors = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $user_tutors->appends($input, $value);
            }
        }

        // 性别
        $user_sex = config('constants.user_sex');
        // 指导师申请进度
        $tutor_apply_progress = config('constants.tutor_apply_progress');


        return view('tutor.list', ['user_tutors' => $user_tutors, 'user_sex' => $user_sex, 'tutor_apply_progress' => $tutor_apply_progress]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 指导师查看与审核 页面
     */
    public function check($id)
    {
        $id = intval($id);
        $userTutorApply = UserTutorApply::with(['user'])->find($id);

        if ($userTutorApply == null) {
            abort(404, '不存在该用户申请！');
        }

        // 性别
        $user_sex = config('constants.user_sex');
        // 指导师审核进度
        $tutor_apply_progress = config('constants.tutor_apply_progress');

        return view('tutor.check', ['userTutorApply' => $userTutorApply, 'user_sex' => $user_sex, 'tutor_apply_progress' => $tutor_apply_progress]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 指导师资料审核通过
     */
    public function pass(Request $request)
    {
        $id = $request->input('id');
        $tutorApply = UserTutorApply::find($id);

        if ($tutorApply == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户申请!']);
        }
        $tutorApply->progress = 2;

        // 保存user表中
        $user = User::find($tutorApply->user_id);
        if ($user == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户!']);
        }
        // 判断该用户的角色是否是合伙人
        if ($user->role == 3) {
            return response()->json(['code' => 1, 'message' => '该用户是合伙人，不可再次申请为指导师!']);
        }
        $user->realname = $tutorApply->realname;
        $user->email = $tutorApply->email;
        $user->sex = $tutorApply->sex;
        $user->address = $tutorApply->address;
        $user->tutor_honor = $tutorApply->honor;
        $user->tutor_cover = $tutorApply->cover;
        $user->tutor_price = $tutorApply->price;
        $user->tutor_introduction = $tutorApply->introduction;

        if ($tutorApply->save() && $user->save()) {
            try {
                //发送模板消息通知用户
                $notice = Wechat::notice();
                $messageId = $notice->send([
                    'touser' => $user->openid,
                    'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                    'url' => front_url('question/teacher/'.$user->id ),
                    'topcolor' => '#f7f7f7',
                    'data' => [
                        'first' => '指导师资料审核通过！',
                        'keyword1'=>'指导师资料更新',
                        'keyword2'=> '指导师资料审核通过',
                        'remark'=> '点击前往指导师中心查看详情'
                    ],
                ]);
            }catch (\Exception $e){

            }
            return response()->json(['code' => 0, 'message' => '资料审核通过成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '资料审核通过失败!']);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 指导师资料审核不通过
     */
    public function reject(Request $request)
    {
        $id = $request->input('id');
        $failReason = $request->input('fali_cause');
        $tutorApply = UserTutorApply::find($id);
        $user = User::find($tutorApply->user_id);
        if ($tutorApply == null) {
            return response()->json(['code' => 1, 'message' => '不存在该用户申请!']);
        }
        if ($user == null) {
            return response()->json(['code' => 2, 'message' => '不存在该用户!']);
        }
        $tutorApply->progress = 3;
        $tutorApply->fali_cause = $failReason;
        if ($tutorApply->save()) {
            //发送模板消息通知用户
            $notice = Wechat::notice();
            $messageId = $notice->send([
                'touser' => $user->openid,
                'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                'url' => front_url('tutor/complete'),
                'topcolor' => '#f7f7f7',
                'data' => [
                    'first' => '指导师资料审核未通过！',
                    'keyword1'=>'指导师资料更新',
                    'keyword2'=> '指导师资料审核未通过',
                    'remark'=> '未通过原因：'.$failReason
                ],
            ]);
            return response()->json(['code' => 0, 'message' => '资料审核驳回成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '资料审核驳回失败!']);
        }
    }
}
