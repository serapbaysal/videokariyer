<?php

namespace Model\Users;

use DB\DB;

class User
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

    }
    public function register(string $name, $surname, $email, $username, $phone, $password)
    {
        $sql = "
            INSERT INTO users(name, surname, email, username, phone, password, created_at, updated_at)
            VALUES(?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";

        $hashedPass = password_hash($password, PASSWORD_BCRYPT);

        $result = $this->conn->prepare($sql);
        $result->bind_param("ssssss",$name, $surname, $email, $username, $phone, $hashedPass);

        $result->execute();
        if(!$result->error){
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
}