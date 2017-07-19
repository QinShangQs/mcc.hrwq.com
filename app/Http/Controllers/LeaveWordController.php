<?php

namespace App\Http\Controllers;

use App\Models\LeaveWord;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;

class LeaveWordController extends Controller
{
    public function index(Request $request)
    {
//         $words = LeaveWord::select('leave_word.*')
//         ->orderBy('leave_word.id','desc')->get();
        
        $words = DB::table('leave_word')
        ->join('user', 'user.id', '=', 'leave_word.user_id')
        ->select('leave_word.*','user.nickname','user.mobile')
        ->orderBy('leave_word.id','desc')
        ->get();
       
        return view('leave_word.index', ['words' => $words]);
    }

    public function show($id)
    {
        $word = LeaveWord::find($id);
        $word_user = User::find($word->user_id);
        return view('leave_word.show',['word'=>$word,'word_user'=>$word_user]);
    }

   
    /** 删除*/
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $word = LeaveWord::find($id);

        if (!$word) {
            return response()->json(['code' => 1, 'message' => '不存在的该留言!']);
        }

        $word->delete();
        return response()->json(['code' => 0, 'message' => '删除成功!']);
    }
}
