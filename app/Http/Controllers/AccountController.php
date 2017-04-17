<?php
/**
 * 后台账号管理
 */
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Admin::class);
        $accounts = Admin::select('admin.*')->paginate(10);
        return view('account.index', ['accounts' => $accounts]);
    }
}
