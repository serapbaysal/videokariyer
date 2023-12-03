<?php

namespace Model\Questions;

use DB\DB;

class Question
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createQuestion($question)
    {
        $id = uniqid();
        $sql = "
            INSERT INTO questions(id, question, created_at, updated_at)
            VALUES(?,?,NOW(), NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("ss",$id,$question);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo  "New record created successfully";
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo"Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
}