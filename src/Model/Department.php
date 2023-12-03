<?php

namespace Model\Departments;

use DB\DB;

class Department
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createDepartment($name, $status)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO departments(id, name, status, created_at, updated_at)
            VALUES(?, ?, ?, NOW(), NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("sss",$id,$name, $status);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo  "New record created successfully";
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
}