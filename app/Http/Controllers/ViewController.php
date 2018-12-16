<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ViewController extends Controller
{
    //


    public function index(){
        //echo 112233;
        return view('index');
    }

    public function layout(){
        //echo 112233;
        return view('layout');
    }





}
