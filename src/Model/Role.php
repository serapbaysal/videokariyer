<?php

namespace Model\Roles;

use DB\DB;

class Role
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createRole($name)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO roles (id, name, created_at, updated_at)
            VALUES(?, ?, NOW(), NOW())
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $id,$name);
        $result = $stmt->execute();
        if($result) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
        $stmt->close();
    }
}