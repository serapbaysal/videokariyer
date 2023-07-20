<?php

namespace DB;

class DB
{
    public function connectDB()
    {
        $server = "127.0.0.1";
        $user = "root";
        $password = "my-secret-pw";
        $db = "videokariyer";

        $conn = mysqli_connect($server, $user, $password, $db);

        return $conn;
    }
}