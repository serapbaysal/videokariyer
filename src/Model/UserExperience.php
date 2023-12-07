<?php
namespace Model\UserExperiences;
use DB\DB;
class UserExperience{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }
    public function addExperience($user_id, $department, $title,$company ,$start_time, $end_time)
    {
        $id = uniqid();
        $stmt = $this->conn->prepare("INSERT INTO user_experiences (
                              id,
                              user, 
                              department, 
                              title, 
                              company, 
                              start_time, 
                              end_time,
                              created_at,
                              updated_at
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sssssss",$id, $user_id, $department, $title, $company, $start_time, $end_time);
        $result = $stmt->execute();
        
        $stmt->close();

        return $result;
    }

    public function getExperienceByUserId($user_id)
    {
        $sql = "SELECT * FROM user_experiences WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $experienceData = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        foreach ($experienceData as &$experince) {
            $dep_id = $experince['department'];
            $experince['department'] = $this->getDepartmantNameById($dep_id);
           
        }
        return $experienceData;
    }
    public function getDepartmantNameById($dep_id)
    {
        $stmt = $this->conn->prepare("SELECT name FROM departments WHERE id = ?");
        $stmt->bind_param("s", $dep_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $yDepData = $result->fetch_assoc();
        return $yDepData['name'];
    }

    public function getExpByExpID($exp_id)
    {
        $sql = "SELECT * FROM user_experiences WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $exp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $experienceData = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        foreach ($experienceData as &$experince) {
            $dep_id = $experince['department'];
            $experince['department'] = $this->getDepartmantNameById($dep_id);
           
        }
        return $experienceData;
    }

    public function updateExperience($user_id,$department ,$title, $company,$start_time,$end_time,$id)
    {
        $updateFields = array(
            'user_id' => $user_id,
            'department' => $department,
            'company' => $company,
            'title' => $title,
            'start_time' => $start_time,
            'end_time' => $end_time,
        );
        $sql = "UPDATE user_experiences 
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
    public function deleteExperience($id)
    {
        $sql = "DELETE FROM user_experiences WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }
    public function getAllDepartmant()
    {
        $sql = "
                SELECT *
                FROM departments
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "id" => $row["id"],
                "name" => $row["name"],
            ];

        }

        return $data;
    }
    
}