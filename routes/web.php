<?php

// Web routes for your app

$routes->get('/', function(){
    echo "Hello world";
});

$routes->notFound(function(){
    echo '404';
});

