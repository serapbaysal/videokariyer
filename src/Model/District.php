<?php


namespace Model\Districts;

use DB\DB;

class District
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createDistrict($name, $city)
    {
        $id = uniqid();

        $sql = "
            INSERT INTO districts (id, name, city)
            VALUES (?, ?, ?)
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("sss",$id,$name, $city);

        $final = $result->execute();

        if($final) {
            echo "The record has been created successfully";
        } else {
            echo "An error has been occurred";
        }
    }
}
