<?php
/**
 * 后台壹家壹服务管理
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Models\Opo;
use App\Models\OpoComment;
use App\Models\User;
use App\Library\UploadFile;

class OpoController extends Controller
{
    // 产品列表
    public function index()
    {
        $opo = Opo::orderBy('id', 'desc')->first();
        if ($opo == null) {
            return view('opo.create');
        } else {
            return view('opo.edit', ['opo' => $opo]);
        }
    }


    /** 添加产品 页面 */
    public function create()
    {
        return view('opo.create');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     *
     * 添加产品
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:opo,title,NULL,id,deleted_at,NULL',
            'project_title' => 'required',
            'picture' => 'required',
            'price' => 'required|numeric'
        ], [], [
            'title' => '产品名称',
            'project_title' => '产品标题',
            'picture' => '图片',
            'price' => '价格',
        ]);

        $opo = Opo::orderBy('id', 'desc')->first();
        if ($opo != null) {
            return back()->withInput()->withErrors('壹加壹产品已存在，无法再次添加！');
        }

        $opo = new Opo;
        $opo->title = $request->input('title');
        $opo->project_title = $request->input('project_title');
        $opo->price = $request->input('price');
        $opo->project_intr = $request->input('project_intr');
        $opo->picture = $request->input('picture');

        if ($opo->save()) {
            return redirect()->route('opo.index');
        } else {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }


    /** 产品编辑 页面 */
    public function edit($id)
    {
        $id = intval($id);
        $opo = Opo::find($id);

        if (!$opo) {
            abort(404, '不存在该产品！');
        }


        return view('opo.edit', ['opo' => $opo]);
    }

    /** 产品编辑 */
    public function update(Request $request)
    {
        $id = intval($request->input('id'));
        $this->validate($request, [
            'title' => 'required|min:2|max:80|unique:opo,title,'.$id.',id,deleted_at,NULL',
            'project_title' => 'required',
            'picture' => 'required',
            'price' => 'required|numeric'
        ], [], [
            'title' => '产品名称',
            'project_title' => '产品标题',
            'picture' => '图片',
            'price' => '价格',
        ]);
        $opo = Opo::find($id);
        $opo->title = $request->input('title');
        $opo->project_title = $request->input('project_title');
        $opo->price = $request->input('price');
        $opo->project_intr = $request->input('project_intr');
        $opo->picture = $request->input('picture');

        if ($opo->save()) {
            return redirect()->route('opo.index');
        } else {
            return redirect()->back()->withInput()->withErrors('编辑失败');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 删除产品
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $opo = Opo::find($id);

        if (!$opo) {
            return response()->json(['code' => 1, 'message' => '不存在该产品!']);
        }

        $opo->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);

    }

    /** 产品详情 */
    public function show($id)
    {
        $id = intval($id);
        $opo = Opo::find($id);

        if (!$opo) {
            abort(404, '不存在该产品！');
        }

        return view('opo.show', ['opo' => $opo]);
    }


    /**
     * web uploader  server process ,POST
     */
    public function uploadImages()
    {
        $upload = new UploadFile();// 实例化上传类
        $upload->savePath = 'uploads/opo/';// 设置附件上传目录
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


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 评论管理列表
     */
    public function comment_list(Request $request)
    {
        $builder = OpoComment::select('opo_comment.*');

        if ($search_opo = trim($request->input('search_opo'))) {
            $builder->whereHas('opo', function ($query) use ($search_opo) {
                $query->where('title', 'like', '%' . $search_opo . '%');
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


        $opo_comments = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $opo_comments->appends($input, $value);
            }
        }

        // 产品list
        $opos = Opo::withTrashed()->get();
        $arrOpos = array();
        foreach ($opos as &$value) {
            $arrOpos[$value['id']] = $value['title'];
        }

        // 评论人list
        $users = User::withTrashed()->get();
        $arrUsers = array();
        foreach ($users as &$value) {
            $arrUsers[$value['id']] = $value['nickname'];
        }

        // 是否点赞
        $likes_list = config('constants.likes_list');

        return view('opo.comment_list', ['opo_comments' => $opo_comments, 'opos' => $opos, 'arrOpos' => $arrOpos, 'users' => $users, 'arrUsers' => $arrUsers, 'likes_list' => $likes_list]);
    }


    /** 删除评论 */
    public function comment_delete(Request $request)
    {
        $id = $request->input('id');

        $del_ids = explode(',', trim($id, ','));

        if (!OpoComment::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        } else {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        }

    }

    /** 评论详情 */
    public function comment_show($id)
    {
        $id = intval($id);
        $opo_comment = OpoComment::find($id);

        if (!$opo_comment) {
            abort(404, '不存在该产品评论！');
        }

        // 产品list
        $opos = Opo::withTrashed()->get();
        $arrOpos = array();
        foreach ($opos as &$value) {
            $arrOpos[$value['id']] = $value['title'];
        }

        // 评论人list
        $users = User::withTrashed()->get();
        $arrUsers = array();
        foreach ($users as &$value) {
            $arrUsers[$value['id']] = $value['nickname'];
        }

        // 是否点赞
        $likes_list = config('constants.likes_list');


        return view('opo.comment_show', ['opo_comment' => $opo_comment, 'arrOpos' => $arrOpos, 'arrUsers' => $arrUsers, 'likes_list' => $likes_list]);
    }

}
