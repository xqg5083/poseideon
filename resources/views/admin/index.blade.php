@extends('layouts.admin')
@section('content')




	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">后台管理模板</div>
			<ul>
				<li><a href="{{url('admin/indexall')}}" class="active">首页</a></li>
				<li><a href="#">管理页</a></li>
			</ul>
		</div>
		<div class="top_right">
			<ul>
				<li>管理员：admin</li>
				<li><a href="#" target="main">修改密码</a></li>
				<li><a href="{{url('admin/quit')}}">退出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
            <li>
            	<h3><i class="fa fa-fw fa-clipboard"></i>数据配置</h3>
                <ul class="sub_menu">
                    {{--<li><a href="{{url('admin/category')}}" target="main"><i class="fa fa-fw fa-plus-square"></i>添加页</a></li>--}}
                    <li><a href="{{url('admin/dataSource')}}" target="main"><i class="fa fa-fw fa-list-ul"></i>数据源</a></li>
                    <li><a href="{{url('admin/dataTask')}}" target="main"><i class="fa fa-fw fa-list-alt"></i>同步配置</a></li>
                </ul>
            </li>
            <li>
            	<h3><i class="fa fa-fw fa-cog"></i>同步管理</h3>
                <ul class="sub_menu">
                    <li><a href="{{url('admin/dataSync')}}" target="main"><i class="fa fa-fw fa-cubes"></i>同步管理</a></li>
                </ul>
            </li>
            {{--<li>--}}
            	{{--<h3><i class="fa fa-fw fa-thumb-tack"></i>工具导航</h3>--}}
                {{--<ul class="sub_menu">--}}
                    {{--<li><a href="http://www.yeahzan.com/fa/facss.html" target="main"><i class="fa fa-fw fa-font"></i>图标调用</a></li>--}}
                    {{--<li><a href="http://hemin.cn/jq/cheatsheet.html" target="main"><i class="fa fa-fw fa-chain"></i>Jquery手册</a></li>--}}
                    {{--<li><a href="http://tool.c7sky.com/webcolor/" target="main"><i class="fa fa-fw fa-tachometer"></i>配色板</a></li>--}}
                    {{--<li><a href="" target="main"><i class="fa fa-fw fa-tags"></i>其他组件</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        </ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="{{url('admin/info')}}" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2018. Powered By <a href="www.newstrong.info">www.newstrong.info</a>
	</div>
	<!--底部 结束-->


@endsection