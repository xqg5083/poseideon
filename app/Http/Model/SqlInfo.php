<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SqlInfo extends Model
{
    //
    protected $table="sql_info";
    protected $primaryKey = "id";
    public $timestamps=false;
}
