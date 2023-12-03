<?php

namespace Controller\UserEducation;

use Model\UserEducations;

require_once "src/Model/UserEducation.php";

class UserEducation
{
    private $education;

    public function __construct()
    {
        $this->education = new UserEducations\UserEducation();
    }

    public function addEducation()
    {
//    $Data = json_decode(file_get_contents('php://input'), true);

        $user_id = $_POST['user_id'];
        $university = $_POST['universities'];
        $degree = $_POST['degree'];
        $department = $_POST['department'];
        $start_year = $_POST['start_year'];
        $start_month = $_POST['start_month'];
        $end_year = $_POST['end_year'];
        $end_month = $_POST['end_month'];
        $this->education->addEducation(
            $user_id,
            $university,
            $degree,
            $department,
            $start_year,
            $start_month,
            $end_year,
            $end_month
        );
    }


    public function getAllUni()
    {
        $uni = $this->education->getAllUni();
        echo json_encode($uni);
    }

    public function getFakulteByUniId($uni_id)
    {
        $education = $this->education->getFakulteByUniId($uni_id);
        echo json_encode($education);
    }

    public function getBolumByFakId($fak_id)
    {
        $education = $this->education->getBolumByFakId($fak_id);
        echo json_encode($education);
    }

    public function getEducationByUserId($user_id)
    {
        $education = $this->education->getEducationByUserId($user_id);
        echo json_encode($education);
    }

    public function getEduByEduID($id)
    {
        $education = $this->education->getEduByEduID($id);
        echo json_encode($education);
    }

    public function getAllDep()
    {
        $dep = $this->education->getAllDep();
        echo json_encode($dep);
    }

    public function getAllFakulte()
    {
        $fak = $this->education->getAllFakulte();
        echo json_encode($fak);
    }

    public function updateEducation($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $university = $data['universities'];
        $degree = $data['degree'];
        $department = $data['department'];
        $start_year = $data['start_year'];
        $start_month = $data['start_month'];
        $end_year = $data['end_year'];
        $end_month = $data['end_month'];

        $result = $this->education->updateEducation(
            $university,
            $degree,
            $department,
            $start_year,
            $start_month,
            $end_year,
            $end_month,
            $id
        );

        if($result) {
            echo "Güncelleme başarılı.";
        } else {
            echo "An error has been occurred";
        }

    }

    public function deleteEducation($id)
    {
        $this->education->deleteEducation($id);
        echo "Silme başarılı.";
    }
}