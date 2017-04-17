<?php
/**
 * 后台登陆相关
 */
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Auth, Hash;

class AdminController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha',
        ], [],[
            'name' => '用户名',
            'password' => '密码',
            'captcha' => '验证码'
        ]);

        if ($request->input('name') == 'admin') {
            $admin = Admin::where('name', 'admin')->first();
            if ($admin == null) {
                Admin::create(['admin_type' => '1', 'name' => 'admin', 'password' => bcrypt($request->input('password'))]);
            }
        }

        if (Auth::attempt(['name' => $request->input('name'), 'password' => $request->input('password')], $request->input('remember'))) {
            return response()->json(['code' => 0, 'message' => '登陆成功！', 'data' => ['redirectTo' => session('url.intended') ? session('url.intended') : route('home')]]);
        } else {
            return response()->json(['code' => 1, 'message' => '登陆失败！']);
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function postPassword(Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'old_password' => 'required|max:20',
            'new_password' => 'required|min:6|confirmed',
        ], [ ], [
            'old_password' => '原密码',
            'new_password' => '密码',
        ]);
        if ($validator->fails()) {
            return [
                'code' => 2,
                'message' => $validator->errors()->getMessages(),
            ];
        }

        $admin = Auth::getUser();
        if (Hash::check($request->input('old_password'), $admin->password)) {
            $admin->password = bcrypt($request->input('new_password'));
            $admin->save();
            return [ 'code' => 0, 'message' => 'OK' ];
        }
        else {
            return [
                'code' => 2,
                'message' => [ 'old_password' => [ '原密码不正确' ] ],
            ];
        }
    }

}
