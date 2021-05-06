<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

/** 后台登录相关 */
Route::match(['POST', 'GET'], 'login', "LoginController@login")->name('auth.login');
Route::get('logout', 'LoginController@logout')->name('auth.logout');

/** 系统相关 */
Route::group(['middleware' => ['auth:admin','rbac']], function (){
    Route::get('/', 'IndexController@index');
    Route::get('/index', 'IndexController@index');
    Route::get('menu', 'IndexController@menu')->name('admin.menu');

    Route::group(['prefix' => 'system'], function(){
        //管理员
        Route::match(['get', 'post'], 'admin','SystemController@admin')->name('system.admin');
        Route::match(['get', 'post'], 'admin/{id}','SystemController@editAdmin')
            ->where(['id' => '[\d]+'])->name('system.admin.edit');
        Route::delete('admin/{id}','SystemController@delAdmin')->where(['id' => '[\d]+'])->name('system.admin.delete');
        Route::put('admin/{id}','SystemController@changeAdmin')->where(['id' => '[\d]+'])->name('system.admin.change');
        //菜单
        Route::get('menu','SystemController@menu')->name('system.menu');
        Route::match(['get', 'post'], 'menu/{id}','SystemController@editMenu')
            ->where(['id' => '[\d]+'])->name('system.menu.edit');
        Route::delete('menu/{id}','SystemController@delMenu')->where(['id' => '[\d]+'])->name('system.menu.delete');
        Route::put('menu/{id}','SystemController@changeMenu')->where(['id' => '[\d]+'])->name('system.menu.change');
        //权限
        Route::get('permission', 'SystemController@permission')->name('system.permission');
        Route::match(['get', 'post'], 'permission/{id}','SystemController@editPermission')
            ->where(['id' => '[\d]+'])->name('system.permission.edit');
        Route::delete('permission/{id}','SystemController@delPermission')->where(['id' => '[\d]+'])->name('system.permission.delete');
        Route::put('permission/{id}','SystemController@changePermission')->where(['id' => '[\d]+'])->name('system.permission.change');
        Route::match(['get', 'put'], 'permissionMenu/{id}','SystemController@permissionMenu')
            ->where(['id' => '[\d]+'])->name('system.permission.permissionMenu');
        //角色
        Route::get('role', 'SystemController@role')->name('system.role');
        Route::match(['get', 'post'], 'role/{id}','SystemController@editRole')
            ->where(['id' => '[\d]+'])->name('system.role.edit');
        Route::put('role/{id}','SystemController@changeRole')->where(['id' => '[\d]+'])->name('system.role.change');
        Route::delete('role/{id}','SystemController@delRole')->where(['id' => '[\d]+'])->name('system.role.delete');
        Route::match(['get', 'put'], 'rolePermission/{id}','SystemController@rolePermission')
        ->where(['id' => '[\d]+'])->name('system.role.rolePermission');
    });

});
