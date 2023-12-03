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
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createAuthorizedPerson($name, $surname, $company, $position, $email)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO authorizedPersons(id, name, surname, company, position, email, created_at, updated_at)
            VALUES(?, ?, ?, ?,?,?, NOW(), NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("ssssss",$id,$name, $surname, $company, $position, $email);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo "New record created successfully";
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }

    }

    public function createCompany($name, $scope, $authorized_person, $address, $email, $website, $photo, $video, $social)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO companies(id, name, scope, authorized_person, address, email, website, photo, video, social_media, created_at, updated_at)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("ssssssssssss",$id,$name, $scope, $authorized_person, $address, $email, $website, $photo, $video, $social);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo "New record created successfully";
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo "Error: " . $sql . "<br>" . $this->conn->error;
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
            "name" => $row[1],
            "id" => $row[0]
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

        if ($this->conn->query($sql) === TRUE) {
            echo "Record successfully deleted";
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }

    }
}