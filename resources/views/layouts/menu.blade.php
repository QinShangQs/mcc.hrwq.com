<style type="text/css">
    .left_15{padding-left: 15px;}
    .left_10{padding-left: 10px;}
</style>
<aside id="sidebar" class="sidebar c-overflow">
    <ul class="main-menu">
        <li class="">
            <a href="{{route('home')}}"><i class="zmdi zmdi-home"></i>首页</a>
        </li>
        <!-- <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-accounts-alt zmdi-hc-fw"></i>用户管理</a>
            <ul>
                <li class="left_10"><a href="{{route('account.index')}}">后台账号列表</a></li>
            </ul>
        </li> -->
        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-accounts-alt zmdi-hc-fw"></i>用户管理</a>
            <ul>
                <li class="left_10"><a href="{{route('user.index')}}">用户信息管理</a></li>
                <li class="left_10"><a href="{{route('user.tutor')}}">指导师审核管理</a></li>
                <li class="left_10"><a href="{{route('user.partner')}}">合伙人审核管理</a></li>
		<li class="left_10"><a href="{{route('user.partner_cards')}}">合伙人卡片管理</a></li>
                <li class="left_10"><a href="{{route('leaveword.index')}}">留言管理</a></li>
            </ul>
        </li>
        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-view-list-alt zmdi-hc-fw"></i>产品服务管理</a>
            <ul>
                <li class="left_10"><a href="{{route('agency.index')}}">课程类别管理</a></li>
                <li class="sub-menu left_10">
                    <a>线下活动课程管理</a>
                    <ul>
                        <li class="left_15"><a href="{{route('course.index')}}">课程管理</a></li>
                        <li class="left_15"><a href="{{route('course.comment')}}">评论管理</a></li>
                        <li class="left_15"><a href="{{route('course.recommend')}}">推荐管理</a></li>
                    </ul>
                </li>
                <li class="sub-menu left_10">
                    <a>视频课程管理</a>
                    <ul>
                        <li class="left_15"><a href="{{route('vcourse.index')}}" >视频课程列表</a></li>
                        <li class="left_15"><a href="{{route('vcourse.tasks')}}">作业管理</a></li>
                        <li class="left_15"><a href="{{route('vcourse.marks')}}">笔记管理</a></li>
                        <li class="left_15"><a href="{{route('vcourse.recommend')}}">推荐管理</a></li>
                    </ul>
                </li>
                <li class="sub-menu left_10">
                    <a>壹家壹服务管理</a>
                    <ul>
                        <li class="left_15"><a href="{{route('opo.index')}}">信息维护</a></li>
                        <li class="left_15"><a href="{{route('opo.comment')}}">评论管理</a></li>
                    </ul>
                </li>
                <li class="sub-menu left_10">
                    <a>好问管理</a>
                    <ul>
                        <li class="left_15"><a href="{{route('question.tags')}}" >标签管理</a></li>
                        <li class="left_15"><a href="{{route('question.index')}}">问题榜</a></li>
                        <li class="left_15"><a href="{{route('talk.index')}}">互助榜</a></li>
                    </ul>
                </li>
                <li class="sub-menu left_10">
                    <a>和会员产品管理</a>
                    <ul>
                        <li class="left_15"><a href="{{route('vip.price_edit',['id'=>1])}}" >和会员价格维护</a></li>
                        <li class="left_15"><a href="{{route('vip.index')}}">和会员激活码维护</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-bookmark-outline zmdi-hc-fw"></i>优惠券管理</a>
            <ul>
                <li class="left_10"><a href="{{route('coupon')}}">优惠券模板</a></li>
                <li class="left_10"><a href="{{route('coupon_rule')}}">获取规则</a></li>
                <li class="left_10"><a href="{{route('coupon.record')}}">用户优惠券</a></li>
            </ul>
        </li>

        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-format-indent-increase zmdi-hc-fw"></i>订单管理</a>
            <ul>
                <li class="left_10"><a href="{{route('order.order_course')}}">好课订单</a></li>
                <li class="left_10"><a href="{{route('order.order_vcourse')}}">好看订单</a></li>
                <li class="left_10"><a href="{{route('order.order_opo')}}">壹家壹订单</a></li>
                <li class="left_10"><a href="{{route('order.order_question')}}">好问订单</a></li>
                <li class="left_10"><a href="{{route('order.order_vip')}}">和会员订单</a></li>
		<li class="left_10"><a href="{{route('order.order_tuangou')}}">团购订单</a></li>
            </ul>
        </li>

        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-money-box zmdi-hc-fw"></i>收益管理</a>
            <ul>
                <li class="left_10"><a href="{{route('income.scale')}}">收益比例</a></li>
                <li class="left_10"><a href="{{route('income.platform')}}">平台收益</a></li>
                <li class="left_10"><a href="{{route('income.user')}}">用户余额</a></li>
                <li class="left_10"><a href="{{route('income.cash')}}">提现申请</a></li>
                {{--<li class="sub-menu left_10">
                    <a>提现申请</a>
                    <ul>
                        <li class="left_15"><a href="{{route('income.cash')}}" >指导师/普通用户</a></li>
                        <li class="left_15"><a href="{{route('income.cash_partner')}}">合伙人</a></li>
                    </ul>
                </li>--}}

                <li class="left_10"><a href="{{route('income.point')}}">积分管理</a></li>
             </ul>
        </li>

        <li class="sub-menu sed_menu">
            <a><i class="zmdi zmdi-border-color zmdi-hc-fw"></i>前台管理</a>
            <ul>
                <li class="left_10"><a href="{{route('carousel')}}">轮播图管理</a></li>
                <li class="left_10"><a href="{{route('article')}}">文案管理</a></li>
                <li class="left_10"><a href="{{route('hot_search')}}">热门搜索管理</a></li>
                <li class="left_10"><a href="{{route('vcourse.sug_link')}}">视频推荐链接</a></li>   
                <li class="left_10"><a href="{{route('wechat_push.index')}}">微信推送管理</a></li>
                <li class="left_10"><a href="{{route('wechat_push.showLove')}}">爱心大使封面</a></li>
           	<li class="left_10"><a href="{{route('wechat_task.index')}}">微信任务管理</a></li>
	   </ul>
        </li>

    </ul>
</aside>
