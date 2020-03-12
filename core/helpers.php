<?php

function getRequestProtocol() : string {
    if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
        return $_SERVER['HTTP_X_FORWARDED_PROTO'];
    else
        return !empty($_SERVER['HTTPS']) ? "https" : "http";
}

function registerRoutes(&$routes, string $name) : void {
    require_once("../routes/{$name}.php");
}