<?php

namespace Controller\Company;

use Model\Companies;
use function Sodium\add;

require_once "src/Model/Company.php";

class Company
{
    private $companies;


    public function __construct()
    {
        $this->companies = new Companies\Company();
    }

    public function createCompanyAuthorizedPerson()
    {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $company = $_POST["company"];
        $position = $_POST["position"];
        $email = $_POST["email"];

        $this->companies->createAuthorizedPerson($name, $surname, $company, $position, $email);
    }

    public function createCompany()
    {
        $name = $_POST["name"];
        $scope = $_POST["scope"];
        $authorized_person = $_POST["authorizedPerson"];
        $address = $_POST["address"];
        $email = $_POST["email"];
        $website = $_POST["website"];
        $photo = $_POST["photo"];
        $video = $_POST["video"];
        $social = $_POST["socialMedia"];

        $this->companies->createCompany($name, $scope, $authorized_person, $address, $email, $website, $photo, $video, $social);
    }

    public function getCompanies()
    {
        $result = $this->companies->getCompanies();

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($result);
    }

    public function getCompanyByID($id)
    {
        $result = $this->companies->getCompanyByID($id);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($result);
    }

    public function updateCompany($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $website = $data["website"];
        $photo = $data["photo"];
        $socialmedia = $data["socialmedia"];
        $address = $data["address"];

        $result = $this->companies->updateCompany($id, $website, $photo, $socialmedia, $address);

        echo json_encode($result);
    }

    public function deleteCompany($id)
    {

        $this->companies->deleteCompany($id);
    }
}