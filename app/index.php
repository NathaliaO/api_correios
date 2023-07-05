<?php

require_once 'routes.php';

$path = $_SERVER['REQUEST_URI'];
$path = strtok($path, '?');
route($path);