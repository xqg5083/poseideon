<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/12/4
 * Time: 上午10:34
 */

namespace App\Common\Services;


class FileOp
{

    // 生成文件名
    public static function genFile($fname) {
        return base_path()."/".$fname;
    }


    // 判断文件是否存在(带刷新)
    public static function dis_file($f)
    {
        clearstatcache();
        return is_file($f);
    }


}