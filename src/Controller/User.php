<?php

namespace Controller\User;

require_once "src/Model/User.php";

use Model\Users;
use DB\DB;

class User
{
    private $users;

    public function __construct()
    {
        $this->users = new Users\User();
    }

    public function register()
    {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];

        $this->users->register($name, $surname, $email, $username, $phone, $password);
    }
}