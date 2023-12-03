<?php


namespace Model\Faculties;

use DB\DB;

class Faculty
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createFaculty($name, $university, $status)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO faculties (id, name, university, status)
            VALUES(?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $id,$name, $university, $status);
        $result = $stmt->execute();
        if($result) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
        $stmt->close();
    }
}