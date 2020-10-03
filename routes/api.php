<?php

use Illuminate\Http\Request;

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

Route::post('register', 'RegisterController@register');


Route::middleware('auth:api')->group( function () {

	Route::get('user', function() { return Auth::user(); });

	Route::get('projects', 'ProjectsController@show');
	Route::get('projects/other', 'ProjectsController@other');
	Route::get('projects/{id}', 'ProjectsController@getById');
	Route::post('projects', 'ProjectsController@store');
	Route::delete('projects/{id}', 'ProjectsController@delete');
	Route::put('projects/{id}', 'ProjectsController@update');
	

	Route::post('projects/users', 'ProjectUserController@store');
	Route::delete('projects/users/{user_id}_{project_id}', 'ProjectUserController@delete');
	
	Route::get('components/project{project_id}', 'ComponentsController@show');
	Route::post('components', 'ComponentsController@store');

	Route::delete('components/{id}', 'ComponentsController@delete');

	Route::get('snippets/component{component_id}', 'SnippetsController@show');
	Route::post('snippets', 'SnippetsController@store');
	Route::put('snippets/{snippet_id}', 'SnippetsController@update');
});



