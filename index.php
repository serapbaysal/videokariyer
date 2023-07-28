<?php
error_reporting(E_ERROR | E_PARSE);
use Controller\User\User;
use Controller\Company\Company;
use Controller\Advert\Advert;
use Controller\Apply\Apply;
use Controller\Question\Question;
use Controller\Answer\Answer;
use Files\Upload\Upload;
use Files\Export\Export;
use Pecee\SimpleRouter\SimpleRouter;

require __DIR__ . '/vendor/autoload.php';
require_once "src/DB/DB.php";
require_once "src/Controller/User.php";
require_once "src/Controller/Company.php";
require_once "src/Controller/Advert.php";
require_once "src/Controller/Apply.php";
require_once "src/Controller/Question.php";
require_once "src/Controller/Answer.php";
require_once "src/Files/Upload.php";
require_once "src/Files/Export.php";

$db = new \DB\DB();
//$user = new User();

SimpleRouter::setDefaultNamespace('\Controller');

SimpleRouter::post("/register", [User::class, "register"]);
SimpleRouter::post("/login", [User::class, "login"]);
SimpleRouter::get("/users", [User::class, "getUsers"]);
SimpleRouter::get("/users/{id}", [User::class, "getUserByID"]);

SimpleRouter::post("/companies/create", [Company::class, "createCompany"]);
SimpleRouter::get("/companies", [Company::class, "getCompanies"]);
SimpleRouter::get("/companies/{id}", [Company::class, "getCompanyByID"]);
//SimpleRouter::put("/companies/{id}", [Company::class, "updateCompany"]);
SimpleRouter::delete("/companies/{id}", [Company::class, "deleteCompany"]);

SimpleRouter::get("/adverts", [Advert::class, "getAdverts"]);

SimpleRouter::post("/applies", [Apply::class, "applyJob"]);

SimpleRouter::post("/questions", [Question::class, "createQuestion"]);

SimpleRouter::post("/answers", [Answer::class, "answerQuestion"]);

SimpleRouter::post("/upload", [Upload::class, "upload"]);
SimpleRouter::get("/exportUsers", [Export::class, "exportUsers"]);
SimpleRouter::get("/applyJob", [Export::class, "applyJob"]);




SimpleRouter::start();


