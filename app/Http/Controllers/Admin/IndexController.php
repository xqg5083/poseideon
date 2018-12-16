<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Model\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    //
    public function index(){
        return view('admin.index');
    }

    public function info(){
        return view('admin.info');
    }


    /**
     * 更改admin密码
     */
    //更改超级管理员密码
    public function pass()
    {
        $input = Input::all();

        //dd($input);
        if(count($input) > 0 && sizeof($input) > 1){
//dd(1);
            //dd(11);
            $rules = [
                'password'=>'required|between:5,20|confirmed',
            ];

            $message = [
                'password.required'=>'新密码不能为空！',
                'password.between'=>'新密码必须在5-20位之间！',
                'password.confirmed'=>'新密码和确认密码不一致！',
            ];

            //dd(22);
            $validator = Validator::make($input,$rules,$message);


            if($validator->passes()){
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    $user->user_pass = Crypt::encrypt($input['password']);
                    //dd(1);
                    $user->update();
                    return redirect('admin/info');
                }else{
                    return back()->with('errors','原密码错误！');
                }
            }else{
                //dd($validator);
                return back()->withErrors($validator);
            }

        }else{
            //dd(2);
            return view('admin.pass');
        }
    }
}
