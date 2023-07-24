<?php

namespace Controller\Apply;

use Model\Applies;

require_once "src/Model/Apply.php";
class Apply
{
    private $applies;

    public function __construct()
    {
        $this->applies = new Applies\Apply();
    }

    public function applyJob()
    {
        $user = $_POST["user"];
        $advert = $_POST["advert"];

        $this->applies->applyJob($user, $advert);
    }

}