<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/usps'], function (Router $router) {

    $router->get('/rates/national/{zip}', [
        'as' => 'usps.rates.national',
        'uses' => 'PublicController@rateNational'
    ]);

    $router->get('/rates/international/{zip}/{country}/{value}', [
        'as' => 'usps.rates.international',
        'uses' => 'PublicController@rateInternational'
    ]);


});