<?php

// Web routes for your app

$routes->get('/', 'HomeController@index');

$routes->get('/sample-route', function($param, $optional_param=''){
    echo "Parameter = {$param}<br/>";
    echo "Optional parameter: {$optional_param}";
});

$routes->notFound(function(){
    echo 'Page not found';
});
