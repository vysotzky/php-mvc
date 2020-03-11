<?php
define('API', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('_init.php');
$action = '';

function error($msg = ''){
    if($msg==''){
        $msg = 'Invalid API request';
    }
    http_response_code(503);
    response(['status'=>'error', 'msg'=>$msg]);
}

function success($msg = ''){
    $response = ['status'=>'success'];
    if($msg!=''){
        $response['msg'] = $msg;
    }
    http_response_code(200);
    response($response);
}

function response($response){
    die(json_encode($response));
}


$url = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strpos($_SERVER['REQUEST_URI'], 'api')+4);
$query = explode('/', $url);

if(isset($query[0]) && $query[0] != ''){
    $params = $query;
    $action = array_shift($params);
} else {
    error();
}


switch($action){
    case 'reportPosition':
        if(isset($_GET['lat'], $_GET['long'], $_GET['spd'], $_GET['user'], $_GET['time'])) {
            $lat = $_GET['lat'];
            $long = $_GET['long'];
            $speed = $_GET['spd'];
            $user = $_GET['user'];
            $time = $_GET['time'];
            $dir = isset($_GET['dir']) ? $_GET['dir'] : '';
            $add = $Positions->add(['latitude'=>$lat, 'longitude'=>$long, 'time'=>$time, 'speed'=>$speed, 'dir'=>$dir, 'user'=>$user]);
            success();
        } else {
            error('Incorrect params');
        }
        break;
    case 'getPositions':
        $pos = $Positions->recent();
        response($pos);
        break;
    default:
        error();
        break;
}