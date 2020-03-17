<?php
namespace App\Controllers;

abstract class Controller
{
    public function before()
    {
        // method called before controller execution
    }

    public function after()
    {
        // method called at the end of controller execution
    }

    public function __destruct(){
        $this->after();
    }
}