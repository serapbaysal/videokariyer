<?php

namespace Model\Adverts;

use DB\DB;

class Advert
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

    }

    public function getAdverts()
    {
        $sql = "
            SELECT companies.name AS company,  adverts.description AS description,  departments.name As department
            FROM adverts
            INNER JOIN companies ON adverts.company = companies.id
            INNER JOIN departments ON adverts.department = departments.id
        ";

        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "company" => $row["company"],
                "department" => $row["department"],
                "description" => $row["description"]
            ];

        }

        return $data;

    }
}