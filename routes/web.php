<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->post('api/users/login/', 'ImageUploadController@userLogin');
$router->group(['prefix' => 'api', 'middleware' => 'client'], function ($router){
    $router->get('/', 'ImageUploadController@index');
    $router->post('uploadimage', 'ImageUploadController@store');
    $router->post('updateimage/{id}', 'ImageUploadController@update');
    $router->delete('deleteimage/{id}', 'ImageUploadController@delete');
});
