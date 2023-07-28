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

    }

    public function createQuestion($question)
    {
        $sql = "
            INSERT INTO questions(question)
            VALUES(?)
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("s",$question);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo  json_encode("New record created successfully");
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}