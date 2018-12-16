<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/12/4
 * Time: 下午2:41
 */

namespace App\Common\Services;


class DataTaskCalcer
{

    public static function caclRecords($dataSource, $table){
        $pdo_dinamic = PdoOperator::prepareBySource($dataSource);
        $sql = " select count(1) as total from  $table ";

        $st = $pdo_dinamic->prepare($sql);
        $st->setFetchMode(\PDO::FETCH_OBJ);
        $st->execute();
        $rows = $st->fetchAll();

        return (int)($rows[0]->total);
    }



}