<?php

namespace App\Http\Controllers\Sync;

use App\Common\Services\DataTaskCalcer;
use App\Common\Services\FileOp;
use App\Common\Services\PdoOperator;
use App\Common\Services\SyncOp;
use App\Http\Controllers\CommonController;
use App\Http\Model\Category;
use App\Http\Model\dataTask;
use App\Http\Model\DataSource;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DataTaskController extends CommonController
{

    //get.admin/dataTask  全部分类列表
    public function index()
    {

        //dd(SyncOp::syncData(7));

//      $categorys = Category::tree();
        //$categorys = (new Category)->tree();
        $dataTasks = DataTask::with('sourceObj')->with('targetObj')->where([])->get();
        foreach($dataTasks as $tk){
            //dd($tk->sourceObj);
            $tk->sync_type_name = Config::get('sync.SYNC_TYPE.'.$tk->sync_type.'');
            $tk->sync_status_name = Config::get('sync.SYNC_STATUS.'.$tk->sync_status.'');
        }

        //dd($dataTasks);
        return view('admin.dataTask.index')->with('data',$dataTasks);
    }




    /**
     * 根据数据表，创建同步任务
     * @param $db_id
     * @param $table_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //get.admin/dataTask/{$db_id}/{$table_name}/dataTaskBySource show tables
    public function dataTaskBySource($db_id, $table_name){
        //d($db_id);
        $dataSource = DataSource::find($db_id);



        $dbs= DataSource::all();

        $field['source_id'] = $dataSource->id;
        $field['source_table'] = $table_name;

        $field = (object)$field;
        //dd($field);

        return view('admin/dataTask/addInit',compact('dbs', 'field'));

    }




    /**
     * 新增页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //get.admin/dataTask/create   添加分类
    public function create()
    {
        $dbs= DataSource::all();

        //dd($dbs);
        return view('admin/dataTask/add',compact('dbs'));
        //return view('admin/dataTask/add');
    }

    //post.admin/dataTask  添加分类提交
    public function store()
    {
        //dd('create');
        $input = Input::except('_token');
        $rules = [
            'source_id'=>'required',
            'source_table'=>'required',
            'target_id'=>'required',
           // 'target_table'=>'required',
        ];

        $message = [
            'source_id.required'=>'源数据库不能为空！',
            'source_table.required'=>'源数据表不能为空！',
            'target_id.required'=>'目标数据库不能为空！',
           // 'target_table.required'=>'目标数据表不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            unset($input['/admin/dataTask']);
            //dd($input['target_table'] );

            if(count($input['target_table']) == 0 || $input['target_table'] == ''){
                $input['target_table'] = $input['source_table'];
            }

            //2.得出总数据量

            $sourceDb = DataSource::where('id', $input['source_id'])->first();
            $totalCt = DataTaskCalcer::caclRecords($sourceDb , $input['source_table'] ) ;
            $input['sync_total_records'] = $totalCt;

            //3.记录入db
            $re = DataTask::create($input);
            if($re){
                return redirect('admin/dataTask');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }






    //get.admin/dataTask/{$task_id}/edit  编辑分类
    public function edit($task_id)
    {
        $dbs= DataSource::all();

        $field = DataTask::find($task_id);
        return view('admin.dataTask.edit',compact('field','dbs' ) );
    }

    //put.admin/dataTask/{$task_id}    更新分类
    public function update($task_id)
    {
        $input = Input::except('_token','_method');
        unset($input['/admin/dataTask/'.$task_id]);
        //dd($input);


        if(count($input['target_table']) == 0 || $input['target_table'] == ''){
            $input['target_table'] = $input['source_table'];
        }


        //2.得出总数据量
        $sourceDb = DataSource::where('id', $input['source_id'])->first();
        $totalCt = DataTaskCalcer::caclRecords($sourceDb , $input['source_table'] ) ;
        $input['sync_total_records'] = $totalCt;


        //3.记录入db
        $re = DataTask::where('id',$task_id)->update($input);
        //dd($re);
        if(count($re) > 0){
            return redirect('admin/dataTask');
        }else{
            return back()->with('errors','同步任务更新失败，请稍后重试！');
        }
    }

    //get.admin/dataTask/{$task_id}  显示单个分类信息
    public function show()
    {

    }

    //delete.admin/dataTask/{$task_id}   删除单个分类
    public function destroy($task_id)
    {
        $re = DataTask::where('id',$task_id)->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '同步任务删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '同步任务删除失败，请稍后重试！',
            ];
        }
        return $data;
    }


    /**
     * todo
     * 校验源表和目标表的 结构是否一致
     * @param $sourceTable
     * @param $targetTable
     */
    private function validateTables($sourceTable, $targetTable){

    }

}
