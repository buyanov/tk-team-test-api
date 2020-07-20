<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use Illuminate\Contracts\Routing\Registrar;

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

JsonApi::register('v1', [], function ($api) {

    $api->post('/auth/token', 'AuthController@token');

    $api->resource('users', ['has-many' => 'tasks'])
        ->readOnly();

    $api->resource('users', ['has-many' => 'tasks'])
        ->only('create')
        ->middleware('auth:sanctum', 'can:create');

    $api->resource('users', ['has-many' => 'tasks' ])
        ->only('delete')
        ->middleware('auth:sanctum', 'can:delete');

    $api->resource('users', ['has-many' => 'tasks'])
        ->only('update')
        ->middleware('auth:sanctum', 'can:edit');

    $api->resource('tasks', ['has-one' => 'user'])
        ->readOnly();

    $api->resource('tasks', ['has-one' => 'user'])
        ->only('create')
        ->middleware('auth:sanctum', 'can:create');

    $api->resource('tasks', ['has-one' => 'user'])
        ->only('update')
        ->middleware('auth:sanctum', 'can:edit');

    $api->resource('tasks', ['has-one' => 'user'])
        ->only('delete')
        ->middleware('auth:sanctum', 'can:delete');
});
