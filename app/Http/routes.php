<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware'=>'auth', 'uses'=>'HomeController@index'])->name('home');

Route::get('/test', 'TestController@index')->name('test');

Route::get('/login', 'AdminController@getLogin')->name('admin.login');
Route::post('/login', 'AdminController@postLogin')->name('admin.auth');
Route::get('/logout', ['uses'=>'AdminController@getLogout', 'as'=>'admin.logout']);

Route::group(['middleware' => 'auth'],function (){
    Route::post('/reset-password', ['uses'=>'AdminController@postPassword', 'as'=>'admin.password']);

    Route::group(['prefix'=>'stat'], function (){
        Route::post('/user', 'HomeController@stat_user')->name('stat.user');
        Route::post('/order', 'HomeController@stat_order')->name('stat.order');
    });

    /** 后台账户管理 */
    Route::group(['prefix'=>'account'], function (){
        Route::get('/', 'AccountController@index')->name('account.index');
    });

    /* 用户管理*/
    Route::group(['prefix'=>'user'], function (){
        // 用户信息管理
        Route::get('/', 'UserController@index')->name('user.index');
        Route::get('/edit/{id}', 'UserController@edit')->name('user.edit');
        Route::post('/edit/{id}', 'UserController@update')->name('user.update');
        Route::get('/show/{id}','UserController@show')->name('user.show');
        Route::post('/block','UserController@block')->name('user.block');
        Route::post('/unlock','UserController@unlock')->name('user.unlock');
        Route::post('/upload','UserController@uploadImages')->name('user.upload');
        Route::post('/getcitylist/{id}', 'UserController@getcitylist')->name('user.getcitylist');
        Route::get('/partner_cards', 'UserController@partnerCards')->name('user.partner_cards');
        Route::get('/partner_card_show/{user_id}', 'UserController@partnerCardShow')->name('user.partner_card_show');
        
        Route::get('/leftday/{user_id}', 'UserController@leftday_show')->name('user.leftday');
        
        Route::get('/partner_card_whites', 'UserController@partnerCardWhites')->name('user.partner_card_whites');
        Route::post('/partner_card_whites_create', 'UserController@partnerCardWhitesCreate')->name('user.partner_card_whites_create');
        Route::post('/partner_card_whites_remove', 'UserController@partnerCardWhitesRemove')->name('user.partner_card_whites_remove');

        // 指导师审核管理
        Route::get('/tutor', 'TutorController@index')->name('user.tutor');
        Route::get('/tutor_check/{id}', 'TutorController@check')->name('user.tutor_check');
        Route::post('/tutor_pass/{id}', 'TutorController@pass')->name('user.tutor_pass');
        Route::post('/tutor_reject/{id}', 'TutorController@reject')->name('user.tutor_reject');

        // 合伙人审核管理
        Route::get('/partner', 'UserController@partner_list')->name('user.partner');
        Route::get('/partner_check/{id}', 'UserController@partner_check')->name('user.partner_check');
        Route::post('/partner_pass/{id}', 'UserController@partner_pass')->name('user.partner_pass');
        Route::post('/partner_reject/{id}', 'UserController@partner_reject')->name('user.partner_reject');
        Route::post('/partner_frozen/{id}', 'UserController@partner_frozen')->name('user.partner_frozen');
        Route::post('/partner_already/{id}', 'UserController@partner_already')->name('user.partner_already');

    });

    /** 课程类别管理 */
    Route::group(['prefix'=>'agency'], function (){
        Route::get('/', 'AgencyController@index')->name('agency.index');
        Route::get('/show/{id}', 'AgencyController@show')->name('agency.show');
        Route::get('/edit/{id}', 'AgencyController@edit')->name('agency.edit');
        Route::post('/edit/{id}', 'AgencyController@update')->name('agency.update');
        Route::post('/delete', 'AgencyController@delete')->name('agency.delete');
        Route::get('/create', 'AgencyController@create')->name('agency.create');
        Route::post('/create', 'AgencyController@store')->name('agency.store');

    });
    
    /** 留言管理 */
    Route::group(['prefix'=>'leaveword'], function (){
    	Route::get('/', 'LeaveWordController@index')->name('leaveword.index');
    	Route::get('/show/{id}', 'LeaveWordController@show')->name('leaveword.show');
    	Route::post('/delete', 'LeaveWordController@delete')->name('leaveword.delete');
    });
    
    /** 微信推送管理 */
    Route::group(['prefix'=>'wechat_push'], function (){
    	Route::get('/', 'WechatPushController@index')->name('wechat_push.index');
    	Route::get('/create', 'WechatPushController@create')->name('wechat_push.create');
    	Route::post('/store', 'WechatPushController@store')->name('wechat_push.store');
    	Route::post('/delete', 'WechatPushController@delete')->name('wechat_push.delete');
        
        Route::get('/lovebg', 'WechatPushController@showLove')->name('wechat_push.showLove');
        Route::post('/lovebg/edit', 'WechatPushController@updateLove')->name('wechat_push.updateLove');
    });
    
    /** 微信任务管理 */
    Route::group(['prefix'=>'wechat_task'], function (){
    	Route::get('/', 'WechatTaskController@index')->name('wechat_task.index');
    	Route::get('/create', 'WechatTaskController@create')->name('wechat_task.create');
        Route::get('/detail/{id}', 'WechatTaskController@detail')->name('wechat_task.detail');
        Route::post('/test', 'WechatTaskController@sendTest')->name('wechat_task.test');
    	Route::post('/store', 'WechatTaskController@store')->name('wechat_task.store');
    	Route::post('/delete', 'WechatTaskController@delete')->name('wechat_task.delete');
    });

    /* 线下活动课程管理*/
    Route::group(['prefix'=>'course'], function (){
        // 课程管理
        Route::get('/', 'CourseController@index')->name('course.index');
        Route::get('/create', 'CourseController@create')->name('course.create');
        Route::post('/create', 'CourseController@store')->name('course.store');
        Route::get('/edit/{id}', 'CourseController@edit')->name('course.edit');
        Route::post('/edit/{id}', 'CourseController@update')->name('course.update');
        Route::post('/delete/{id}', 'CourseController@delete')->name('course.delete');
        Route::get('/show/{id}','CourseController@show')->name('course.show');
        Route::post('/upload','CourseController@uploadImages')->name('course.upload');
        Route::post('/release','CourseController@release')->name('course.release');
        Route::post('/off','CourseController@off')->name('course.off');
        Route::post('/on','CourseController@on')->name('course.on');

        // 评论管理
        Route::get('/comment', 'CourseController@comment_list')->name('course.comment');
        Route::post('/comment_delete/{id}', 'CourseController@comment_delete')->name('course.comment_delete');
        Route::get('/comment_show/{id}','CourseController@comment_show')->name('course.comment_show');

        // 推荐管理
        Route::get('/recommend', 'CourseController@recommend_list')->name('course.recommend');
        Route::get('/recommend_create', 'CourseController@recommend_create')->name('course.recommend_create');
        Route::post('/recommend_create', 'CourseController@recommend_store')->name('course.recommend_store');
        Route::post('/recommend_cancel/{id}', 'CourseController@recommend_cancel')->name('course.recommend_cancel');
        /** 开课前提醒 */
        Route::any('/send-notify', 'CourseController@sendNotify')->name('course.send.notify');

    });

    /** 视频课程管理 */
    Route::group(['prefix'=>'vcourse'], function (){
        Route::get('/', 'VcourseController@index')->name('vcourse.index');
        Route::get('/show/{id}', 'VcourseController@show')->name('vcourse.show');
        Route::get('/edit/{id}', 'VcourseController@edit')->name('vcourse.edit');
        Route::post('/edit/{id}', 'VcourseController@update')->name('vcourse.update');
        Route::post('/delete', 'VcourseController@delete')->name('vcourse.delete');
        Route::get('/create', 'VcourseController@create')->name('vcourse.create');
        Route::post('/create', 'VcourseController@store')->name('vcourse.store');
        Route::post('/upload','VcourseController@uploadImages')->name('vcourse.upload');
        Route::post('/release','VcourseController@release')->name('vcourse.release');
        Route::post('/off','VcourseController@off')->name('vcourse.off');
        Route::post('/on','VcourseController@on')->name('vcourse.on');

        /** 作业管理 */
        Route::get('/tasks', 'VcourseController@task_list')->name('vcourse.tasks');
        Route::get('/task_show/{id}', 'VcourseController@task_show')->name('vcourse.task_show');
        Route::post('/task_delete', 'VcourseController@task_delete')->name('vcourse.task_delete');\

        /** 笔记管理 */
        Route::get('/marks', 'VcourseController@mark_list')->name('vcourse.marks');
        Route::get('/mark_show/{id}', 'VcourseController@mark_show')->name('vcourse.mark_show');
        Route::post('/mark_delete', 'VcourseController@mark_delete')->name('vcourse.mark_delete');

        /** 推荐管理 */
        Route::get('/recommend', 'VcourseController@recommend_list')->name('vcourse.recommend');
        Route::post('/recommend_cancel', 'VcourseController@recommend_cancel')->name('vcourse.recommend_cancel');
        Route::get('/recommend_create', 'VcourseController@recommend_create')->name('vcourse.recommend_create');
        Route::post('/recommend_create', 'VcourseController@recommend_store')->name('vcourse.recommend_store');
        Route::any('/qiniu_uptoken', 'VcourseController@qiniu_uptoken')->name('vcourse.qiniu_uptoken');
        Route::any('/qiniu_notify', 'VcourseController@qiniu_notify')->name('vcourse.qiniu_notify');
        Route::get('/pfop_status', 'VcourseController@pfop_status')->name('vcourse.pfop_status');
        Route::any('/qiniu_delete', 'VcourseController@qiniu_delete')->name('vcourse.qiniu_delete');
        
        Route::get('/sug_link', 'VcourseController@sug_link')->name('vcourse.sug_link');
        Route::post('/sug_link_create', 'VcourseController@sug_link_create')->name('vcourse.sug_link_create');
    });

    /** 订单管理 */
    Route::group(['prefix'=>'order'], function (){
        /** 好课订单 */
        Route::get('/order_course', 'OrderController@order_course')->name('order.order_course');
        Route::get('/order_course_show/{id}', 'OrderController@order_course_show')->name('order.order_course_show');
        Route::post('/order_course_show/{id}', 'OrderController@order_course_update')->name('order.order_course_update');
        /** 好看订单 */
        Route::get('/order_vcourse', 'OrderController@order_vcourse')->name('order.order_vcourse');
        Route::get('/order_vcourse_show/{id}', 'OrderController@order_vcourse_show')->name('order.order_vcourse_show');
        /** 壹家壹订单 */
        Route::get('/order_opo', 'OrderController@order_opo')->name('order.order_opo');
        Route::get('/order_opo_show/{id}', 'OrderController@order_opo_show')->name('order.order_opo_show');
        Route::post('/order_opo_show/{id}', 'OrderController@order_opo_update')->name('order.order_opo_update');
        /** 好问订单 */
        Route::get('/order_question', 'OrderController@order_question')->name('order.order_question');
        Route::get('/order_question_show/{id}', 'OrderController@order_question_show')->name('order.order_question_show');
        /** 和会员订单 */
        Route::get('/order_vip', 'OrderController@order_vip')->name('order.order_vip');
        Route::get('/order_vip_show/{id}', 'OrderController@order_vip_show')->name('order.order_vip_show');
        Route::post('/order_vip_show/{id}', 'OrderController@order_vip_update')->name('order.order_vip_update');

        Route::any('/qiniu_uptoken', 'OrderController@qiniu_uptoken')->name('order.qiniu_uptoken');
        Route::any('/qiniu_delete', 'OrderController@qiniu_delete')->name('order.qiniu_delete');
        Route::post('/upload','OrderController@uploadImages')->name('order.upload');
        Route::post('/order/vip_remove','OrderController@vipRemove')->name('order.vip_remove');
        
        Route::get('/tuangou', 'OrderController@order_tuangou')->name('order.order_tuangou');
    });

    /* 壹家壹服务管理*/
    Route::group(['prefix'=>'opo'], function (){
        // 信息维护
        Route::get('/', 'OpoController@index')->name('opo.index');
        Route::get('/create', 'OpoController@create')->name('opo.create');
        Route::post('/create', 'OpoController@store')->name('opo.store');
        Route::get('/edit/{id}', 'OpoController@edit')->name('opo.edit');
        Route::post('/edit/{id}', 'OpoController@update')->name('opo.update');
        Route::post('/delete/{id}', 'OpoController@delete')->name('opo.delete');
        Route::get('/show/{id}','OpoController@show')->name('opo.show');
        Route::post('/upload','OpoController@uploadImages')->name('opo.upload');

        // 评论管理
        Route::get('/comment', 'OpoController@comment_list')->name('opo.comment');
        Route::post('/comment_delete/{id}', 'OpoController@comment_delete')->name('opo.comment_delete');
        Route::get('/comment_show/{id}','OpoController@comment_show')->name('opo.comment_show');
    });

    /* 和会员产品管理*/
    Route::group(['prefix'=>'vip'], function (){
        // 和会员激活码管理
        Route::get('/', 'VipController@index')->name('vip.index');
        Route::get('/create', 'VipController@create')->name('vip.create');
        Route::post('/create', 'VipController@store')->name('vip.store');
        Route::get('/edit/{id}', 'VipController@edit')->name('vip.edit');
        Route::post('/edit/{id}', 'VipController@update')->name('vip.update');
        Route::post('/delete/{id}', 'VipController@delete')->name('vip.delete');
        Route::post('/upload','VipController@uploadImages')->name('vip.upload');
        Route::get('/import','VipController@import')->name('vip.import');
        Route::post('/import','VipController@do_import')->name('vip.do_import');

        Route::get('/price_edit/{id}', 'VipController@price_edit')->name('vip.price_edit');
        Route::post('/price_edit/{id}', 'VipController@price_update')->name('vip.price_update');

    });

    Route::group(['prefix'=>'question'], function (){
        /** 问题榜 */
        Route::get('/', 'QuestionController@index')->name('question.index');
        Route::get('/show/{id}', 'QuestionController@show')->name('question.show');
        Route::get('/edit/{id}', 'QuestionController@edit')->name('question.edit');
        Route::post('/edit/{id}', 'QuestionController@update')->name('question.update');
        Route::post('/delete', 'QuestionController@delete')->name('question.delete');
        Route::post('/restore', 'QuestionController@restore')->name('question.restore');

        /** 标签管理 */
        Route::get('/tags', 'QuestionController@tag_list')->name('question.tags');
        Route::get('/tag_create', 'QuestionController@tag_create')->name('question.tag_create');
        Route::get('/tag_edit/{id}', 'QuestionController@tag_edit')->name('question.tag_edit');
        Route::post('/tag_create', 'QuestionController@tag_store')->name('question.tag_store');
        Route::post('/tag_edit/{id}', 'QuestionController@tag_update')->name('question.tag_update');
        Route::post('/tag_delete', 'QuestionController@tag_delete')->name('question.tag_delete');

    });

    /** 互助榜 */
    Route::group(['prefix'=>'talk'], function (){
        Route::get('/', 'TalkController@index')->name('talk.index');
        Route::get('/show/{id}', 'TalkController@show')->name('talk.show');
        Route::get('/edit/{id}', 'TalkController@edit')->name('talk.edit');
        Route::post('/edit/{id}', 'TalkController@update')->name('talk.update');
        Route::post('/delete', 'TalkController@delete')->name('talk.delete');
        Route::post('/restore', 'TalkController@restore')->name('talk.restore');
        //评论s
        Route::get('/comments/{id}', 'TalkController@comments')->name('talk.comments');
        Route::post('/comment_delete', 'TalkController@comment_delete')->name('talk.comment_delete');
    });

    /** 轮播图管理 */
    Route::group(['prefix'=>'carousel'], function (){
        Route::get('/', 'CarouselController@index')->name('carousel');
        Route::get('/create', 'CarouselController@create')->name('carousel.create');
        Route::post('/store', 'CarouselController@store')->name('carousel.store');
        Route::get('/edit/{id}', 'CarouselController@edit')->name('carousel.edit');
        Route::post('/edit/{id}', 'CarouselController@update')->name('carousel.update');
        Route::post('/delete', 'CarouselController@delete')->name('carousel.delete');
        Route::post('/upload','CarouselController@uploadImages')->name('carousel.upload');
    });
    
    /** 广告管理 */
    Route::group(['prefix'=>'ad'], function (){
        Route::get('/', 'AdController@index')->name('ad');
        Route::get('/create', 'AdController@create')->name('ad.create');
        Route::post('/store', 'AdController@store')->name('ad.store');
        Route::get('/edit/{id}', 'AdController@edit')->name('ad.edit');
        Route::post('/edit/{id}', 'AdController@update')->name('ad.update');
        Route::post('/show', 'AdController@isShow')->name('ad.show');
        Route::post('/delete', 'AdController@delete')->name('ad.delete');
    });

    /** 文章管理 */
    Route::group(['prefix'=>'article'], function (){
        Route::get('/', 'ArticleController@index')->name('article');
        Route::get('/create', 'ArticleController@create')->name('article.create');
        Route::post('/store', 'ArticleController@store')->name('article.store');
        Route::get('/edit/{id}', 'ArticleController@edit')->name('article.edit');
        Route::post('/edit/{id}', 'ArticleController@update')->name('article.update');
        Route::post('/delete', 'ArticleController@destroy')->name('article.delete');
        Route::get('/show/{id}', 'ArticleController@show')->name('article.show');
    });

    /** 热门搜索 */
    Route::group(['prefix'=>'hot_search'], function (){
        Route::get('/', 'HotSearchController@index')->name('hot_search');
        Route::get('/create', 'HotSearchController@create')->name('hot_search.create');
        Route::post('/store', 'HotSearchController@store')->name('hot_search.store');
        Route::get('/edit/{id}', 'HotSearchController@edit')->name('hot_search.edit');
        Route::post('/edit/{id}', 'HotSearchController@update')->name('hot_search.update');
        Route::post('/delete', 'HotSearchController@delete')->name('hot_search.delete');
        Route::get('/show/{id}', 'HotSearchController@show')->name('hot_search.show');
    });

    /** 优惠券 模板 发放 */
    Route::group(['prefix'=>'coupon'], function (){
        Route::get('/', 'CouponController@index')->name('coupon');
        Route::get('/create', 'CouponController@create')->name('coupon.create');
        Route::post('/store', 'CouponController@store')->name('coupon.store');
        Route::get('/edit/{id}', 'CouponController@edit')->name('coupon.edit');
        Route::post('/edit/{id}', 'CouponController@update')->name('coupon.update');
        Route::post('/delete', 'CouponController@delete')->name('coupon.delete');
        Route::get('/show/{id}', 'CouponController@show')->name('coupon.show');

        Route::post('/select_city', 'CouponController@select_city')->name('coupon.select_city');
        Route::get('/distribute/{id}', 'CouponController@distribute')->name('coupon.distribute');
        Route::post('/distribute_selected', 'CouponController@distribute_selected')->name('coupon.distribute_selected');
        Route::get('/record', 'CouponController@record')->name('coupon.record');
        Route::post('/record_delete', 'CouponController@record_delete')->name('coupon.record_delete');

        Route::post('/select_agency', 'CouponController@select_agency')->name('coupon.select_agency');
        Route::post('/select_course', 'CouponController@select_course')->name('coupon.select_course');
    });

    /** 优惠券获取规则 */
    Route::group(['prefix'=>'coupon_rule'], function (){
        Route::get('/', 'CouponRuleController@index')->name('coupon_rule');
        Route::get('/create', 'CouponRuleController@create')->name('coupon_rule.create');
        Route::post('/store', 'CouponRuleController@store')->name('coupon_rule.store');
        Route::get('/edit/{id}', 'CouponRuleController@edit')->name('coupon_rule.edit');
        Route::post('/edit/{id}', 'CouponRuleController@update')->name('coupon_rule.update');
        Route::post('/delete', 'CouponRuleController@delete')->name('coupon_rule.delete');
        Route::post('/restore', 'CouponRuleController@restore')->name('coupon_rule.restore');
        Route::get('/show/{id}', 'CouponRuleController@show')->name('coupon_rule.show');
    });


    /** 收益管理 */
    Route::group(['prefix'=>'income'], function (){
        Route::get('/scale', 'IncomeController@scale_index')->name('income.scale');
        Route::get('/scale_add', 'IncomeController@scale_add')->name('income.scale_add');
        Route::post('/scale_store', 'IncomeController@scale_store')->name('income.scale_store');
        Route::get('/scale_edit/{id}', 'IncomeController@scale_edit')->name('income.scale_edit');
        Route::post('/scale_edit/{id}', 'IncomeController@scale_update')->name('income.scale_update');

        Route::get('/point', 'IncomeController@point')->name('income.point');
        Route::post('/point_update', 'IncomeController@point_update')->name('income.point_update');
        Route::get('/point_show/{id}', 'IncomeController@point_show')->name('income.point_show');
        Route::get('/point_empty', 'IncomeController@point_empty')->name('income.point_empty');

        Route::get('/platform', 'IncomeController@platform')->name('income.platform');
        Route::get('/platform_show/{id}', 'IncomeController@platform_show')->name('income.platform_show');
        Route::post('/platform_log', 'IncomeController@platform_log')->name('income.platform_log');

        Route::get('/withdraw', 'WithdrawController@index')->name('income.cash');
        //Route::get('/cash_partner', 'IncomeController@cash_partner')->name('income.cash_partner');
        Route::get('/withdraw-show/{id}', 'WithdrawController@show')->name('income.cash_show');
        //Route::get('/cash_partner_show/{id}', 'IncomeController@cash_partner_show')->name('income.cash_partner_show');
        //Route::post('/cash_refuse', 'IncomeController@cash_refuse')->name('income.cash_refuse');
        //Route::post('/cash_partner_log', 'IncomeController@cash_partner_log')->name('income.cash_partner_log');
        Route::post('/withdraw-bulk-approve', 'WithdrawController@bulkApprove')->name('income.withdraw.bulk.approve');
        Route::post('/withdraw-approve', 'WithdrawController@approve')->name('income.withdraw.approve');
        Route::post('/withdraw-reject', 'WithdrawController@reject')->name('income.withdraw.reject');

        Route::get('/user', 'IncomeController@user')->name('income.user');
        Route::get('/user_show/{id}', 'IncomeController@user_show')->name('income.user_show');
        Route::post('/user_balance_mod', 'IncomeController@user_balance_mod')->name('income.user_balance_mod');
    });

});