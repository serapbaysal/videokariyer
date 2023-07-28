<?php

namespace Model\Companies;

use DB\DB;

class Company
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

    }

    public function createCompany($name)
    {
        $sql = "
            INSERT INTO companies(name, created_at, updated_at)
            VALUES(?, NOW(), NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("s",$name);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo  json_encode("New record created successfully");
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }

    public function getCompanies()
    {
        $sql = "
                SELECT *
                FROM companies
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        

        foreach ($rows as $row) {
            $data[] = [
                "name" => $row["name"]
            ];

        }

        return $data;

    }

    public function getCompanyByID($id)
    {
        $sql = "
                SELECT *
                FROM companies
                WHERE id='$id'
            ";
        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_row($result);

        $data[] = [
            // row is an array, we can fetch name with a number
            "name" => $row[2]
        ];

        return $data;
    }

    public function updateCompany($id, $name)
    {
        $sql = "
                UPDATE companies
                SET name='$name', updated_at = NOW()
                WHERE id = '$id'
            ";
        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $getSql = "
                SELECT name
                FROM companies
                WHERE id = '$id'
            ";

            $getResult = mysqli_query($this->conn, $getSql);

            $row = mysqli_fetch_row($getResult);

            echo "<pre>";
            var_dump($row);
            echo "<pre>";
            die;
            $data[] = [
                // row is an array, we can fetch name with a number
                "name" => $row
            ];

            return $data;

        }
    }

    public function deleteCompany($id)
    {
        $sql = "
            DELETE 
            FROM companies
            WHERE id = '$id'
        ";

        if ($conn->query($sql) === TRUE) {
            echo "Record successfully deleted";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
}