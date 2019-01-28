<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/icommerceusps'], function (Router $router) {
    $router->bind('configusps', function ($id) {
        return app('Modules\IcommerceUsps\Repositories\ConfiguspsRepository')->find($id);
    });
    $router->get('configusps', [
        'as' => 'admin.icommerceusps.configusps.index',
        'uses' => 'ConfiguspsController@index',
        'middleware' => 'can:icommerceusps.configusps.index'
    ]);
    $router->get('configusps/create', [
        'as' => 'admin.icommerceusps.configusps.create',
        'uses' => 'ConfiguspsController@create',
        'middleware' => 'can:icommerceusps.configusps.create'
    ]);
    $router->post('configusps', [
        'as' => 'admin.icommerceusps.configusps.store',
        'uses' => 'ConfiguspsController@store',
        'middleware' => 'can:icommerceusps.configusps.create'
    ]);
    $router->get('configusps/{configusps}/edit', [
        'as' => 'admin.icommerceusps.configusps.edit',
        'uses' => 'ConfiguspsController@edit',
        'middleware' => 'can:icommerceusps.configusps.edit'
    ]);
    $router->put('configusps', [
        'as' => 'admin.icommerceusps.configusps.update',
        'uses' => 'ConfiguspsController@update',
        'middleware' => 'can:icommerceusps.configusps.edit'
    ]);
    $router->delete('configusps/{configusps}', [
        'as' => 'admin.icommerceusps.configusps.destroy',
        'uses' => 'ConfiguspsController@destroy',
        'middleware' => 'can:icommerceusps.configusps.destroy'
    ]);

// append

});
