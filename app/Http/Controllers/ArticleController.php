<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    private $_rules = [
        'title' => 'required',
        'type' => 'required',
        'content' => 'required',
    ];

    private $_customAttributes = [
        'title' => '文章标题',
        'type' => '类型',
        'content' => '内容',
    ];

    public function index()
    {
        $data = Article::orderBy('type','asc')->orderBy('id','desc')->get();
        return view('article.index', ['datas' => $data, 'type' =>config('constants.article_type') ]);
    }

    public function create()
    {
        return view('article.create', ['type' => config('constants.article_type')]);
    }

    public function store(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $data = $request->all();

        $this->validate($request, $this->_rules, [], $this->_customAttributes);

        $data['add_uid'] = $data['update_uid'] = $request->user()->id;

        Article::create($data);
        return redirect()->route('article');
    }

    public function show($id)
    {
        $article = Article::find($id);
        if (!$article)
            abort(404, '未找到该文章！');

        return view('article.show', ['data' => $article]);
    }

    public function edit($id)
    {
        $data = Article::find($id);
        if ($data == null)
            abort(404, '未找到该文章！');
        return view('article.edit', ['data' => $data, 'type' =>config('constants.article_type') ]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if ($article == null)
            abort(404, '未找到该文章！');
        $request->merge(array_map('trim', $request->all()));

        $data = $request->all();

        $this->validate($request, $this->_rules, [], $this->_customAttributes);

        $data['update_uid'] = $request->user()->id;

        $article->update($data);
        return redirect()->route('article');
    }


    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['code' => 1, 'message' => '未找到该文章!']);
        }


        $count = Article::where('type', $article->type)->count();

        if ($count <= 1) {
            return response()->json(['code' => 1, 'message' => '当前类型只有一篇文章，不可删除！!']);
        }

        $article->delete();

        if ($article->trashed()) {
            return response()->json(['code' => 0, 'message' => '删除成功!']);
        } else {
            return response()->json(['code' => 1, 'message' => '删除失败!']);
        }
    }
}
