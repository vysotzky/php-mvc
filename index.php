<?php

require_once('router.php');
$routes = new router;

$routes->get('xd', function($x){
   echo "echo $x";
});

$routes->get('/', function(){

});

$routes->notFound(function(){
    echo '404';
});


