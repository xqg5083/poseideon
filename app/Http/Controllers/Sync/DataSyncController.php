<?php

namespace App\Http\Controllers\Sync;

use App\Common\Services\DataTaskCalcer;
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

class DataSyncController extends CommonController
{

    //get.admin/dataSync  全部分类列表
    public function index()
    {
//        $categorys = Category::tree();
        //$categorys = (new Category)->tree();
        $dataTasks = DataTask::with('sourceObj')->with('targetObj')->where([])->get();
        foreach($dataTasks as $tk){
            //dd($tk->sourceObj);
            $tk->sync_type_name = Config::get('sync.SYNC_TYPE.'.$tk->sync_type.'');
            $tk->sync_status_name = Config::get('sync.SYNC_STATUS.'.$tk->sync_status.'');
        }

        //dd($dataTasks);
        return view('admin.dataSync.index')->with('data',$dataTasks);
    }


    //get.admin/dataSync/{$task_id}/edit  编辑分类
    public function edit($task_id)
    {
        $dbs= DataSource::all();

        $field = DataTask::find($task_id);
        return view('admin.dataSync.edit',compact('field','dbs' ) );
    }

    //post.admin/dataSync/invokeSync    更新分类
    public function invokeSync()
    {
        $input = Input::all();

        //任务Id
        $task_id = $input['task_id'] ;

        //开启同步操作
        $re = SyncOp::syncData($task_id);


        //同步结束后，更新同步结果
        $this->afterSync($task_id);

        //dd($re);
        if($re){
            $data = [
                'status' => 0,
                'msg' => '数据同步成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '数据同步失败，请稍后重试！',
            ];
        }
        return $data;
    }


    private function afterSync($task_id){
        $task = DataTask::where('id', $task_id)->first();
        //dd($task);
        //2.得出总数据量
        $targetDb = DataSource::where('id', $task->target_id)->first();
        $totalCt = DataTaskCalcer::caclRecords($targetDb , $task->target_table ) ;

        //dd($totalCt);
        $input['id'] = $task_id;
        $input['sync_exec_records'] = $totalCt;
        $input['sync_status'] = 2;
        //3.记录入db
        $re = DataTask::where('id',$task_id)->update($input);
    }



    //get.admin/dataSync/{$task_id}  显示单个分类信息
    public function show()
    {

    }


}
