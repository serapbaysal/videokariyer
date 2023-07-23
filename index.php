<?php
error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/vendor/autoload.php';
require_once "src/DB/DB.php";
require_once "src/Controller/User.php";

use Controller\User\User;
use Pecee\SimpleRouter\SimpleRouter;


$db = new \DB\DB();
$user = new User();

SimpleRouter::setDefaultNamespace('\Controller\User');

SimpleRouter::post("/register", [User::class, "register"]);
SimpleRouter::post("/login", [User::class, "login"]);
SimpleRouter::start();


