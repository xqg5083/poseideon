<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/11/30
 * Time: 下午5:31
 */

namespace App\Common\Services;


/**
 * 动态数据源操作
 * Class PdoOperator
 * @package App\Common\Services
 */
class PdoOperator
{

    /**
     * 动态数据源
     *
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * @return PDO
     */
    public static function prepareBySource($dataSrouce){
        $mysql_conf = self::get_dbconf($dataSrouce);

        $dsn = "mysql:host=".$mysql_conf['host'].";port=".$mysql_conf['port'].";dbname=".$mysql_conf['db'];
        $pdo_dinamic = new \PDO($dsn, $mysql_conf['db_user'], $mysql_conf['db_pwd']);
        $pdo_dinamic->exec("set names 'utf8'");
        return $pdo_dinamic;
    }

    /**
     * 动态数据源
     *
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * @return PDO
     */
    public static function prepare($host, $dbschema, $db_user, $db_pwd, $port){
        $mysql_conf = array(
            'host'    => $host,
            'port'    => $port,
            'db'      => $dbschema,
            'db_user' => $db_user,
            'db_pwd'  => $db_pwd,
        );

        $dsn = "mysql:host=".$mysql_conf['host'].";port=".$mysql_conf['port'].";dbname=".$mysql_conf['db'];
        $pdo_dinamic = new \PDO($dsn, $mysql_conf['db_user'], $mysql_conf['db_pwd']);
        $pdo_dinamic->exec("set names 'utf8'");
        return $pdo_dinamic;
    }


    /**
     * 展示数据源对应的tables
     *
        "table_name" => "blog_user"
        "table_comment" => "管理员"
        "engine" => "MyISAM"
     *
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * @return mixed
     */
    public static function showtables($host, $dbschema, $db_user, $db_pwd, $port){
        $host = trim($host);
        $dbschema = trim($dbschema);
        $db_user = trim($db_user);
        $db_pwd = trim($db_pwd);

        $pdo_dinamic = PdoOperator::prepare($host, $dbschema, $db_user, $db_pwd, $port);

        $sql = "select table_name, table_comment,  engine from information_schema.tables "
                    ." where table_type='BASE TABLE' and  table_schema='$dbschema' ";

        //dd($sql);
        $st = $pdo_dinamic->prepare($sql);
        $st->setFetchMode(\PDO::FETCH_OBJ);
        $st->execute();
        $rows = $st->fetchAll();

        return $rows;
    }


    /**
     * 展示数据表对应的columns
     *
    "table_name" => "blog_user"
    "table_comment" => "管理员"
    "engine" => "MyISAM"
     *
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * @return mixed
     */
    public static function showColumns($host, $dbschema, $db_user, $db_pwd, $port,  $table_name){

        $pdo_dinamic = PdoOperator::prepare($host, $dbschema, $db_user, $db_pwd, $port);

        $sql = "select column_name,  column_comment, "
                  ." column_type from information_schema.COLUMNS "
                  ." where table_name = '".$table_name."' and table_schema = '".$dbschema."' ";

        $st = $pdo_dinamic->prepare($sql);
        $st->setFetchMode(\PDO::FETCH_OBJ);
        $st->execute();
        $rows = $st->fetchAll();

        return $rows;
    }


    /**
     * 检查连接是否可用
     *
     * @param $host
     * @param $db
     * @param $db_user
     * @param $db_pwd
     * @return boolean
     */
    public static function testConn($host, $dbschema, $db_user, $db_pwd, $port){

        $result  = '';
        try {
            $pdo_dinamic = PdoOperator::prepare($host, $dbschema, $db_user, $db_pwd, $port);

            $sql = "select 1 ";

            $st = $pdo_dinamic->prepare($sql);
            $st->setFetchMode(\PDO::FETCH_BOTH);
            $st->execute();
            $result = $st->fetchAll();
        }catch(\Exception $e){
            $result = 'failed';
        }

        return $result;

    }





    // 导出数据到文件
    public static function export_file($dbsource,$sql,$file)
    {/*{{{*/
        //$dbconf = PdoOperator::get_dbconf($dbsource);
        //if (empty($dbconf)) {return;}
        $dbname = $dbsource->db;
        //dd($file);
        if (! FileOp::dis_file($file)) {
            $cmd = self::get_mysql_bin($dbsource)." -N -e \"use $dbname;$sql\" > $file";
            //$cmd = self::get_mysql_bin($dbsource)." -N -e \"use $dbname;$sql\" ";
            //dd( $cmd);
            self::runcmd($cmd);
        }
    }/*}}}*/



    // 导入文件到数据表
    public static function import_file($file,$dbsource,$table,$replace=false)
    {/*{{{*/
        //1. 判断文件是否存在
        if (!FileOp::dis_file($file)) {
            //ublog::warning("file_not_exist[$file]");
            return;
        }
        //2. 导入数据
        $dbname = $dbsource->db;
        $rp = $replace ? 'replace' : 'ignore';
        $sql = "load data local infile '$file' $rp into table $table";
        $cmd = self::get_mysql_bin($dbsource)." -N -e \"use $dbname;$sql\"";
        self::runcmd($cmd);

    }/*}}}*/


    /**
     $mysql_conf = array(
    'host'    => $host,
    'db'      => $dbschema,
    'db_user' => $db_user,
    'db_pwd'  => $db_pwd,
    );
     * @param $dbconf
     * @return string
     */

    // 获取mysql_bin
    public static function get_mysql_bin($dataSource)
    {
        /*{{{*/
        return //"/usr/local/mysql/bin/mysql".
            env('DB_MYSQL_PATH', '')."mysql".
            " -h".$dataSource->host.
            " -P".$dataSource->port.
            " -u".$dataSource->db_user.
            " -p".$dataSource->db_pwd.
            " --default-character-set=utf8";
    }/*}}}*/


    // 使用system执行cmd
    public static function runcmd($cmd)
    {
        //ublog::trace("runcmd: $cmd");
        //dd( system("") );
        //dd(system("./export.sh") );
        $code = 0;
        system($cmd,$code);
        return $code;
    }



    public static function get_dbconf($dataSrouce){
        $mysql_conf = array(
            'host'    =>  $dataSrouce->host,
            'port'    =>  $dataSrouce->port,
            'db'      => $dataSrouce->db,
            'db_user' => $dataSrouce->db_user,
            'db_pwd'  => $dataSrouce->db_pwd,
        );
        return $mysql_conf;
    }



}