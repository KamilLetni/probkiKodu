<?php

use App\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home',function (){

    if ($id = Auth::user()->role=='user')
    {
        $id = Auth::user()->id;
        return redirect("/profile/".$id);
    }
    else
    {
        $id = Auth::user()->id;
        return redirect("/admin");
    }

})->middleware('auth', 'auth.admin');

Route::get('/admin', 'AdminController@index');
Route::get('/admin/edit/{id}/role/{role}/name/{name}/email/{email}/bonus/{bonus}', ['as' => 'adminEdit', 'uses' => 'AdminController@edit']);
Route::get('/admin/delete/{id}', 'AdminController@delete');
Route::get('/admin/update', 'AdminController@update');
Route::get('/admin/send', 'AdminController@send');
Route::get('/admin/create', 'AdminController@create');

Route::get('/profile/{user}', 'ProfileController@index');
Route::post('/profile/update', 'ProfileController@update');
Route::post('/profile/mine/create', 'MineController@create');
Route::post('/profile/mine/improve', 'MineController@improve');

Route::get('/workers', 'WorkerController@index');
Route::post('/workers/add', 'WorkerController@add');
Route::post('/workers/fire', 'WorkerController@fire');
Route::get('/workers/sell', 'WorkerController@workerSell');
Route::post('/workers/trade', 'WorkerController@trade');
Route::get('/workers/{id}', 'WorkerController@hire');
Route::get('workers/price/{price}/buyer/{buyer}/worker/{worker}/seller/{seller}/trade/{trade}', [
    'as' => 'remindHelper', 'uses' => 'WorkerController@buy']);
Route::get('workers/confirm/{trade}', ['as' => 'confirm', 'uses' => 'WorkerController@confirm']);
Route::get('workers/decline/{trade}', ['as' => 'decline', 'uses' => 'WorkerController@decline']);
Route::get('workers/delete/{trade}', ['as' => 'delete', 'uses' => 'WorkerController@delete']);

Route::get('/chart', 'ChartController@index')->name('chart.show');
Route::get('/chart/transaction', 'ChartController@sell');

Route::get('/user', 'UserController@index')->name('user.show');
Route::get('/user/data', 'UserController@account');
Route::get('/user/password', 'UserController@password');

Route::get('/rank', 'PaginationController@index');
Route::get('/pagination/fetch_data', 'PaginationController@fetch_data');
//    Route::get('/test', 'RankController@index');

Route::get('/ally', 'AllyController@index');
Route::get('/ally/create', 'AllyController@create');
Route::get('/ally/fetch_data', 'AllyController@fetch_data');
Route::get('/ally/quit', 'AllyController@quit');
Route::get('/ally/supply', 'AllyController@supplySend');
Route::get('/ally/apply', 'AllyController@apply');
Route::get('/ally/delete', 'AllyController@delete');
Route::get('/ally/applyStatus', 'AllyController@applyStatus');
Route::get('/ally/role', 'AllyController@role');
Route::get('/ally/allyDelete', 'AllyController@allyDelete');
Route::get('/ally/postCreate', 'AllyController@postCreate');
Route::get('/ally/postDelete', 'AllyController@postDelete');


Route::get('/post/commentAdd', 'PostController@commentAdd');
Route::get('/post/{post}', ['as' => 'view', 'uses' => 'PostController@index']);








