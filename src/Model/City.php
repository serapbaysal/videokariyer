<?php


namespace Model\Cities;

use DB\DB;
class City
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');

    }

    public function createCity($name)
    {
        $id = uniqid();

        $sql = "
            INSERT INTO cities (id, name)
            VALUES (?, ?)
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("ss",$id,$name);

        $final = $result->execute();

        if($final) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
    }
}