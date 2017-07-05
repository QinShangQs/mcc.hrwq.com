<?php
/**
 * 好问  标签管理 ，问题榜
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\TagWithTrash;
use App\Models\Question;
use Validator;

class QuestionController extends Controller
{
    /** 问题榜列表 */
    public function index(Request $request)
    {
        //关联模型
        $builder = Question::with(['ask_user','answer_user','tags']);
        
        //排除提问后未付款的问题
        $builder->join('order', function ($join) {
            $join->on('question.id', '=', 'order.pay_id')
                 ->where('order.pay_type', '=', 4)
                 ->where('order.order_type', '=', 2);
        });

        //问题名称
        if ($search_name = trim($request->input('content'))) {
            $builder->where('content', 'like', '%'.$search_name.'%');
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

        //检索被问人
        if ($answer_user = trim($request->input('answer_user'))) {
            $builder->whereHas('answer_user', function ($query) use ($answer_user) {
                $query->where(function($query) use($answer_user) {
                    $query->where('nickname', 'like', '%' . $answer_user . '%')
                        ->orWhere('realname', 'like', '%' . $answer_user . '%');
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
                $query->where('question_tag.tag_id', $tag_id);
            });
        }

        //问题状态
        if ($ask_state = trim($request->input('ask_state'))) {
            $builder->where('answer_flg',$ask_state);
        }

        //收听次数
        if ($listener_num = trim($request->input('listener_num'))) {
            $pick_mod = $request->input('pick_mod');
            $mod = '>';
            if($pick_mod > 0) {
                if ($pick_mod == 2) {
                    $mod = '=';
                } elseif ($pick_mod == 3) {
                    $mod = '<';
                }
                $builder->where('listener_nums',$mod,$listener_num);
            }
        }

        $data = $builder->orderBy('question.created_at', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $data->appends($input, $value);
            }
        }

        return view('question.index', ['data' => $data,'answer_state'=>config('constants.answer_state'),'tags'=>TagWithTrash::all()]);
    }

    /** 问题详情 */
    public function show($id)
    {
        $question = Question::withTrashed()->whereId($id)->first();

        $listeners = $question->listener->pluck('nickname')->toArray();
        $listeners = implode($listeners,' , ');

        return view('question.show',['question'=>$question,'answer_state'=>config('constants.answer_state'),'listeners'=>$listeners]);
    }

    /** 问题修改 */
    public function edit($id)
    {
        $question = Question::withTrashed()->find($id);
        if ($question == null){
            abort(404, '问题已关闭');
        }

        $cur_tags = $question->tags->lists('id')->toArray();
        return view('question.edit',['question'=>$question,'all_tags'=>TagWithTrash::all(),'cur_tags'=>$cur_tags]);
    }

    /** 问题修改 - 保存*/
    public function update(Request $request,$id)
    {
        Validator::extend('array_num', function($attribute, $value, $parameters) {
            return is_array($value) && count($value) <=3 && count($value) >=1;
        });

        $question = Question::find($id);

        if ($question == null)
            abort(404, '问题不存在！');

        $this->validate($request, [
            'listener_nums'  => 'required|numeric',
            'tags'  => 'array_num',
            'free_end'  => 'after:free_from',
        ], [], [
            'listener_nums' => '收听次数',
            'free_from' => '开始时间',
            'free_end'  => '截止时间',
            'tags' => '标签'
        ]);

        $update_question = [];
        $update_question['listener_nums'] = $request->input('listener_nums');
        if($request->input('free_from') && $request->input('free_end')) {
            $update_question['free_flg'] = 1;
            $update_question['free_from'] = $request->input('free_from');
            $update_question['free_end'] = $request->input('free_end');
        } else {
            $update_question['free_flg'] = 0;
            $update_question['free_from'] = null;
            $update_question['free_end'] = null;
        }
        $update_question['sort'] = $request->input('sort')?$request->input('sort'):null;

        $question->update($update_question);
        $tags = $request->input('tags');
        if(empty($tags))
            $tags = [];
        $question->tags()->sync($tags);

        return redirect()->route('question.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 前台显示关闭
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $item = Question::find($id);
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
        $item = Question::withTrashed()->whereId($id)->first();
        if ($item == null) {
            return response()->json(['code' => 1, 'message' => '查找失败!']);
        }

        if ($item->restore()) {
            return response()->json(['code' => 0, 'message' => '开启成功!']);
        } else {
            return response()->json(['code' => 3, 'message' => '开启失败!']);
        }
    }

    /** 标签列表 */
    public function tag_list(Request $request)
    {
        $builder = Tag::with('questions','talks');

        if ($search_name = trim($request->input('search_name'))) {
            $builder->where('title', 'like', '%' . $search_name . '%');
        }

        $tags = $builder->orderBy('id', 'desc')->paginate(10);

        foreach ($request->except('page') as $input => $value) {
            if (!empty($value)) {
                $tags->appends($input, $value);
            }
        }

        return view('question.tag_list', ['tags' => $tags]);
    }

    /** 标签添加 */
    public function tag_create()
    {
        return view('question.tag_create');
    }

    /** 标签添加-保存 */
    public function tag_store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $this->validate($request, [
            'title' => 'required|min:2|max:20|unique:tag,title,NULL,id,deleted_at,NULL',
            'sort'  => 'required|numeric'
        ], [], [
            'title' => '标签名称',
            'sort' => '排序',
        ]);

        Tag::create($request->all());
        return redirect()->route('question.tags');
    }

    /** 标签编辑 */
    public function tag_edit($id)
    {
        $tag = Tag::find($id);
        if ($tag == null)
            abort(404, '标签查找失败！');
        return view('question.tag_edit', ['tag' => $tag]);
    }

    /** 标签编辑 - 保存*/
    public function tag_update(Request $request,$id)
    {
        $tag = Tag::find($id);
        if ($tag == null)
            abort(404, '标签查找失败！');
        $request->merge(array_map('trim', $request->all()));
        $this->validate($request, [
            'title' => 'required|min:2|max:20|unique:tag,title,' . $id . ',id,deleted_at,NULL',
            'sort'  => 'required|numeric'
        ], [], [
            'title' => '标签名称',
            'sort' => '排序',
        ]);

        $tag->update($request->all());
        return redirect()->route('question.tags');
    }

    public function tag_delete(Request $request)
    {
        $tag = Tag::find($request->input('id'));

        if (!$tag) {
            return response()->json(['code' => 1, 'message' => '不存在的标签!']);
        }

        $tag->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }


}
