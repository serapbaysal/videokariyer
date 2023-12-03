<?php

namespace Controller\District;

require_once "src/Model/District.php";

use Model\Districts;


class District
{
    private $districts;


    public function __construct()
    {
        $this->districts = new Districts\District();
    }
    public function createDistrict()
    {
        $name = $_POST["name"];
        $city = $_POST["city"];


        $this->districts->createDistrict($name, $city);
    }
}