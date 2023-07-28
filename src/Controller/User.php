<?php

namespace Controller\User;

require_once "src/Model/User.php";

use Model\Users;

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

    public function login()
    {
        if($_POST["email"]) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $ok = $this->users->login($email, "", $password);
            if(isset($ok)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode("Email or password is wrong. Please try again.");
            }
        } else if($_POST["username"]) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $ok = $this->users->login("", $username, $password);
            if(isset($ok)) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode("Email or password is wrong. Please try again.");
            }
        }
    }

    public function getUsers()
    {
        $result = $this->users->getAllUsers();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function getUserByID($id)
    {
        $result = $this->users->getUserByID($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }



}