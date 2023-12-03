<?php

namespace Controller\University;

require_once "src/Model/University.php";

use Model\Universities;


class University
{
    private $universities;


    public function __construct()
    {
        $this->universities = new Universities\University();
    }

    public function createUniversity()
    {
        $name = $_POST["name"];
        $city = $_POST["city"];
        $status = $_POST["status"];

        $this->universities->createUniversity($name, $city, $status);

    }

}