<?php

namespace Controller\Role;

require_once "src/Model/Role.php";

use Model\Roles;


class Role
{
    private $roles;


    public function __construct()
    {
        $this->roles = new Roles\Role();
    }

    public function createRole()
    {
        $name = $_POST["name"];

        $this->roles->createRole($name);
    }
}