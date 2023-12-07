<?php
error_reporting(E_ERROR | E_PARSE);
use Controller\User\User;
use Controller\Company\Company;
use Controller\Advert\Advert;
use Controller\UserVideo\UserVideo;
use Controller\Question\Question;
use Controller\Answer\Answer;
use Controller\UserEducation\UserEducation;
use Controller\UserEducation\UserEducationController;
use Controller\UserExperience\UserExperience;
use Controller\UserSkill\UserSkill;
use Controller\VideoQuestion\VideoQuestion;
use Controller\District\District;
use Controller\City\City;
use Files\Upload\Upload;
use Files\Export\Export;
use Model\UserEducation\UserEducationModel;
use Pecee\SimpleRouter\SimpleRouter;
use Controller\Role\Role;
use Controller\University\University;
use Controller\Department\Department;
use Controller\Faculty\Faculty;



require __DIR__ . '/vendor/autoload.php';
require_once "src/DB/DB.php";
require_once "src/Controller/User.php";
require_once "src/Controller/UserVideo.php";
require_once "src/Controller/Company.php";
require_once "src/Controller/Advert.php";
require_once "src/Controller/Question.php";
require_once "src/Controller/Answer.php";
require_once "src/Files/Upload.php";
require_once "src/Files/Export.php";
require_once "src/Controller/UserEducation.php";
require_once "src/Controller/UserExperience.php";
require_once "src/Controller/UserSkill.php";
require_once "src/Controller/VideoQuestion.php";
require_once "src/Controller/City.php";
require_once "src/Controller/District.php";
require_once "src/Controller/Role.php";
require_once "src/Controller/University.php";
require_once "src/Controller/Department.php";
require_once "src/Controller/Faculty.php";

$db = new \DB\DB();
//$user = new User();

SimpleRouter::setDefaultNamespace('\Controller');

SimpleRouter::group(['prefix' => '/users'], function () {
    SimpleRouter::post("/register", [User::class, "register"]);  // checked
    SimpleRouter::post("/login", [User::class, "login"]); // checked

    SimpleRouter::get("/", [User::class, "getUsers"]); // checked
    SimpleRouter::get("/{id}", [User::class, "getUserByID"]); // checked
    SimpleRouter::put("/addPhoto", [User::class, "updatePhoto"]); // checked
    SimpleRouter::get("/getPhoto/{id}", [User::class, "getPhoto"]); // checked
    SimpleRouter::get("/getQues", [User::class, "getAllVideoQues"]);
//    SimpleRouter::get("/getVideo/{id}", [User::class, "getVideoById"]);
    SimpleRouter::get("/getInfo/{id}", [User::class, "getUserInfo"]); // checked
    SimpleRouter::put("/updateInfo", [User::class, "updateUserProfile"]); // checked
    SimpleRouter::post("/addInfo", [User::class, "addInfo"]); // checked
    SimpleRouter::delete("/delVideoUser/{id}/{quesId}", [User::class, "deleteVideoWithQuestion"]); // checked
});

SimpleRouter::group(['prefix' => 'questions'], function () {
    SimpleRouter::post("/create", [Question::class, "createQuestion"]); //checked
});

SimpleRouter::group(['prefix' => 'uservideos'], function () {
    SimpleRouter::post("/create", [UserVideo::class, "createUserVideo"]); // checked
});

SimpleRouter::group(['prefix' => 'videoquestions'], function () {
    SimpleRouter::post("/create", [VideoQuestion::class, "createVideoQuestion"]); // checked
});

SimpleRouter::group(['prefix' => '/cities'], function () {
    SimpleRouter::post("/create", [City::class, "createCity"]); // checked
    SimpleRouter::get("/all", [User::class, "getAllCity"]); // checked
});

SimpleRouter::group(['prefix' => '/districts'], function () {
    SimpleRouter::post("/create", [District::class, "createDistrict"]); // checked
    SimpleRouter::get("/{city}", [User::class, "getIlcebyID"]); // checked
});


SimpleRouter::group(['prefix' => '/roles'], function () {
    SimpleRouter::post("/create", [Role::class, "createRole"]); // checked
    SimpleRouter::get("/{id}", [User::class, "getUserRolebyID"]); // checked
});

SimpleRouter::group(['prefix' => '/universities'], function () {
    SimpleRouter::post("/create", [University::class, "createUniversity"]); // checked
});

SimpleRouter::group(['prefix' => '/departments'], function () {
    SimpleRouter::post("/create", [Department::class, "createDepartment"]); // checked
});

SimpleRouter::group(['prefix' => '/faculties'], function () {
    SimpleRouter::post("/create", [Faculty::class, "createFaculty"]); // checked
});


SimpleRouter::group(['prefix' => '/education'], function () {
    SimpleRouter::post("/add", [UserEducation::class, "addEducation"]);  // checked
    SimpleRouter::get("/{userid}", [UserEducation::class, "getEducationByUserId"]); // checked
    SimpleRouter::get("/get/{id}", [UserEducation::class, "getEduByEduID"]); // checked
    SimpleRouter::get("/getDepartmentByID/{facultyid}", [UserEducation::class, "getBolumByFakId"]); // checked
    SimpleRouter::get("/getFacultiesByUniversity/{universityid}", [UserEducation::class, "getFakulteByUniId"]); // checked
    SimpleRouter::get("/universities/getAll", [UserEducation::class, "getAllUni"]); // checked
    SimpleRouter::get("/departments/getAll", [UserEducation::class, "getAllDep"]); // checked
    SimpleRouter::put("/update/{id}", [UserEducation::class, "updateEducation"]); // checked
    SimpleRouter::delete("/delete/{id}", [UserEducation::class, "deleteEducation"]); // checked
    SimpleRouter::get("/faculties/getAll", [UserEducation::class, "getAllFakulte"]); // checked
});

SimpleRouter::group(['prefix' => '/experiences'], function () {
    SimpleRouter::post("/add", [UserExperience::class, "addExperience"]); // checked
    SimpleRouter::get("/{userid}", [UserExperience::class, "getExperienceByUserId"]); // checked
    SimpleRouter::get("/getByID/{id}", [UserExperience::class, "getExpByExpID"]); // checked
    SimpleRouter::put("/update/{id}", [UserExperience::class, "updateExperience"]); // checked
    SimpleRouter::delete("/delete/{id}", [UserExperience::class, "deleteExperience"]); // checked
});

SimpleRouter::get("/soft", [UserSkill::class, "getAllSoftSkill"]);
SimpleRouter::group(['prefix' => '/skills'], function () {
    SimpleRouter::post("/create", [UserSkill::class, "createSkill"]); // checked

    SimpleRouter::post("/createForUser", [UserSkill::class, "createSkillForUser"]); // checked
    SimpleRouter::put("/update", [UserSkill::class, "updateSkill"]); // checked
    SimpleRouter::get("/{id}", [UserSkill::class, "getOneSkillByID"]); // checked
    SimpleRouter::delete("/delete/{userid}/{skillid}", [UserSkill::class, "deleteSkill"]); // checked
    SimpleRouter::get("/getByUser/{userid}", [UserSkill::class, "getSkillByUserId"]); // checked
    SimpleRouter::get("/", [UserSkill::class, "getAllSkill"]); // checked

    SimpleRouter::post("/createSoftSkill", [UserSkill::class, "createSoftSkill"]);  // checked

    SimpleRouter::put("/soft/update", [UserSkill::class, "updateSoft"]); // checked
    SimpleRouter::post("/soft/create", [UserSkill::class, "createSoft"]); // checked
    SimpleRouter::get("/soft/{id}", [UserSkill::class, "getOneSoftByID"]); // checked
    SimpleRouter::delete("/soft/delete/{userid}/{softid}", [UserSkill::class, "deleteSoft"]);  // checked
    SimpleRouter::get("/soft/get/{userid}", [UserSkill::class, "getSoftSkillByUserID"]); // checked


    SimpleRouter::post("/languages/createLang", [UserSkill::class, "createLang"]);  // checked


    SimpleRouter::put("/languages/update", [UserSkill::class, "updateLaung"]); // checked
    SimpleRouter::post("/languages/create", [UserSkill::class, "createLaung"]); // checked
    SimpleRouter::delete("/languages/delete/{userid}/{langid}", [UserSkill::class, "deleteLaung"]); // checked
    SimpleRouter::get("/languages/{id}", [UserSkill::class, "getOneLaungById"]); // if a user has multiple languages then every record will be deleted
});

SimpleRouter::get("/languages", [UserSkill::class, "getAllLaung"]); // checked
SimpleRouter::get("/languages/{userid}", [UserSkill::class, "getLaungByUserID"]); // checked

SimpleRouter::group(['prefix' => '/companies'], function () {
    SimpleRouter::post("/createAuthorized", [Company::class, "createCompanyAuthorizedPerson"]);
    SimpleRouter::post("/create", [Company::class, "createCompany"]); // checked
    SimpleRouter::get("/", [Company::class, "getCompanies"]); // checked
    SimpleRouter::get("/{id}", [Company::class, "getCompanyByID"]); // checked
    SimpleRouter::delete("/{id}", [Company::class, "deleteCompany"]); // checked
    SimpleRouter::put("/update/{id}", [Company::class, "updateCompany"]);
});


SimpleRouter::group(['prefix' => '/adverts'], function () {
    SimpleRouter::post("/saveVideo", [Advert::class, "insertVideo"]); // checked
    SimpleRouter::post("/create", [Advert::class, "createAdvert"]); // checked
    SimpleRouter::get("/{id}", [Advert::class, "getAdvertsById"]); // checked
    SimpleRouter::delete("/delete/{id}", [Advert::class, "deleteAdvert"]); // checked
    SimpleRouter::get("/company/{companyid}", [Advert::class, "getAdvertsByCompany"]); // checked
    SimpleRouter::post("/apply", [Advert::class, "applyAdvert"]); // checked
    SimpleRouter::get("/{userid}/{advertid}", [Advert::class, "getApply"]); // checked

});

SimpleRouter::get("/getAdverts", [Advert::class, "getAdverts"]); // checked
SimpleRouter::get("/applications/{advertid}", [Advert::class, "getApplyAdvert"]); // checked

SimpleRouter::group(['prefix' => '/answers'], function () {
    SimpleRouter::post("/", [Answer::class, "answerQuestion"]); // checked
});

SimpleRouter::group(['prefix' => '/upload'], function () {
    SimpleRouter::post("/", [Upload::class, "upload"]);
});

SimpleRouter::group(['prefix' => '/export'], function () {
    SimpleRouter::get("/users", [Export::class, "exportUsers"]);
    SimpleRouter::get("/apply", [Export::class, "applyJob"]);
});

SimpleRouter::start();
