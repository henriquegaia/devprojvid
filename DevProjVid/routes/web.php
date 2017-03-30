<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'WelcomeController@index');
    Route::get('/home', 'HomeController@index');
});


/*
 * ************************************************************************
 * UserController
 * ************************************************************************
 */
$user_ctrl = 'UserController';

////////////////////////////////
// With Auth Middleware
////////////////////////////////


Route::group(['middleware' => 'auth'], function() {
    $user_ctrl = 'UserController';
    Route::get('user/{id}/edit', "$user_ctrl@edit");
});
Route::get('user/email/update', "$user_ctrl@updateEmail")
        ->middleware('auth');
Route::get('user/name/update', "$user_ctrl@updateName")
        ->middleware('auth');

////////////////////////////////
// Without Auth Middleware
////////////////////////////////
Route::resource('user', $user_ctrl, [
    'only' => [
        'show'
]]);

Route::get('testDev', function() {
    return 'is developer';
})->middleware('developer');

Route::get('testCom', function() {
    return 'is company';
})->middleware('company');

/*
 * ************************************************************************
 * ProjectController
 * 
 * all with auth middleware, except: index, show
 * ************************************************************************
 */

////////////////////////////////
// With Auth Middleware
////////////////////////////////

$project_ctrl = "ProjectController";
Route::group(['middleware' => 'auth'], function() {
    $project_ctrl = "ProjectController";
    Route::resource('project', $project_ctrl, [
        'except' => [
            'index',
            'show'
        ]
    ]);
});

////////////////////////////////
// Without Auth Middleware
////////////////////////////////
Route::get('projects/user/{userId}', "$project_ctrl@indexUser");
Route::get('project', "$project_ctrl@index");
Route::get('project/{id}', "$project_ctrl@show");
Route::post('project/rate/{projectId}/{raterId}/{mode}', "$project_ctrl@rate");

/*
 * ************************************************************************
 * VideoGalleryController
 * ************************************************************************
 */

$video_gallery_ctrl = "VideoGalleryController";
Route::resource('videoGallery', $video_gallery_ctrl, [
    'except' => [
        'create'
    ]
]);
Route::get('videoGallery/create/{projectId}', "$video_gallery_ctrl@create")
        ->middleware('auth');
Route::post('videoGallery/{id}/updateVideos', "$video_gallery_ctrl@updateVideos")
        ->middleware('auth');
/*
 * ************************************************************************
 * VideoController
 * ************************************************************************
 */

$video_ctrl = "VideoController";
Route::resource('video', $video_ctrl);

/*
 * ************************************************************************
 * RequestController
 * ************************************************************************
 */


Route::group(["middleware" => "company"], function() {
    $requestController = "RequestController";
    Route::resource('request', $requestController, [
        'except' => [
            'index',
            'indexByCompany',
            'show'
        ]
    ]);
});

$requestController = "RequestController";

Route::get('request', 'RequestController@index');
Route::get('request/company/{companyId}', 'RequestController@indexByCompany');
Route::get('request/{id}', "$requestController@show");


/*
 * ************************************************************************
 * MatchController
 * ************************************************************************
 */

$matchController = 'MatchController';

Route::get('match', "$matchController@index");