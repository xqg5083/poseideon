<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    //

    protected $table="data_source";
    protected $primaryKey = "id";
    public $timestamps=false;



    protected $fillable = ['id','show_name', 'host', 'port', 'db', 'db_user', 'db_pwd' ];


}
