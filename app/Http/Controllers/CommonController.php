<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/11/25
 * Time: 下午6:04
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{

    public function login(){
        return 'login success';
    }


    public function index(){
        return view('welcome');
    }


    public function setSession($key, $value){
        $_SESSION[$key] = $value;
    }

}