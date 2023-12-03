<?php


namespace Model\VideoQuestions;
use DB\DB;

class VideoQuestion
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
    }

    public function createVideoQuestion($question)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO video_questions (id, question, created_at, updated_at) 
                      VALUES (?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("ss", $id, $question);
        $result = $stmt->execute();
        if($result) {
            echo ("The record has been saved successfully.");
        } else {
            echo ("Invalid Data");
        }
        $stmt->close();
    }
}