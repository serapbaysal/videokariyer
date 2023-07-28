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

    }

    public function createAnswer($user, $question, $advert, $answer)
    {
        $sql = "
                INSERT INTO answers(user_id, question_id, advert_id, answer)
                VALUES(?, ?, ?, ?)
            ";
        $result = $this->conn->prepare($sql);
        $result->bind_param("iiis",$user, $question, $advert, $answer);

        $result->execute();

        if (!$result->error) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode("New record created successfully");
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode("Error: " . $sql . "<br>" . $conn->error);
        }
    }
}