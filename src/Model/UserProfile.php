<?php

namespace Model\UserProfiles;
use DB\DB;
class UserProfile
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
    }

    public function createUserProfile($user, $district, $city, $addres, $web, $email, $link, $github, $cover)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO user_profiles(id, user, created_at, updated_at, district,
                                      city, addres, web, email, link, github, cover)
            VALUES(?,?,NOW(),NOW(),?,?,?,?,?,?,?,?)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $id,$user,$district, $city, $addres, $web, $email, $link, $github, $cover);
        $result = $stmt->execute();
        if($result) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
        $stmt->close();
    }
}