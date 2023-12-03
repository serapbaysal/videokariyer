<?php
namespace Model\UserSkills;
use DB\DB;
class UserSkill{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function createSkill($name, $status)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO skills (id,name, status, created_at, updated_at) VALUES (?,?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("sss", $id,$name, $status);
        $stmt->execute();
        $stmt->close();
    }
    public function createSkillForUserId($user_id, $skill_id, $rating)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO user_skills (id,user, skill, rating, created_at, updated_at) VALUES (?,?, ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("sssi", $id,$user_id, $skill_id, $rating);
        $stmt->execute();
        $stmt->close();
    }

    public function updateSkillByUserId($user_id, $skill_id, $rating)
    {
        $updateSql = "UPDATE user_skills SET rating = ? WHERE user = ? AND skill = ?";
        $stmt = $this->conn->prepare($updateSql);
        $stmt->bind_param("iss", $rating, $user_id, $skill_id);
        $result = $stmt->execute();

        if($result) {
            echo ("The record has been added successfully");
        } else {
            echo ("An error has been occurred");
        }

        $stmt->close();

        return $result;
    }
    

    public function deleteSkillByUserId($user_id, $skill_id)
    {
        $deleteSql = "DELETE FROM user_skills WHERE user = ? AND skill = ?";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bind_param("ss", $user_id, $skill_id);

        if ($stmt->execute()) {
            return true; // Başarılı silme durumu
        } else {
            return false; // Silme başarısız
        }
    }
    
    public function getSkillByUserId($user_id)
    {
        $sql = "SELECT * FROM user_skills WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $skillData = $result->fetch_all(MYSQLI_ASSOC);

        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        foreach ($skillData as &$skilss) {
            $skillID = $skilss['skill'];
            $skilss['skill_name'] = $this->getSkillNameById($skillID);
           
        }
        return ($skillData);
    }

    public function getOneSkillByID($skillID)
    {
        $sql = "SELECT * FROM user_skills WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $skillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $skillData = $result->fetch_all(MYSQLI_ASSOC);

        return ($skillData);
    }


    public function getSkillNameById($skillID)
    {
        $stmt = $this->conn->prepare("SELECT name FROM skills WHERE id = ?");
        $stmt->bind_param("s", $skillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $yDepData = $result->fetch_assoc();
        return ($yDepData['name']);
    }
    public function getAllSkills()
    {
        $sql = "
                SELECT *
                FROM skills
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "name" => $row["name"],
                "id" => $row["id"],
            ];

        }

        return $data;
    }


    public function createSoftSkill($name, $status)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO soft_skills (id, name, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("sss", $id,$name, $status);
        $result = $stmt->execute();

        if ($result) {
            echo ("The record has been saved successfully");
        } else {
            echo ("An error has been occurred");
        }

        $stmt->close();
    }



    public function createSoftForUserId($user_id, $skill_id, $rating)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO user_soft_skills (id, user, skill, rating) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("ssss", $id,$user_id, $skill_id, $rating);
        $result = $stmt->execute();

        if ($result) {
            echo ("The record has been saved successfully");
        } else {
            echo ("An error has been occurred");
        }
        $stmt->close();
    }

    public function updateSoftByUserId($user_id, $skill_id, $rating)
    {
        $updateSql = "UPDATE user_soft_skills SET rating = ? WHERE user = ? AND skill = ?";
        $stmt = $this->conn->prepare($updateSql);
        $stmt->bind_param("sss", $rating, $user_id, $skill_id);
        $stmt->execute();
        $stmt->close();
    }
    

    public function deleteSoftByUserId($user_id, $skill_id)
    {
        $deleteSql = "DELETE FROM user_soft_skills WHERE user = ? AND skill = ?";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bind_param("ss", $user_id, $skill_id);
        $result = $stmt->execute();
        if ($result) {
            return true; // Başarılı silme durumu
        } else {
            return false; // Silme başarısız
        }
    }

    public function getOneSoftlByID($skillID)
    {
        $sql = "SELECT * FROM user_soft_skills WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $skillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $skillData = $result->fetch_all(MYSQLI_ASSOC);
        return ($skillData);
    }
    public function getSoftSkillByUserID($user_id)
    {
        $sql = "SELECT * FROM user_soft_skills WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $skillData = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        foreach ($skillData as &$skilss) {
            $skillID = $skilss['id'];
            $skilss['softSkill_name'] = $this->getSoftSkillNameById($skillID);
           
        }
        return ($skillData);
    }
    public function getSoftSkillNameById($skillID)
    {
        $stmt = $this->conn->prepare("SELECT name FROM soft_skills WHERE id = ?");
        $stmt->bind_param("s", $skillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $yDepData = $result->fetch_assoc();
        return ($yDepData['name']);
    }

    public function getAllSoftSkills()
    {
        $sql = "
                SELECT *
                FROM soft_skills
            ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $results = $result->fetch_assoc();

        return $results;
    }


    public function createLang($name, $status)
    {
        $id = uniqid();

        $sql = "
            INSERT INTO languages (id, name, status, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("sss", $id,$name, $status);
        $result = $stmt->execute();

        if ($result) {
            echo ("The record has been saved successfully");
        } else {
            echo ("An error has been occurred");
        }
        $stmt->close();
    }


    public function createLaungForUserId($user_id, $language_id, $rating)
    {
        $id = uniqid();
        $insertSql = "INSERT INTO user_languages (id, user, language, score) VALUES (?,?, ?, ?)";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bind_param("ssss", $id,$user_id, $language_id, $rating);
        $result = $stmt->execute();
        if ($result) {
            echo ("The record has been saved successfully");
        } else {
            echo ("An error has been occurred");
        }

        $stmt->close();
    }

    public function updateLaungByUserId($user_id, $language_id, $rating)
    {
        $updateSql = "UPDATE user_languages SET score = ? WHERE user = ? AND language = ?";
        $stmt = $this->conn->prepare($updateSql);
        $stmt->bind_param("iss", $rating, $user_id, $language_id);
        $stmt->execute();
        $stmt->close();
    }
    

    public function deleteLaungByUserId($user_id, $language_id)
    {
        $deleteSql = "DELETE FROM user_languages WHERE user = ? AND language = ?";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bind_param("ss", $user_id, $language_id);
        
        if ($stmt->execute()) {
            return true; // Başarılı silme durumu
        } else {
            return false; // Silme başarısız
        }
    }
    public function getAllLaung()
    {
        $sql = "
                SELECT *
                FROM languages
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $rows;
    }
    public function getOneLaung($user_id)
    {
        $sql = "SELECT * FROM user_languages WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $oneLang = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın

        return ($oneLang);
    }
    public function getLaungByUserID($user_id)
    {
        $sql = "SELECT * FROM user_languages WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $laungData = $result->fetch_all(MYSQLI_ASSOC);
    
        // Eğitim verilerindeki her bir üniversite ID'sini kullanarak üniversite ismini alın
        return ($laungData);
    }
    public function getLaungNameById($laungID)
    {
        $stmt = $this->conn->prepare("SELECT name FROM languages WHERE id = ?");
        $stmt->bind_param("s", $laungID);
        $stmt->execute();
        $result = $stmt->get_result();
        $yDepData = $result->fetch_assoc();
        return $yDepData['name'];
    }
}