<?php
/**
 * 好问  互助帮 帖子
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TagWithTrash;
use App\Models\Talk;
use App\Models\TalkComment;
use Validator;


class TalkController extends Controller
{
    /**
     * 帖子列表
     */
    public function index(Request $request)
    {
        //关联模型
        $builder = Talk::withTrashed()->with(['ask_user','tags']);

        //问题名称
        if ($search_name = trim($request->input('content'))) {
            $builder->where('title', 'like', '%'.$search_name.'%');
        }

        //检索提问人
        if ($ask_user = trim($request->input('ask_user'))) {
            $builder->whereHas('ask_user', function ($query) use ($ask_user) {
                $query->where(function($query) use($ask_user) {
                    $query->where('nickname', 'like', '%' . $ask_user . '%')
                        ->orWhere('realname', 'like', '%' . $ask_user . '%');
                });
            });
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //开始时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        //标签
        if ($tag_id = trim($request->input('tags'))) {
            $builder->whereHas('tags', function ($query) use ($tag_id) {
                $query->where('talk_tag.tag_id', $tag_id);
            });
        }

        $data = $builder->orderBy('talk.sort', 'desc')->orderBy('talk.id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('talk.index', ['data' => $data,'tags'=>TagWithTrash::all()]);
    }

    /** 帖子修改 */
    public function edit($id)
    {
        $question = Talk::find($id);
        if ($question == null)
            abort(404, '问题已关闭');

        $cur_tags = $question->tags->lists('id')->toArray();
        return view('talk.edit',['question'=>$question,'all_tags'=>TagWithTrash::all(),'cur_tags'=>$cur_tags]);
    }

    /** 帖子修改 - 保存*/
    public function update(Request $request,$id)
    {
        Validator::extend('array_num', function($attribute, $value, $parameters) {
            return is_array($value) && count($value) <=3 && count($value) >=1;
        });

        $talk = Talk::find($id);

        if ($talk == null)
            abort(404, '问题不存在！');

        $this->validate($request, [
            'view'  => 'required|numeric',
            'tags'  => 'array_num',
        ], [], [
            'view'  => '收听次数',
            'tags'  => '标签',
        ]);

        $updates = [];
        $updates['view'] = $request->input('view');
        $updates['sort'] = $request->input('sort')?$request->input('sort'):null;

        $talk->update($updates);
        $newTags = $request->input('tags');
        if(empty($newTags))
            $newTags = [];
        $talk->tags()->sync($newTags);

        return redirect()->route('talk.index');
    }


    /** 帖子详情 */
    public function show($id)
    {
        $talk = Talk::withTrashed()->whereId($id)->first();
        return view('talk.show',['question'=>$talk]);
    }

    /** 帖子评论 */
    public function comments(Request $request,$id)
    {
        $talk =  Talk::withTrashed()->whereId($id)->first();

        //关联模型
        $builder = TalkComment::where('talk_id',$id)->with(['answer_user']);

        //回答人
        if ($ask_user = trim($request->input('answer_user'))) {
            $builder->whereHas('answer_user', function ($query) use ($ask_user) {
                $query->where('nickname', 'like', '%' . $ask_user . '%');
            });
        }

        //开始时间
        if ($start_time = $request->input('s_time')) {
            $builder->where('created_at', '>=', "{$start_time}" . ' 00:00:00');
        }

        //开始时间
        if ($stop_time = $request->input('e_time')) {
            $builder->where('created_at', '<=', "{$stop_time}" . ' 23:59:59');
        }

        $data = $builder->paginate(15);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('talk.comments', ['data' => $data,'talk'=>$talk]);
    }


    public function comment_delete(Request $request)
    {
        $del_ids = explode(',',trim($request->input('id'),','));
        if (!TalkComment::destroy($del_ids)) {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        }
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }


    /**
     * 前台显示关闭
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $item = Talk::find($id);
        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '查找失败!']);
        }

        $item->delete();
        if ($item->trashed()) {
            return response()->json(['code' => 0, 'message' => '关闭成功!']);
        } else {
            return response()->json(['code' => 3, 'message' => '关闭失败!']);
        }
    }

    /**
     * 前台显示开启
     */
    public function restore(Request $request)
    {
        $id = $request->input('id');
        $item = Talk::withTrashed()->whereId($id)->first();
        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '查找失败!']);
        }

        if ($item->restore()) {
            return response()->json(['code' => 0, 'message' => '开启成功!']);
        } else {
            return response()->json(['code' => 3, 'message' => '开启失败!']);
        }
    }
}
