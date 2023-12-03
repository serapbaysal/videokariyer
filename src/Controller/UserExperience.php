<?php
namespace Controller\UserExperience;
use Model\UserExperiences;
require_once "src/Model/UserExperience.php";
class UserExperience{
    private $experience;
    public function __construct()
    {
        $this->experience = new UserExperiences\UserExperience();
    }

    public function addExperience()
{
//    $Data = json_decode(file_get_contents('php://input'), true);
//    $user_id = $Data['user_id'];
//    $department = $Data['department'];
//    $title = $Data['title'];
//    $company = $Data['company'];
//    $start_time = $Data['start_time'];
//    $end_time = $Data['end_time'];
//    $id = $Data['id'];

        $user_id = $_POST['user'];
        $department = $_POST['department'];
        $title = $_POST['title'];
        $company = $_POST['company'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $id = $_POST['id'];
    $this->experience->addExperience(
        $user_id,
        $department,
        $title,
        $company,
        $start_time,
        $end_time,
    );
    echo "Deneyim Ekleme Başarılı.";
}
    
    public function getExperienceByUserId($user_id)
    {
        try {
            $experienceData = $this->experience->getExperienceByUserId($user_id);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getExpByExpID($exp_id)
    {
        try {
            $experienceData = $this->experience->getExpByExpID($exp_id);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

    public function updateExperience()
    {
            $data = json_decode(file_get_contents('php://input'), true);
            $user_id = $data['user_id'];
            $department = $data['department'];
            $title = $data['title'];
            $company = $data['company'];
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];
            $id = $data['id'];
        
            $result = $this->experience->updateExperience(
                $user_id,
                $department,
                $title,
                $company,
                $start_time,
                $end_time,
                $id
            );

            json_encode($result);

        
    }

    public function deleteExperience($id)
    {
        try {
            $this->experience->deleteExperience($id);
            return "Deneyim başarıyla silindi.";
        } catch (\Exception $e) {
            return "Deneyim silinirken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getAllDepartmant()
    {
        $dep = $this->experience->getAllDepartmant();
        echo json_encode($dep);
    }
    
}