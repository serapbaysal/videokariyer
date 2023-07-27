<?php
namespace Model\Users;

use DB\DB;
use Firebase\JWT\JWT;

use Dotenv\Dotenv;

mysqli_report(MYSQLI_REPORT_OFF);

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

        $result = mysqli_prepare($this->conn, $sql);
        $hashedPass = password_hash($password, PASSWORD_BCRYPT);

        mysqli_stmt_bind_param($result, "ssssss", $name, $surname, $email, $username, $phone, $hashedPass);
        $ok = mysqli_stmt_execute($result);

        if($ok) {
            echo "Record successfully inserted.";
        } else {
           http_response_code(400);
            echo "An account with that credentials is already exists. Please log in or sign up with another credentials.";
        }
    }

    public function login(string $email, $username, $password)
    {
        if ($email != "") {
            $payload = [
                $email, $password
            ];

            $userSql = "
                SELECT CONCAT(name , ' ' , surname) AS fullname, email,  password, phone
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
                $verified = password_verify($password, $row["password"]);


                if ($verified) {
                    $result->bind_param("ss", $access_token, $email);
                    $result->execute();
                } else {
                    return $verified;
                }

                if (!$result->error) {
                    $data = [
                        "access token" => $access_token,
                        "name" => $row["fullname"],
                        "email" => $row["email"],
                        "phone" => $row["phone"]
                    ];
                    echo json_encode($data);
                }
            }


        } else if ($username != "") {
            $payload = [
                $username, $password
            ];

            $userSql = "
                SELECT CONCAT(name , ' ' , surname) AS fullname, email,  password, phone
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
                $verified = password_verify($password, $row["password"]);

                if ($verified) {
                    $result->bind_param("ss", $access_token, $username);
                    $result->execute();
                } else {
                    return $verified;
                }
                if (!$result->error) {
                    $data = [
                        "access token" => $access_token,
                        "name" => $row["fullname"],
                        "email" => $row["email"],
                        "phone" => $row["phone"]
                    ];
                   echo json_encode($data);
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