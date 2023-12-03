<?php

namespace Controller\Advert;

use Model\Adverts;

require_once "src/Model/Advert.php";
class Advert
{
    private $adverts;

    public function __construct()
    {
        $this->adverts = new Adverts\Advert();
    }

    public function getAdverts()
    {
        $result = $this->adverts->getAdverts();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
    public function getAdvertsById($advert_id)
    {
        try {
            $experienceData = $this->adverts->getAdvertsByID($advert_id);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getAdvertsByCompany($userId)
    {
        try {
            $experienceData = $this->adverts->getAdvertsBCompany($userId);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function deleteAdvert($id)
    {
        try {
            $this->adverts->deleteAdvert($id);
            return "İş İlanı başarıyla silindi.";
        } catch (\Exception $e) {
            return "Deneyim silinirken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function createAdvert()
    {

            $title = $_POST["title"];
            $type = $_POST["type"];
            $company = $_POST["company"];
            $descHtml = $_POST["descHtml"];
            $description = $_POST["description"];
            $department = $_POST["department"];
            $calismaturu = $_POST["worktype"];
            $deneyimseviyesi = $_POST["experience"];
            $logoUrl = $_POST["logourl"];

            if($this->adverts->createAdvert($title,$type,$company, $description,  $department, $calismaturu, $deneyimseviyesi,$descHtml, $logoUrl))
                echo "Ekleme Başarılı";
            else {
                echo "Bir hata oluştu, ekleme gerçekleştirilemedi.";
            }
    }
    public function applyAdvert()
    {
        $user_id = $_POST["user_id"];
        $advert = $_POST["advert"];
        $this->adverts->applyAdvert($user_id, $advert);
    }

    public function getApply($user_id,$advert)
    {
        try {
            $experienceData = $this->adverts->getApply($user_id,$advert);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }
    public function getApplyAdvert($advert)
    {
        try {
            $experienceData = $this->adverts->getApllyAdvert($advert);
            return json_encode($experienceData);
        } catch (\Exception $e) {
            return "Deneyimler alınırken bir hata oluştu: " . $e->getMessage();
        }
    }

    public function insertVideo()
    {
        $user_id = $_POST["user_id"];
        $advert = $_POST["advert"];
        $video = $_POST["video"];
        $question = $_POST["question"];
        $this->adverts->insertVideo($user_id, $advert,$video,$question);
    }


}