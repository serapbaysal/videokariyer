<?php

require_once "src/Router/Router.php";
require_once "src/DB/DB.php";
use App\Router as Router;


$router = new Router();

$db = new \DB\DB();
$db->connectDB("127.0.0.1", "root", "my-secret-pw", "videokariyer");

$router->get("/", function () {
    echo "HOME";
});

$router->get("/about", function () {

});

$router->addNotFoundHandler(function () {
    echo "Not Found";
});

$router->run();

//
//$request = $_SERVER['REQUEST_URI'];
//$viewDir = '/src/Controller/';
//
//
//switch ($request) {
//    case '':
//    case '/':
//        require __DIR__ . $viewDir . 'Home.php';
//        break;
//
//    case '/views/users':
//        require __DIR__ . $viewDir . 'users.php';
//        break;
//
//    case '/contact':
//        require __DIR__ . $viewDir . 'contact.php';
//        break;
//
//    default:
//        http_response_code(404);
//        require __DIR__ . $viewDir . '404.php';
//}


