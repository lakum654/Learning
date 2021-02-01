<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('posts', 'PostController');
Route::post('posts/favorite','PostController@addFavirote')->name('posts.favorite');
Route::post('posts/comment','PostController@addComment')->name('posts.comment');
Route::post('posts/loadComment','PostController@loadComment')->name('posts.loadComment');
Route::any('posts/addLike','PostController@addLike')->name('posts.like');
Route::any('posts/comment/reply','PostController@addReply')->name('posts.comment.reply');

Route::any('post/reply/remove','PostController@replyDelete')->name('posts.reply.delete');
Route::any('post/comment/remove/{id}','PostController@commentDelete')->name('posts.comment.delete');
