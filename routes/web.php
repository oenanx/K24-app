<?php

use Illuminate\Support\Facades\Route;

Route::get('refresh-csrf', function(){
    return csrf_token();
});

Route::group(['middleware' => 'prevent-back-history'],function()
{
    Route::get('/', [\App\Http\Controllers\Home::class, 'index'])->name('login');

	Route::post('/captcha-validation', [\App\Http\Controllers\Home::class, 'authenticate']);
	
	Route::get('/reload-captcha', [\App\Http\Controllers\Home::class, 'reloadCaptcha'])->name('login.reloadCaptcha');

    Route::get('Home/home', [\App\Http\Controllers\Home::class, 'home']);

    Route::post('login', [\App\Http\Controllers\Home::class, 'authenticate'])->name('login.authenticate');

    Route::get('forget', [\App\Http\Controllers\Home::class, 'forget'])->name('login.forget');

    Route::post('resetpasswd', [\App\Http\Controllers\Home::class, 'resetpasswd'])->name('login.resetpasswd');

    Route::get('logout', [\App\Http\Controllers\Home::class, 'logout'])->name('logout');
	
	Route::get('Home/new_user', [\App\Http\Controllers\Home::class, 'new_user']);
	
	Route::post('Home/daftar', [\App\Http\Controllers\Home::class, 'daftar']);

    Route::get('Change_Pass/change_pass', [\App\Http\Controllers\Change_Pass::class, 'change_pass']);
    Route::get('Change_Pass/change_pass_new', [\App\Http\Controllers\Change_Pass::class, 'change_pass_new']);
    Route::get('Change_Pass/update_pass', [\App\Http\Controllers\Change_Pass::class, 'update_pass']);

    Route::get('M_Users/index', [\App\Http\Controllers\Home::class, 'userindex']);
    Route::post('M_Users/datatable', [\App\Http\Controllers\Home::class, 'userindex'])->name('M_Users.datatable');
    Route::get('M_Users/view_user/{id}', [\App\Http\Controllers\Home::class, 'view_user']);
    Route::get('M_Users/update_user', [\App\Http\Controllers\Home::class, 'update_user']);
 
});
