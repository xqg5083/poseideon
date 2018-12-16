<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 数据迁移任务表
 *
 * Class DataTask
 * @package App\Http\Model
 */
class DataTask extends Model
{
    //

    protected $table="data_task";
    protected $primaryKey = "id";
    public $timestamps=false;



    protected $fillable = ['source_id', 'source_table', 'target_id', 'target_table', 'sync_total_records', 'sync_exec_records'];


    public function sourceObj(){
        return $this->hasone('App\Http\Model\DataSource',  'id', 'source_id');
    }

    public function targetObj(){
        return $this->hasone('App\Http\Model\DataSource', 'id', 'target_id');
    }


}
