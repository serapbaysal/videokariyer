<?php

namespace DB;

class DB
{
    public function connectDB()
    {
        $charset = 'utf8mb4';
        $server = "127.0.0.1";
        $user = "root";
        $password = "";
        $db = "videokariyer";
        try {
            $conn = mysqli_connect($server, $user, $password, $db);
        return $conn;
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }



//        $server = "127.0.0.1";
//        $user = "root";
//        $password = "my-secret-pw";
//        $db = "videokariyer";
//
//        $conn = mysqli_connect($server, $user, $password, $db);
//
//        return $conn;
    }
}