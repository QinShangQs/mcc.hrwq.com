<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Wechat;

class TestController extends Controller
{
    public function index()
    {
        switch(request('type')) {
            case 1:     //好课订单确认后 30 分钟未支付
                $now = Carbon::now();
                $startTime = (string)$now->subMinutes(30);
                $endTime = (string)$now->addMinutes(5);
                $courseUnpaidOrders = Order::where('order_type', 1)
                    ->where('pay_type', 1)
                    ->where('pay_notify_flg', 1)
                    ->whereBetween('created_at', [$startTime, $endTime])
                    ->with('user', 'course')->get();
                if (count($courseUnpaidOrders)) {
                    foreach ($courseUnpaidOrders as $courseUnpaidOrder) {
                        send_sms([$courseUnpaidOrder->user->mobile], '你报名的 ' . $courseUnpaidOrder->course->title . ' 尚未支付，请尽快前往“我的”完成支付。父母是孩子最好的老师，祝你生活愉快！');
                        //推送给合伙人
                        if($courseUnpaidOrder->course->head_flg == 2) {
                            $partner = User::where('block', 1)->find($courseUnpaidOrder->course->promoter);
                        } else if($courseUnpaidOrder->course->head_flg == 1 && $courseUnpaidOrder->course->distribution_flg == 1) {
                            $partner = User::where('block', 1)->where('partner_city', $courseUnpaidOrder->order_course->user_city)->first();
                        }
                        if(!empty($partner)){
                            echo $partner->openid;
                            $notice = Wechat::notice();
                            $notice->send([
                                'touser' => $partner->openid,
                                'template_id' => '7hXsOVA4WE3nGyta1UQRqUOtDP6C1D5ymR-E46_X1Ts',
                                'url' => front_url('partner/orders'),
                                'topcolor' => '#f7f7f7',
                                'data' => [
                                    'first' => '合伙人订单顾客未支付提醒',
                                    'keyword1'=> '好课',
                                    'keyword2'=> (string)$courseUnpaidOrder->created_at,
                                    'keyword3'=> $courseUnpaidOrder->course->title,
                                    'remark'=> '您所负责的区域有顾客下单后30分钟未支付，请及时关注。'
                                ],
                            ]);
                        }
                    }
                    Order::whereIn('id', $courseUnpaidOrders->pluck('id')->toArray())->update(['pay_notify_flg' => 2]);
                }
                break;
            case 2:     //好问 没回答
                for ($i = 1; $i <= 10; ++$i) {
                    $now = Carbon::now();
                    $startTime = (string)$now->subMinutes($i * 24 * 60);
                    $endTime = (string)$now->addMinutes(5);
                    $notAnsweredQuestions = Question::where('answer_flg', 1)
                        ->where('answer_notify_flg', $i)
                        ->whereHas('order', function ($query) {
                            $query->where('pay_type', 4);
                        })
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->get();
                    if ($notAnsweredQuestions->count()) {
                        foreach ($notAnsweredQuestions as $question) {
                            $notice = \Wechat::notice();
                            $notice->send([
                                'touser' => $question->answer_user->openid,
                                'template_id' => '2FBf-IuHdd0Jptwxrn-1NfjJMVORVqAKx4HuevfsjpI',
                                'url' => front_url('user/answer_voice/' . $question->id),
                                'topcolor' => '#f7f7f7',
                                'data' => [
                                    'first' => '好问回答提醒',
                                    'keyword1' => '好问-' . $question->content,
                                    'keyword2' => '未回答',
                                    'remark' => '爱的家人，你有超过24个小时仍未回答的问题'
                                ],
                            ]);
                        }
                        Question::whereIn('id', $notAnsweredQuestions->pluck('id')->toArray())->update(['answer_notify_flg' => ($i+1)]);
                    }
                }
                break;
            case 3:
                //课程开始前n天通知
                $now = Carbon::now();
                $startTime = (string)$now->addDays(config('constants.notify_days_before_course'));
                $endTime = (string)$now->addMinutes(10);
                $nearStartCourseOrders = Order::where('pay_type', 1)
                    ->where('order_type', 2)
                    ->where('course_start_notify_flg', 1)
                    ->whereHas('course', function($query) use ($startTime, $endTime){
                        $query->whereBetween('course_date_start', [$startTime, $endTime]);
                    })
                    ->with('user', 'course')
                    ->get();
                if ($nearStartCourseOrders->count()) {
                    foreach ($nearStartCourseOrders as $order) {
                        send_sms([$order->user->mobile], '你报名的'.$order->course->title.'活动还有'.config('constants.notify_days_before_course').'天就要开课了，请注意安排好行程。父母是孩子最好的老师，祝你生活愉快！客服电话400-6363-555');
                    }
                    Order::whereIn('id', $nearStartCourseOrders->pluck('id')->toArray())->update(['course_start_notify_flg' => 2]);
                }
                break;
            case 4:
                dd(qiniu_previews('微信平台开发应用规则（新）.docx'));
        }
        return 'ok!';
        //dd($courseUnpaidOrders);
    }
}
