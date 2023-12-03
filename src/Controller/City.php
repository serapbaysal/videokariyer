<?php


namespace Controller\City;

use Model\Cities;

require_once "src/Model/City.php";
class City
{
    private $cities;

    public function __construct()
    {
        $this->cities = new Cities\City();
    }

    public function createCity()
    {
        $name = $_POST["name"];

        $this->cities->createCity($name);
    }

}