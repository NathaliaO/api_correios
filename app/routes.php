<?php

require_once 'Controller/AuthorizationController.php';
require_once 'Controller/CountryController.php';
require_once 'Controller/FileController.php';

function route($path) {
    $route = [
        '/' => 'Controller\AuthorizationController@index',
        '/country' => 'Controller\CountryController@index',
        '/file' => 'Controller\FileController@index'
    ];

    if(array_key_exists($path, $route)){
        $routeParts = explode('@', $route[$path]);
        $controller = $routeParts[0];
        $method = $routeParts[1];

        call_user_func([new $controller(), $method]);
    } else {
        echo 'Página não encontrada!! Tente Novamente.';
    }
}