<?php

// Web routes for your app

$routes->get('/', 'HomeController@index');

$routes->get('/users', 'UsersController@index');

$routes->get('/sample-route', function ($param, $optional_param = '') { // will return notFound route if $param is missing
    echo "Parameter: {$param}<br/>";
    echo "Optional parameter: {$optional_param}";
});

$routes->get('/optional-route', function ($param = 'foo', $optional_param = 'bar') {
    echo "Optional parameter #1: {$param}<br/>";
    echo "Optional parameter #2: {$optional_param}";
});

$routes->notFound(function () {
    echo 'Page not found';
});
