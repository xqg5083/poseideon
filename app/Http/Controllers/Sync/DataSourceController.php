<?php

namespace App\Http\Controllers\Sync;

use App\Common\Services\PdoOperator;
use App\Http\Controllers\CommonController;
use App\Http\Model\Category;
use App\Http\Model\DataSource;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DataSourceController extends CommonController
{

    //get.admin/dataSource  全部分类列表
    public function index()
    {
//        $categorys = Category::tree();
        //$categorys = (new Category)->tree();
        $dataSources = DataSource::all();
        return view('admin.dataSource.index')->with('data',$dataSources);
    }


    /**
     * 展示数据源的表集合
     * @param $source_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //get.admin/dataSource/{$source_id}/tables  show tables
    public function tables($source_id){
        $field = DataSource::find($source_id);

        //dd($field);
        //$host, $dbschema, $db_user, $db_pwd
        $db_tables = PdoOperator::showtables($field->host, $field->db, $field->db_user, $field->db_pwd, $field->port );

        if(count($db_tables) > 0  && sizeof($db_tables) > 0){
            foreach($db_tables as $tb){
                $tb->db_id = $source_id;
            }
        }

        //dd($db_tables);

        //$data = DataSource::where('cate_pid',0)->get();
        return view('admin.dataSource.tables',compact('db_tables'));
        //return view('admin.dataSource.edit',compact('field','data'));
    }



    /**
     * 展示数据表的列集合
     * @param $source_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //get.admin/dataSource/{$db_id}/{$table_name}/showColumn  show tables
    public function showColumn($db_id, $table_name){
        //d($db_id);
        $dataSource = DataSource::find($db_id);

        //dd($field);
        //$host, $dbschema, $db_user, $db_pwd
        $db_columns = PdoOperator::showColumns($dataSource->host, $dataSource->db, $dataSource->db_user, $dataSource->db_pwd, $dataSource->port, $table_name );


        //dd($db_columns);


        //$data = DataSource::where('cate_pid',0)->get();
        return view('admin.dataSource.column',compact('db_columns'));
        //return view('admin.dataSource.edit',compact('field','data'));
    }





    /**
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * 检验连接是否可用
     */
    public function checkConn(){
        $input = Input::all();
        return PdoOperator::testConn($input['host'], $input['db'], $input['db_user'], $input['db_pwd'], $input['port'] ) ;
    }


    public function changeOrder()
    {
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类排序更新成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/dataSource/create   添加分类
    public function create()
    {
        //$data = DataSource::where('id',0)->get();
        //return view('admin/dataSource/add',compact('data'));
        return view('admin/dataSource/add');
    }

    //post.admin/dataSource  添加分类提交
    public function store()
    {
        //dd('create');
        $input = Input::except('_token');
        $rules = [
            'show_name'=>'required',
        ];

        $message = [
            'show_name.required'=>'分类名称不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            unset($input['/admin/dataSource']);
            //dd($input);
            $re = DataSource::create($input);
            if(count($re) > 0){
                return redirect('admin/dataSource');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get.admin/dataSource/{$source_id}/edit  编辑分类
    public function edit($source_id)
    {
        $field = DataSource::find($source_id);
        //$data = DataSource::where('cate_pid',0)->get();
        return view('admin.dataSource.edit',compact('field'));
        //return view('admin.dataSource.edit',compact('field','data'));
    }

    //put.admin/dataSource/{source_id}    更新分类
    public function update($source_id)
    {
        $input = Input::except('_token','_method');
        unset($input['/admin/dataSource/'.$source_id]);
        $re = DataSource::where('id',$source_id)->update($input);
        if(count($re) > 0){
            return redirect('admin/dataSource');
        }else{
            return back()->with('errors','数据源信息更新失败，请稍后重试！');
        }
    }

    //get.admin/dataSource/{source_id}  显示单个分类信息
    public function show()
    {

    }

    //delete.admin/dataSource/{source_id}   删除单个分类
    public function destroy($source_id)
    {
        $re = DataSource::where('id',$source_id)->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类删除失败，请稍后重试！',
            ];
        }
        return $data;
    }

}
