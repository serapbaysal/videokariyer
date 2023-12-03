<?php
namespace Model\UserEducations;
use DB\DB;

class UserEducation{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }
    public function addEducation($user_id, $university, $degree, $department, $start_year, $start_month, $end_year, $end_month)
    {
        $id = uniqid();
        $stmt = $this->conn->prepare("INSERT INTO user_education (
                            id,
                            user, 
                            universities, 
                            degree, 
                            department, 
                            start_year, 
                            start_month, 
                            end_year, 
                            end_month) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $id,$user_id, $university, $degree, $department, $start_year, $start_month, $end_year, $end_month);
       
        $result = $stmt->execute();
        $stmt->close();

        if($result) {
            echo "The record has been added successfully";
            return true;
        }
        echo "An error has been occurred";
        return false;
    }

    public function getEducationByUserId($user_id)
    {
        $sql = "SELECT * FROM user_education WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $educationData = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        foreach ($educationData as &$education) {
            $university_id = $education['universities'];
            $dep_id = $education['department'];
            $education['university_name'] = $this->getUniversityNameById($university_id);
            $education['dep_name'] = $this->getDepNameById($dep_id);
        }
    
        return $educationData;
    }

    public function getEduByEduID($edu_id)
    {
        $sql = "SELECT * FROM user_education WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $edu_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getFakulteByUniId($user_id)
    {
        $sql = "SELECT * FROM faculties WHERE university = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $educationData = $result->fetch_all(MYSQLI_ASSOC);
        return $educationData;
    }
    public function getBolumByFakId($department)
    {
        $sql = "SELECT * FROM departments WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result = $stmt->get_result();
        $educationData = $result->fetch_all(MYSQLI_ASSOC);
        return $educationData;
    }


    public function getAllUni()
    {
        $sql = "
                SELECT *
                FROM universities
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $rows;
    }

    public function getAllDep()
    {
        $sql = "
                SELECT *
                FROM departments
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        return $rows;
    }
    public function getAllFakulte()
    {
        $sql = "
                SELECT *
                FROM faculties
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $rows;
    }

    public function getUniversityNameById($university_id)
    {
        $stmt = $this->conn->prepare("SELECT name FROM universities WHERE id = ?");
        $stmt->bind_param("s", $university_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $universityData = $result->fetch_assoc();
        return $universityData[' name'];
    }

public function getDepNameById($dep_id)
{
    $stmt = $this->conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->bind_param("s", $dep_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $universityData = $result->fetch_assoc();
    return $universityData['name'];
}

    public function updateEducation($university, $degree,$department,$start_year,$start_month,$end_year,$end_month,$id)
    {
        $updateFields = array(
            'universities' => $university,  // Specify the fields you want to update and their new values
            'degree' => $degree,
            'department' => $department,
            'start_year' => $start_year,
            'start_month' => $start_month,
            'end_year' => $end_year,
            'end_month' => $end_month,
        );
        $sql = "UPDATE user_education 
                SET ";

        foreach ($updateFields as $field => $value) {
            if($value != null) {
                $sql .= "$field = $value ";
            }

        }

        $sql .= "WHERE id = '" . $id . "';";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    public function deleteEducation($id)
    {
        $sql = "DELETE FROM user_education WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }
}