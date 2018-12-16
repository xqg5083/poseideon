<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/





Route::any('admin/crypt', 'Admin\LoginController@crypt');



Route::any('admin/vue', 'Admin\VueController@index');



Route::group(['middleware' => ['init.web'], 'namespace' => 'Admin'], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::any('admin/login', 'LoginController@login');
    Route::any('admin/quit', 'LoginController@quit');

    Route::get('admin/code', 'LoginController@code');


});




Route::group(['middleware' => ['admin.login'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

    Route::get('indexall', 'IndexController@index');

    Route::get('info', 'IndexController@info');
    Route::any('pass', 'IndexController@pass');

    Route::post('cate/changeorder', 'CategoryController@changeOrder');
    Route::resource('category', 'CategoryController');

});


Route::group(['middleware' => ['admin.login'], 'prefix' => 'admin', 'namespace' => 'Sync'], function () {

    /** 数据源管理 **/
    Route::any('dataSource/{source_id}/tables', 'DataSourceController@tables');

    Route::any('dataSource/{db_id}/{table_name}/showColumn', 'DataSourceController@showColumn');

    //showColumn
    Route::post('dataSource/changeorder', 'DataSourceController@changeOrder');
    Route::any('dataSource/checkConn', 'DataSourceController@checkConn');

    Route::resource('dataSource', 'DataSourceController');


    /**同步配置**/
    Route::resource('dataTask', 'DataTaskController');

    Route::any('dataTask/{db_id}/{table_name}/dataTaskBySource', 'DataTaskController@dataTaskBySource');

    /**同步管理**/
    Route::resource('dataSync', 'DataSyncController');
    Route::post('dataSync/invokeSync', 'DataSyncController@invokeSync');

});


