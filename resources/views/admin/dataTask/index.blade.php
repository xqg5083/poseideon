@extends('layouts.admin')
@section('content')
        <!--面包屑导航 开始-->
<div class="crumb_warp">
    <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
    <i class="fa fa-home"></i> <a href="{{url('admin/indexall')}}">首页</a> &raquo; 全部同步任务  &raquo;  <a herf="#" onclick="history.go(-1)" >返回</a>
</div>
<!--面包屑导航 结束-->

{{--<!--结果页快捷搜索框 开始-->--}}
{{--<div class="search_wrap">--}}
    {{--<form action="" method="post">--}}
        {{--<table class="search_tab">--}}
            {{--<tr>--}}
                {{--<th width="120">选择分类:</th>--}}
                {{--<td>--}}
                    {{--<select onchange="javascript:location.href=this.value;">--}}
                        {{--<option value="">全部</option>--}}
                        {{--<option value="http://www.baidu.com">百度</option>--}}
                        {{--<option value="http://www.sina.com">新浪</option>--}}
                    {{--</select>--}}
                {{--</td>--}}
                {{--<th width="70">关键字:</th>--}}
                {{--<td><input type="text" name="keywords" placeholder="关键字"></td>--}}
                {{--<td><input type="submit" name="sub" value="查询"></td>--}}
            {{--</tr>--}}
        {{--</table>--}}
    {{--</form>--}}
{{--</div>--}}
{{--<!--结果页快捷搜索框 结束-->--}}

<!--搜索结果页面 列表 开始-->
<form action="#" method="post">
    <div class="result_wrap">
        <div class="result_title">
            <h3>同步任务管理</h3>
        </div>
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/dataTask/create')}}"><i class="fa fa-plus"></i>添加同步任务</a>

                <a href="{{url('admin/dataTask')}}"><i class="fa fa-recycle"></i>全部同步任务</a>
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>

    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab">
                <tr>
                    <th class="tc" width="15%">源数据库</th>
                    <th class="tc" width="5%">源数据表</th>
                    <th>目标库</th>
                    <th>目标表</th>

                    <th>同步方式</th>
                    <th>同步状态</th>
                    <th>待同步数据量</th>
                    <th>已同步数据量</th>

                    <th>操作</th>
                </tr>

                @foreach($data as $v)
                <tr>
                    <td class="tc" width="15%" >
                          {{$v->sourceObj->show_name}}
                    </td>
                    <td class="tc">{{$v->source_table}}</td>
                    <td>{{$v->targetObj->show_name}}</td>
                    <td>{{$v->target_table}}</td>


                    <td>{{$v->sync_type_name}}</td>
                    <td>{{$v->sync_status_name}}</td>
                    <td>{{$v->sync_total_records}}</td>
                    <td>{{$v->sync_exec_records}}</td>



                    <td>
                        @if($v->sync_status == 0)
                        <a href="{{url('admin/dataTask/'.$v->id.'/edit')}}">修改</a>
                        <a href="javascript:;" onclick="delSource({{$v->id}})">删除</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>
</form>
<!--搜索结果页面 列表 结束-->

<script>
    function changeOrder(obj,cate_id){
        var cate_order = $(obj).val();
        $.post("{{url('admin/dataTask/changeorder')}}",{'_token':'{{csrf_token()}}','cate_id':cate_id,'cate_order':cate_order},function(data){
            if(data.status == 0){
                layer.msg(data.msg, {icon: 6});
            }else{
                layer.msg(data.msg, {icon: 5});
            }
        });
    }

    //删除分类
    function delSource(source_id) {
        layer.confirm('您确定要删除这个同步任务吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post("{{url('admin/dataTask/')}}/"+source_id,{'_method':'delete','_token':"{{csrf_token()}}"},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
//            layer.msg('的确很重要', {icon: 1});
        }, function(){

        });
    }



</script>

@endsection
