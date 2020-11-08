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

//Route::get('/', 'HomeController@index')->name('home');
Route::get('r/{slug}', 'HomeController@subreddit')->name('subreddit');
Route::get('c/{slug}', 'HomeController@category')->name('category');
Route::get('user/{id}', function(){ return view('subreddit'); });
Route::get('/', function(){ return view('subreddit'); });


//Auth::routes();
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/categories', 'CategoriesController@index')->name('categories');
Route::match(['get','post'],'/category/create', 'CategoriesController@create')->name('category.add');
Route::match(['get','post'],'/category/edit/{id}', 'CategoriesController@edit')->name('category.edit');
Route::delete('/category/delete/{id}', 'CategoriesController@delete')->name('category.delete');

Route::get('/subcat/{id}', 'SubCategoriesController@index')->name('sub-categories');
Route::match(['get','post'],'/subcat/create/{id}', 'SubCategoriesController@create')->name('subcat.add');
Route::match(['get','post'],'/subcat/edit/{id}', 'SubCategoriesController@edit')->name('subcat.edit');
Route::delete('/subcat/delete/{id}', 'SubCategoriesController@delete')->name('subcat.delete');


Route::get('/sauce', 'SauceController@index')->name('sauce');
Route::get('/sauce/list', 'SauceController@getlist')->name('sauce.list');
Route::post('/sauce/save', 'SauceController@savesauce')->name('sauce.lsaveist');

//Route::get('/home', 'HomeController@index')->name('home');
