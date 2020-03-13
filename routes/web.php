<?php

// Web routes for your app

$routes->get('/', function(){
    echo "Hello world";
});

$routes->get('/c', 'HomeController@index');

$routes->notFound(function(){
    echo '404';
});

