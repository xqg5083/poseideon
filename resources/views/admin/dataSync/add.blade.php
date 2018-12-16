@extends('layouts.admin')
@section('content')
        <!--面包屑导航 开始-->
<div class="crumb_warp">
    <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
    <i class="fa fa-home"></i> <a href="{{url('admin/indexall')}}">首页</a> &raquo; 添加同步任务  &raquo;  <a herf="#" onclick="history.go(-1)" >返回</a>
</div>
<!--面包屑导航 结束-->

<!--结果集标题与导航组件 开始-->
<div class="result_wrap">
    <div class="result_title">
        <h3>同步任务管理</h3>
        @if(count($errors)>0)
            <div class="mark">
                @if(is_object($errors))
                    @foreach($errors->all() as $error)
                        <p>{{$error}}</p>
                    @endforeach
                @else
                    <p>{{$errors}}</p>
                @endif
            </div>
        @endif
    </div>
    <div class="result_content">
        <div class="short_wrap">
            <a href="{{url('admin/dataTask/create')}}"><i class="fa fa-plus"></i>添加同步任务</a>
            <a href="{{url('admin/dataTask')}}"><i class="fa fa-recycle"></i>全部同步任务</a>
        </div>
    </div>
</div>
<!--结果集标题与导航组件 结束-->

<div class="result_wrap">
    <form   action="{{url('admin/dataTask')}}" method="post">
        {{csrf_field()}}
        <table class="add_tab">
            <tbody>
            <tr>
                <th width="120"><i class="require">*</i>源数据库：</th>
                <td>
                    <select name="source_id">
                        <option value="0">==源数据库==</option>
                        @foreach($dbs as $d)
                            <option value="{{$d->id}}">{{$d->show_name}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>源数据表：</th>
                <td>
                    <input type="text"  name="source_table">
                    <span><i class="fa fa-exclamation-circle yellow"></i>源数据表</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>目标库：</th>
                <td>
                    <select name="target_id">
                        <option value="0">==目标库==</option>
                        @foreach($dbs as $d)
                            <option value="{{$d->id}}">{{$d->show_name}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>目标表：</th>
                <td>
                    <input type="text"  name="target_table">
                    <span><i class="fa fa-exclamation-circle yellow"></i>目标表</span>
                </td>
            </tr>

            <tr>
                <th></th>
                <td>
                    <input type="submit" value="提交">
                    <input type="button" class="back" onclick="history.go(-1)" value="返回">
                </td>
            </tr>
            </tbody>
        </table>
    </form>


    <script>
        function checkConn(){
            $.post("{{url('admin/dataTask/checkConn')}}",{'_token':'{{csrf_token()}}'
                ,'host' : ($("input[name='host']").val())
                ,'port' : ($("input[name='port']").val())
                ,'db'   : ($("input[name='db']").val())
                ,'db_user' : ($("input[name='db_user']").val())
                ,'db_pwd' : ($("input[name='db_pwd']").val())
            },function(data){
                if(data == 'failed'){
                    layer.msg('连接不可用', {icon: 5});
                }else{
                    layer.msg('连接成功', {icon: 6});
                }
            });
        }

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


    </script>

</div>

@endsection
