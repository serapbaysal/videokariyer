<?php

namespace Controller\Advert;

use Model\Adverts;

require_once "src/Model/Advert.php";
class Advert
{
    private $adverts;

    public function __construct()
    {
        $this->adverts = new Adverts\Advert();
    }

    public function getAdverts()
    {
        $result = $this->adverts->getAdverts();

        header("Content-Type: application/json");

        echo json_encode($result);
    }

}