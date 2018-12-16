<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/11/30
 * Time: 下午5:31
 */

namespace App\Common\Services;
use App\Http\Model\DataSource;
use App\Http\Model\DataTask;


/**
 * php通过sqlloader，进行数据同步
 * Class PdoOperator
 * @package App\Common\Services
 */
class SyncOp
{

    /**
     * 数据同步
     *
     * @param $dataTask
     */
    public static function syncData($dataTaskId){
        $result = "failed";

        $dataTask = DataTask::where('id' ,$dataTaskId )->first();

        //dd($dataTask);

        //1. 同步表结构
        //源数据库
        $sourcedb = DataSource::where('id', $dataTask->source_id)->first();
        //目标库
        $targetdb = DataSource::where('id', $dataTask->target_id)->first();

        $sourcePdo = PdoOperator::prepareBySource($sourcedb);
        $targetPdo = PdoOperator::prepareBySource($targetdb);


        $res = self::pdo_execute( $targetPdo ,"DESCRIBE ".$dataTask->source_table);
        if (empty($res)) {
            //dd(1);
            //获取建表语句
            $create_sql = self::get_create_sql($sourcePdo, $dataTask->source_table);
            //dd($create_sql);
            //同步数据表结构
            $st = $targetPdo->exec($create_sql);
        }else{
            //return $result;
        }


        //2. 从ods表中导出数据到文件
        $fileName = "ods-".$sourcedb->db."-".($dataTask->source_table ).mt_rand(1000,9999).".xls";

        //$fileName = "ods-source_db-demo_table1454.xls";

        $datafile = FileOp::genFile($fileName);
        if (FileOp::dis_file($datafile)) {
            //throw new Exception("ods_table_has_dumped [$sourcedb->db.$dataTask->source_table]");
            //return;     //!< 数据文件存在说明已经同步过,直接退出
        }
        if (! FileOp::dis_file($datafile)) {
            $sql = "SELECT * FROM $dataTask->source_table";
            PdoOperator::export_file($sourcedb,$sql,$datafile);
        }

        //dd(3);
        //3.导入文件到数据表
        PdoOperator::import_file($fileName,$targetdb,$dataTask->target_table,true);

        $result = 'success';
        return $result;

    }





    /**
     * 获取建表语句
     *
     * @param $sourcePdo
     * @param $table
     * @return bool|mixed|null|string
     */
    private static function get_create_sql($sourcePdo, $table){

        $sql = " SHOW CREATE TABLE $table ";
        $create_sql = null;

        $st = $sourcePdo->prepare($sql);
        $st->setFetchMode(\PDO::FETCH_BOTH);
        $st->execute();
        $rows = $st->fetchAll();

        if(empty($rows)){
            //todo
            //源数据表不存在
            return null;
        }else{
            //处理建表语句,发现不同mysql版本支持的建表语句不同,这里做下适配
            $create_sql =  self::filter_create_sql($rows[0][1]);
        }

        return $create_sql;
    }


    /**
     * pdo 执行sql
     * @param $pdo
     * @param $sql
     * @return mixed
     */
    private static function pdo_execute($pdo, $sql){
        $st = $pdo->prepare($sql);
        $st->setFetchMode(\PDO::FETCH_BOTH);
        $st->execute();
        $rows = $st->fetchAll();
        //dd($rows);
        return $rows;
    }





    /**
     * 处理建表语句,发现不同mysql版本支持的建表语句不同,这里做下适配
     *
     * @param $sql
     * @return bool|mixed|string
     */
    private static function filter_create_sql($sql){
        //2. 处理建表语句,发现不同mysql版本支持的建表语句不同,这里做下适配
        $pos = strpos($sql,'CREATE TABLE');
        $sql = substr($sql, $pos);
        $sql = str_replace('CREATE TABLE','CREATE TABLE IF NOT EXISTS', $sql);
        $sql = str_replace('ON UPDATE CURRENT_TIMESTAMP','', $sql);
        $sql = str_replace('CURRENT_TIMESTAMP',"'1971-01-01 08:00:00'", $sql);
        $sql = str_replace('COLLATE utf8_bin','', $sql);  //!< 去除二进制显示
        $sql = str_replace('COLLATE=utf8_bin','', $sql);  //!< 去除二进制显示
        $sql = str_replace('COLLATE utf8mb4_bin','', $sql);  //!< 去除二进制显示
        $sql = str_replace('COLLATE=utf8mb4_bin','', $sql);  //!< 去除二进制显示
        $sql = str_replace('utf8mb4','utf8', $sql);  //!< 去除二进制显示
        $sql = str_replace("\n",'', $sql);
        $sql = str_replace("\r",'', $sql);
        $sql = preg_replace("/InnoDB/i", "MyISAM", $sql);

        //dd($sql);
        return $sql;

    }




}