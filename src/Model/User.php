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
            
            $userSql = "
                SELECT password
                FROM users
                WHERE email = '$email'
            ";
            $userResult = mysqli_query($this->conn, $userSql);

            $rows = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

            foreach ($rows as $row) {
                $sql = "
                UPDATE users
                SET access_token = ?
                WHERE email = ?
            ";

                $result = $this->conn->prepare($sql);

                $access_token = JWT::encode($payload, $_ENV["JWT_SECRET"], "HS256");

                if(password_verify($password,$row["password"])) {
                    $result->bind_param("ss", $access_token, $email);
                    $result->execute();
                }

                if(!$result->error) {
                    echo $access_token;
                }
            }

            
         
        } else if ($username != "") {
            $payload = [
                $username, $password
            ];

            $userSql = "
                SELECT password
                FROM users
                WHERE username = '$username'
            ";
            $userResult = mysqli_query($this->conn, $userSql);

            $rows = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

            foreach ($rows as $row) {

                $sql = "
                UPDATE users
                SET access_token = ?
                WHERE username = ?
            ";

                $access_token = JWT::encode($payload, getenv("JWT_SECRET"), "HS256");

                $result = $this->conn->prepare($sql);
                if(password_verify($password, $row["password"])) {
                    $result->bind_param("ss", $access_token, $username);
                    $result->execute();
                }
                if (!$result->error) {
                    echo $access_token;
                }
            }
        }

    }

    public function getAllUsers()
    {
        $sql = "
                SELECT *
                FROM users
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "name" => $row["name"] . " " . $row["surname"],
                "email" => $row["email"],
                "username" => $row["username"]
            ];

        }

        return $data;
    }

    public function getUserByID($id)
    {
        $sql = "
           SELECT *
           FROM users
           WHERE id= '$id'
        ";
        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_row($result);

        return [
            // name, surname
            "name" => $row[1] . " " . $row[2],
            "email" => $row[3],
            "username" => $row[4]
        ];
    }
}