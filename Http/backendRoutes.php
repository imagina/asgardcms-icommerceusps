<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/icommerceusps'], function (Router $router) {
    $router->bind('icommerceusps', function ($id) {
        return app('Modules\Icommerceusps\Repositories\IcommerceuspsRepository')->find($id);
    });
    $router->get('icommerceusps', [
        'as' => 'admin.icommerceusps.icommerceusps.index',
        'uses' => 'IcommerceuspsController@index',
        'middleware' => 'can:icommerceusps.icommerceusps.index'
    ]);
    $router->get('icommerceusps/create', [
        'as' => 'admin.icommerceusps.icommerceusps.create',
        'uses' => 'IcommerceuspsController@create',
        'middleware' => 'can:icommerceusps.icommerceusps.create'
    ]);
    $router->post('icommerceusps', [
        'as' => 'admin.icommerceusps.icommerceusps.store',
        'uses' => 'IcommerceuspsController@store',
        'middleware' => 'can:icommerceusps.icommerceusps.create'
    ]);
    $router->get('icommerceusps/{icommerceusps}/edit', [
        'as' => 'admin.icommerceusps.icommerceusps.edit',
        'uses' => 'IcommerceuspsController@edit',
        'middleware' => 'can:icommerceusps.icommerceusps.edit'
    ]);
    $router->put('icommerceusps/{icommerceusps}', [
        'as' => 'admin.icommerceusps.icommerceusps.update',
        'uses' => 'IcommerceuspsController@update',
        'middleware' => 'can:icommerceusps.icommerceusps.edit'
    ]);
    $router->delete('icommerceusps/{icommerceusps}', [
        'as' => 'admin.icommerceusps.icommerceusps.destroy',
        'uses' => 'IcommerceuspsController@destroy',
        'middleware' => 'can:icommerceusps.icommerceusps.destroy'
    ]);
// append

});
