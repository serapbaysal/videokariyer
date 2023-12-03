<?php

namespace Controller\User;

require_once "src/Model/User.php";
require_once "src/Model/UserProfile.php";

use Model\Users;
use Model\UserProfiles\UserProfile;
class User
{
    private $users;


    public function __construct()
    {
        $this->users = new Users\User();
        $this->userprofiles = new UserProfile();
    }
   public function register()
    {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        $district = $_POST["district"] ?? "";
        $city = $_POST["city"] ?? "";
        $addres = $_POST["addres"] ?? "";
        $web = $_POST["web"] ?? "";
        $link = $_POST["link"] ?? "";
        $github = $_POST["github"] ?? "";
        $cover = $_POST["cover"] ?? "";

        $userid = $this->users->register($name, $surname, $username , $email, $phone, $password,$role);
        $this->userprofiles->createUserProfile($userid, $district, $city, $addres, $web, $email, $link, $github, $cover);
    }
    public function updatePhoto()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $photoData = $data["photoData"];
        $userId = $data["userId"];
        $this->users->addPhoto($photoData,$userId);
    }

    public function login()
    {
        if($_POST["email"]) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $ok = $this->users->login($email, "", $password);
            if(!$ok) {
                echo json_encode("Email or password is wrong. Please try again.");
            } else {
                echo json_encode($ok);
            }
        } else if($_POST["username"]) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            
            $ok = $this->users->login("", $username, $password);
            if(!$ok) {
                echo json_encode("Username is wrong. Please try again.");
            } else{
                echo json_encode($ok);
            }
        }
    }

    public function getUsers()
    {
        $result = $this->users->getAllUsers();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function getAllQues()
    {
        $result = $this->users->getAllQues();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
    public function getPhoto($userId)
    {
        $result = $this->users->getPhoto($userId);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function getUserByID($id)
    {
        $result = $this->users->getUserByID($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
    public function getUserRolebyID($user_id)
    {
        try {
            $result = $this->users->getUserRolebyID($user_id);
             echo json_encode($result);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getAllCity()
    {
        $result = $this->users->getAllCity();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function getIlcebyID($id)
    {
        $result = $this->users->getIlcebyID($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function addVideo()
    {
        $user_id = $_POST["user_id"];
        $question_id = $_POST["question_id"];
        $video = $_POST["video"];
        $this->users->addVideo($user_id, $question_id, $video);
    }
    public function getVideoById($video_id)
    {
        $result = $this->users->getVideoWithQuestion($video_id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
    public function deleteVideoWithQuestion($user_id,$question_id)
    {
        // Bu işlevi kullanarak videoyu silme işlemini gerçekleştirebilirsiniz
        $result = $this->users->deleteVideoWithQuestion($user_id, $question_id);

        if ($result) {
            // Silme işlemi başarılı oldu
            echo "Video başarıyla silindi.";
        } else {
            // Silme işlemi başarısız oldu
            echo "Video silinemedi.";
        }
    }
    public function getUserInfo($id)
    {
        $result = $this->users->getUserInfo($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function addInfo()
    {
        $user_id = $_POST["user_id"];
        $userIlce = $_POST["userIlce"];
        $userCity = $_POST["userCity"];
        $userAddres = $_POST["userAddres"];
        $userWeb = $_POST["userWeb"]; 
        $userMail = $_POST["userMail"];
        $userLink = $_POST["userLink"];
        $userGithub = $_POST["github"];
        $userCover = $_POST["userCover"];;
        $this->userprofiles->createUserProfile($user_id,$userIlce,$userCity,$userAddres,$userWeb,$userMail,$userLink,$userGithub,$userCover);
    }
    public function updateUserProfile() {
        $got = file_get_contents('php://input');


        $data = json_decode($got, true);


        $user_id = $data["user_id"] ?? "";
        $userIlce = $data["district"] ?? "";
        $userCity = $data["city"] ?? "";
        $userAddres = $data["addres"] ?? "";
        $userWeb = $data["web"] ?? "";
        $userMail = $data["email"] ?? "";
        $userLink = $data["link"] ?? "";
        $userGithub = $data["github"] ?? "";
        $userCover = $data["cover"] ?? "";
        // İşte burada updateUserInfo işlevini çağırarak kullanıcı profilini güncelliyoruz.
        $this->users->updateUserInfo($user_id, $userIlce, $userCity, $userAddres, $userWeb, $userMail, $userLink, $userGithub, $userCover);
    }



}