<?php
namespace Controller\UserSkill;
use Model\UserSkills;
require_once "src/Model/UserSkill.php";
class UserSkill{

    private $userSkill;
    public function __construct()
    {
        $this->userSkill = new UserSkills\UserSkill();
    }

    public function createSkill()
    {
        $name = $_POST["name"];
        $status = $_POST["status"];
        $this->userSkill->createSkill($name, $status);
        echo "Deneyim Ekleme Başarılı.";

    }

    public function createSkillForUser()
    {
            $user_id = $_POST["user_id"];
            $skill_id = $_POST["skill_id"];
            $rating = $_POST["rating"];

             $this->userSkill->createSkillForUserId($user_id, $skill_id, $rating);
             echo "Deneyim Ekleme Başarılı.";
    }

    public function updateSkill()
    {
        $data =  json_decode(file_get_contents('php://input'), true);
        if ($data) {
            $user_id = $data["user_id"];
            $skill_id = $data["skill_id"];
            $rating = $data["rating"];

            $this->userSkill->updateSkillByUserId($user_id, $skill_id, $rating);

        } else {
            echo "Invalid JSON Data.";
        }
    }

    public function deleteSkill($user_id, $skill_id)
    {
        if ($this->userSkill->deleteSkillByUserId($user_id, $skill_id)) {
            return "Skill deleted successfully.";
        } else {
            return "Failed to delete skill.";
        }
    }
    public function getOneSkillByID($skillID){
        try {
            $skillData = $this->userSkill->getOneSkillByID($skillID);
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

    public function getSkillByUserID($user_id)
    {
        try {
            $skillData = $this->userSkill->getSkillByUserId($user_id);
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getAllSkill()
    {
        try {
            $skillData = $this->userSkill->getAllSkills();
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Soft Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

    public function createSoftSkill()
    {
        $name = $_POST["name"];
        $status = $_POST["status"];

        $this->userSkill->createSoftSkill($name, $status);
    }


    public function createSoft()
    {
        $user_id = $_POST["user_id"];
        $skill_id = $_POST["skill_id"];
        $rating = $_POST["rating"];
        $this->userSkill->createSoftForUserId($user_id, $skill_id, $rating);
    }

    public function updateSoft()
    {
        $data =  json_decode(file_get_contents('php://input'), true);
        if ($data) {
            $user_id = $data["user_id"];
            $skill_id = $data["skill_id"];
            $rating = $data["rating"];
             $this->userSkill->updateSoftByUserId($user_id, $skill_id, $rating);
                echo "Skill uptaded successfully.";
        } else {
            echo "Invalid JSON Data.";
        }
    }

    public function deleteSoft($user_id, $soft_id)
    {
        if ($this->userSkill->deleteSoftByUserId($user_id, $soft_id)) {
            return "Skill deleted successfully.";
        } else {
            return "Failed to delete skill.";
        }
    }
    public function getOneSoftByID($skillID){
        try {
            $skillData = $this->userSkill->getOneSoftlByID($skillID);
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getSoftSkillByUserID($user_id)
    {
        try {
            $softSkill = $this->userSkill->getSoftSkillByUserId($user_id);
            return json_encode($softSkill);
        } catch (\Exception $e) {
            return "Soft Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getAllSoftSkill()
    {
        try {
            $softData = $this->userSkill->getAllSoftSkills();
            return json_encode($softData);
        } catch (\Exception $e) {
            return "Soft Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

    public function createLang()
    {
        $name = $_POST["name"];
        $status = $_POST["status"];

        $this->userSkill->createLang($name, $status);
    }

    public function createLaung()
    {
            $user_id = $_POST["user_id"];
            $language_id = $_POST["language_id"];
            $rating = $_POST["rating"];
             $this->userSkill->createLaungForUserId($user_id, $language_id, $rating);
    }

    public function updateLaung()
    {
        $data =  json_decode(file_get_contents('php://input'), true);
        if ($data) {
            $user_id = $data["user_id"];
            $language_id = $data["language_id"];
            $rating = $data["score"];
             $this->userSkill->updateLaungByUserId($user_id, $language_id, $rating);
                echo "Skill uptaded successfully.";
        } else {
            echo "Invalid JSON Data.";
        }
    }

    public function deleteLaung($user_id, $language_id)
    {
        if ($this->userSkill->deleteLaungByUserId($user_id, $language_id)) {
            return "Skill deleted successfully.";
        } else {
            return "Failed to delete skill.";
        }
    }

    public function getAllLaung()
    {
            $lanData = $this->userSkill->getAllLaung();

            return json_encode($lanData);
     }
    
    public function getLaungByUserID($user_id)
    {
        try {
            $skillData = $this->userSkill->getLaungByUserID($user_id);
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getOneLaungById($user_id)
    {
        try {
            $skillData = $this->userSkill->getOneLaung($user_id);
            return json_encode($skillData);
        } catch (\Exception $e) {
            return "Skiller alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

}