<?php


namespace Controller\Faculty;

require_once "src/Model/Faculty.php";

use Model\Faculties;


class Faculty
{
    private $faculties;


    public function __construct()
    {
        $this->faculties = new Faculties\Faculty();
    }

    public function createFaculty()
    {
        $name = $_POST["name"];
        $university = $_POST["university"];
        $status = $_POST["status"];

        $this->faculties->createFaculty($name, $university, $status);
    }

}