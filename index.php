<?php
session_start();
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/system/Autoload.php');

// Router call
$router = new Router();
$router->run();