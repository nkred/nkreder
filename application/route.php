<?php

use Think\Route;
Route::post('register','App/login/register');
Route::post('login','App/login/login');
Route::put('save/:id','App/login/save');
//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//];
