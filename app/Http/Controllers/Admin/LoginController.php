<?php
/**
 * Created by PhpStorm.
 * User: jiangxinqiang
 * Date: 2018/11/25
 * Time: 下午6:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Http\Model\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{

    /**
     * 登录主方法
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|string
     */
    public function login(){
        $input = Input::all();

        //dd(sizeof($input));
        if(count($input) > 0 && sizeof($input) > 1){
            if(strtolower($input['code']) != strtolower((new \Code())->get())){
                $this->setSession('msg', '验证码错误');
                return back();
            }

            $user = User::first();

            if($user->user_name != $input['user_name']
                || trim($user->user_pass) != md5(trim($input['user_pass']))
            ){
                return back()->with('msg', '用户名或密码错误');
            }



            //记录用户到session中　
            $this->setSession('user', $user);

            //dd(222);

            //登录成功，重定向到index后面主页面
            return redirect('admin/indexall');

        }else{
            //$users = User::all();
            //dd($users);
            return view('admin.login');
        }
    }


    /**
     * 退出
     */
    public function quit(){
        //销毁session
        session_destroy();
        return view('admin.login');
    }


    /**
     * 后端主页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('welcome');
    }


    /**
     * 获取验证码
     */
    public function code(){
        $code = new \Code();
        $code->make();
        //return view('welcome');
    }

//    public function getcode(){
//        $code = new \Code();
//        $code->get();
//        //return view('welcome');
//    }


//
    public function crypt(){
        //加密后，长度在250以内
        $str = '123456';
        $str_pt = 'eyJpdiI6IlZoTlRZbkdkcXFFM1VOOVZzWnp6TXc9PSIsInZhbHVlIjoiMUNtUWk0QzBMOGYyblwvSEV4MmQrWkE9PSIsIm1hYyI6IjIyMjMxYmQ5OWNjMjMxYjhlYWZjODg0YzUyYjZiNzdmYzZlNThlMTMwOGEzODY4NjMwNWFlZmQ2YjE2NThlYmUifQ';


        //  e10adc3949ba59abbe56e057f20f883e
        //加密
        echo md5($str);
        //Crypt::encrypt($str);
        //解密
        //Crypt::decrypt($str_pt);
    }

}