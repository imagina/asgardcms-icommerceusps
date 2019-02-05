<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'icommerceusps'], function (Router $router) {
    
    $router->get('/', [
        'as' => 'icommerceusps.api.usps.init',
        'uses' => 'IcommerceUspsApiController@init',
    ]);

   

});