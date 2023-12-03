<?php

namespace Model\Answers;

use DB\DB;

class Answer
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createAnswer($user, $question, $advert, $answer)
    {
        $id = uniqid();
        $sql = "
                INSERT INTO answers(id, user, question, advert, video)
                VALUES(?, ?, ?, ?, ?)
            ";
        $result = $this->conn->prepare($sql);
        $result->bind_param("sssss",$id,$user, $question, $advert, $answer);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode("New record created successfully");
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode("Error: " . $sql . "<br>" . $this->conn->error);
        }
    }
}