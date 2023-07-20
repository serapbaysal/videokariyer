<?php

namespace DB;

class DB
{
    public function connectDB($server, $user, $password, $db)
    {
        $conn = mysqli_connect($server, $user, $password, $db);

        echo "<pre>";
        var_dump($conn);
        echo "<pre>";
        die;;
    }
}