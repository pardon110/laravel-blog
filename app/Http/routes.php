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

Route::get('/', function () {
    return view('index');
});

Route::get('login',function(){
    return view('login');
});
Route::post('login', function()
{
    //数据验证规则
    $rules = array(
        'email'       => 'required|email',
        'password'    => 'required|min:6',
        'remember_me' => 'boolean',
    );
    $validator = Validator::make(Request::all(), $rules);
    //验证通过
    if ($validator->passes())
    {
        if (Auth::attempt([
            'email'    => Request::input('email'),
            'password' => Request::input('password'),
            'block'    => 0],
            (boolean) Request::input('remember_me')))
        {
            return Redirect::to('home');
        }
        //账号或密码错误
        else {
            return Redirect::to('login')->withInput()->with('message', array('type' => 'danger', 'content' => 'E-mail or password error'));
        }
    }
    //数据格式错误
    else {
        return Redirect::to('login')->withInput()->withErrors($validator);
    }
});
//访问主页
Route::get('home', ['middleware' => 'auth', function()
{
    return view('home');
}]);

Route::get('logout',['middleware'=>'auth',function(){
    Auth::logout();
    return Redirect::to('/');
}]);