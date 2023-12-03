<?php

namespace Controller\Department;

use Model\Departments;

require_once "src/Model/Department.php";

class Department
{
    private $departments;


    public function __construct()
    {
        $this->departments = new Departments\Department();
    }

    public function createDepartment()
    {
        $name = $_POST["name"];
        $status = $_POST["status"];

        $this->departments->createDepartment($name, $status);
    }


}