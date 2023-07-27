<?php

namespace Files\Export;

use DB\DB;

class Export
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

    }

    public function exportUsers()
    {
        $sql = "
            SELECT *
            FROM users
        ";


        $fileName = "members-data_" . date('Y-m-d') . ".xls";
        $fields = array("id", "name", "surname", "username", "phone", "email_verified_at", "password", "access_token", "status", "created_at", "updated_at", "deleted_at");

        $excelData = implode("\t", array_values($fields)) . "\n";

        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


            foreach ($rows as $row) {
                $lineData = array($row['id'], $row['name'], $row['surname'], $row['username'], $row['phone'], $row['email_verified_at'], $row['password'], $row["access_token"], $row['status'], $row['created_at'], $row['updated_at'], $row["deleted_at"]);
                $excelData .= implode("\t", array_values($lineData)) . "\n";
            }

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$fileName\"");

            echo $excelData;

            exit;
    }

    public function exportEducation()
    {
        $sql = "
            SELECT ue.user_id, universities.name, ue.degree, ue.start_year, ue.end_year
            FROM user_education AS ue
            INNER JOIN universities ON user_education.university = universities.id
        ";


        $fileName = "education-data_" . date('Y-m-d') . ".xls";
        $fields = array("user_id", "university", "degree", "started_at", "ended_at");

        $excelData = implode("\t", array_values($fields)) . "\n";

        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $lineData = array($row['user_id'], $row['university'], $row['degree'], $row['started_at'], $row['ended_at']);
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        echo $excelData;

        exit;
    }
}
