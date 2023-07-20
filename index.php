<?php

require __DIR__ . '/vendor/autoload.php';
require_once "src/Router/Router.php";
require_once "src/DB/DB.php";
require_once "src/Controller/User.php";

use App\Router as Router;
use Controller\User\User;

$db = new \DB\DB();

$user = new User();

$router = new \Bramus\Router\Router();

$router->post("/users/login", $user->login());

//$router = new Router();



//Router::run("/", function () {
//    echo "hello";
//});
Router::post("/users/login", "User@login");


//
//$router->post("/users/register", $user->register());
//$router->post("/users/login", $user->login());
//
//$router->get("/about", function () {
//
//});
//
//$router->addNotFoundHandler(function () {
//    echo "Not Found";
//});
//
//$router->run();

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


