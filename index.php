<?php
error_reporting(E_ERROR | E_PARSE);
use Controller\User\User;
use Controller\Company\Company;
use Controller\Advert\Advert;
use Controller\Apply\Apply;
use Pecee\SimpleRouter\SimpleRouter;

require __DIR__ . '/vendor/autoload.php';
require_once "src/DB/DB.php";
require_once "src/Controller/User.php";
require_once "src/Controller/Company.php";
require_once "src/Controller/Advert.php";
require_once "src/Controller/Apply.php";

$db = new \DB\DB();
//$user = new User();

SimpleRouter::setDefaultNamespace('\Controller');

SimpleRouter::post("/register", [User::class, "register"]);
SimpleRouter::post("/login", [User::class, "login"]);

SimpleRouter::post("/companies/create", [Company::class, "createCompany"]);
SimpleRouter::get("/companies", [Company::class, "getCompanies"]);
SimpleRouter::get("/companies/{id}", [Company::class, "getCompanyByID"]);
//SimpleRouter::put("/companies/{id}", [Company::class, "updateCompany"]);
SimpleRouter::delete("/companies/{id}", [Company::class, "deleteCompany"]);

SimpleRouter::get("/adverts", [Advert::class, "getAdverts"]);

SimpleRouter::post("/applies", [Apply::class, "applyJob"]);


SimpleRouter::start();


