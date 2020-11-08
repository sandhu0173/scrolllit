<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});


Route::middleware('auth:api')->get('/follow/subreddit/{id}','AuthController@follow');
Route::middleware('auth:api')->get('/check/follow/subreddit/{id}','AuthController@checkfollow');

Route::get('/getdata', 'Api@getdata');
Route::get('/fetchposts/{slug}', 'Api@fetchposts');
Route::get('/loadmore/subcat/{type}/{slug}/{filter}/{offset}', 'Api@discoverposts');
Route::get('/cat/top/{slug}', 'Api@cattop');
Route::get('/loadmore/cat/top/{slug}/{offset}', 'Api@morecattop');

Route::get('/loadmore/subcat/top/{slug}/{filter}/{topfilter}/{offset}', 'Api@moresubcattop');
Route::get('/loadmore/subcat/all/{slug}/{filter}/{topfilter}/{offset}', 'Api@moresubcatall');
Route::get('/loadmore/subcat/hot/{slug}/{filter}/{topfilter}/{offset}', 'Api@moresubcathot');

Route::get('/cat/hot/{slug}', 'Api@cathot');
Route::get('/loadmore/cat/hot/{slug}/{offset}', 'Api@morecathot');
//sub categaory route
Route::get('/top/{slug}/{filter}', 'Api@topdata');
Route::get('/loadmore/top/{slug}/{offset}/{filter}', 'Api@moretopdata');
Route::get('/hot/{slug}/{filter}', 'Api@hotdata');
Route::get('/loadmore/hot/{slug}/{offset}/{filter}', 'Api@morehotdata');
//sub categaory mobile route
Route::get('/subcat/posts/{slug}/{filter}/{sort}/{topfilter}', 'Api@subcatposts');
Route::get('/loadmore/subcat/posts/{slug}/{offset}/{filter}/{sort}/{topfilter}', 'Api@moresubcatposts');

Route::get('/mobile/subcat/posts/{slug}/{filter}/{sort}/{topfilter}', 'Api@mobilesubcatposts');
Route::get('/mobile/loadmore/subcat/posts/{slug}/{offset}/{filter}/{sort}/{topfilter}', 'Api@mobilemoresubcatposts');

Route::get('/mobile/top/{slug}/{filter}', 'Api@mobiletopdata');
Route::get('/mobile/loadmore/top/{slug}/{offset}/{filter}', 'Api@mobilemoretopdata');
Route::get('/mobile/hot/{slug}/{filter}', 'Api@mobilehotdata');
Route::get('/mobile/loadmore/hot/{slug}/{offset}/{filter}', 'Api@mobilemorehotdata');


Route::get('/categories', 'Api@maincategories');
Route::get('/subcats/{slug}/{offset}', 'Api@subcategories');
Route::get('/categories/{offset}', 'Api@categories');
Route::get('/adultcategories/{offset}', 'Api@adultcategories');
Route::get('/catdetail/{slug}', 'Api@catdetail');
Route::get('/subcatdetail/{slug}', 'Api@subcatdetail');
Route::get('/postdetail/{slug}', 'Api@postdetail');
Route::get('/post/new/{slug}', 'Api@newpost');
Route::get('/post/new/previous/{slug}', 'Api@newpreviouspost');


Route::get('/search/subreddit', 'Api@search');
Route::group([
    'middleware' => 'auth:api'
  ], function() {
    Route::get('/check/post/like/{postid}', 'Api@checklike');
    Route::get('/post/like/{postid}', 'Api@postlike');
    
    Route::get('/check/post/like/top/{postid}', 'Api@checktoplike');
    Route::get('/check/post/like/hot/{postid}', 'Api@checkhotlike');
    Route::get('/post/like/top/{postid}', 'Api@toppostlike');
    Route::get('/post/like/hot/{postid}', 'Api@hotpostlike');
    Route::get('/following', 'Api@following');
    Route::get('/favorites', 'Api@favorites');
    Route::get('/adult/following', 'Api@adultfollowing');
    Route::get('/check/login', 'api@checklogin');
    Route::get('/top/favorites', 'api@topfavorites');
    Route::get('/hot/favorites', 'api@hotfavorites');

  }
);
