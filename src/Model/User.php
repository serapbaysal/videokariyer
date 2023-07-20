<?php

namespace Model\Users;
use DB\DB;
use Firebase\JWT\JWT;

use Dotenv\Dotenv;


$dotenv = Dotenv::createUnsafeImmutable("./src");
$dotenv->load();

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

    public function login(string $email, $username, $password)
    {
        if($email != "") {
            $payload = [
                $email, $password
            ];
            $sql = "
                UPDATE users
                SET access_token = ?
                WHERE email = ? AND password = ?
            ";

            $result = $this->conn->prepare($sql);

            $access_token = JWT::encode($payload, $_ENV["JWT_SECRET"], "HS256");
            $result->bind_param("sss", $access_token, $email, $password);
            $result->execute();
            if(!$result->error) {
                echo $access_token;
            }
        } else if ($username != "") {
            $payload = [
                $username, $password
            ];
            $sql = "
                UPDATE users
                SET access_token = ?
                WHERE username = ? AND password = ?
            ";

            $access_token = JWT::encode($payload, getenv("JWT_SECRET"),"HS256" );

            $result = $this->conn->prepare($sql);
            $result->bind_param("sss", $access_token, $username, $password);

            $result->execute();

            if(!$result->error) {
                echo $access_token;
            }
        }
        



    }
}