<?php

namespace Model\Applies;

use DB\DB;

class Apply
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

    }

    public function applyJob($user, $advert)
    {
        $sql = "
            INSERT INTO applies (user, advert, applied_at)
            VALUES  ( ? , ? , NOW())
        ";

        $result = $this->conn->prepare($sql);
        $result->bind_param("si",$user, $advert);

        $result->execute();

        if (!$result->error) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}