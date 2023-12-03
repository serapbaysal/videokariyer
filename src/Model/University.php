<?php


namespace Model\Universities;

use DB\DB;

class University
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createUniversity($name, $city, $status)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO universities (id, name, city, status)
            VALUES(?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $id,$name, $city, $status);
        $result = $stmt->execute();
        if($result) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
        $stmt->close();
    }
}