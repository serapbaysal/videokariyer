<?php

namespace Model\Adverts;
use Model\UserSkills\UserSkill;
use Model\UserEducations\UserEducation;
use Model\UserExperiences\UserExperience;
use Model\Users\User;
use DB\DB;

class Advert
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();

        header('Content-Type: application/json; charset=utf-8');

    }

    public function getAdvertsByID($advID)
    {
        $sql = "SELECT 
    adverts.id,
    companies.name AS company,
    adverts.description AS description,
    departments.name AS department,
    adverts.worktype,
    adverts.experience,
    adverts.created_at,
    adverts.type,
    adverts.title
FROM adverts
INNER JOIN companies ON adverts.company = companies.id
INNER JOIN departments ON adverts.department = departments.id
WHERE adverts.id = '$advID'";
    
        $result = mysqli_query($this->conn, $sql);
    
        if ($result === false) {
            // Sorguda bir hata olduğunu belirlemek için hata izini alın
            echo "Sorguda bir hata oluştu: " . mysqli_error($this->conn);
            // Hata durumunda diğer işlemleri durdurabilir veya uygun bir şekilde ele alabilirsiniz.
            return [];
        }

        $data = [];
        
        // Sorgu başarılıysa verileri çekin
        while ($row = mysqli_fetch_assoc($result)) {
            $advertID = $row['id'];
            $data[] = [
                "id" => $row["id"],
                "company" => $row["company"],
                "department" => $row["department"],
                "description" => $row["description"],
                "created_at" => $row["created_at"],
                "levels" => $row["experience"],
                "type" => $row["type"],
                "worktype" => $row["worktype"],
                "title" => $row["title"],
                "descHtml" => $row["descHtml"],
                "logo" => $row["userlogo"],
                'skills'  => $this->getSkillsByAdvertId($advertID),
                'softSkills'  => $this->getSoftsByAdvertId($advertID),
                'langs'  => $this->getLangsByAdvertId($advertID),
                'ques'  => $this->getQuestionByAdvertId($advertID),
            ];
        }
    
        return $data;
    }

public function createAdvert($title,$type,$company, $description, $department, $worktype, $experience, $descHtml, $logourl)
{

    $stmt = $this->conn->prepare("
                    INSERT INTO adverts 
                        (
                         id,
                         title,
                         type, 
                         company, 
                         description,  
                         department, 
                         created_at, 
                         worktype, 
                         experience,
                         deschtml,
                         logourl
                         ) 
                    VALUES (?,?, ?, ?, ?, ?, NOW(),?,?, ?, ?)");
    if (!$stmt) {
        echo "Hazırlama hatası: " . $this->conn->error;
        return false;
    }

    $advertid = uniqid();

    $stmt->bind_param("ssisssssss", $advertid,$title,$type,$company, $description,  $department, $worktype, $experience,$descHtml, $logourl);
    if (!$stmt->execute()) {
        echo "Çalıştırma hatası: " . $stmt->error;
        $stmt->close();
        return false;
    }


    $stmt->close();
    return true;
}

public function getAdvertsBCompany($userID)
    {
        // hata gelirse 'userId' olacak şekilde güncelle
        $sql = "SELECT adverts.id, companies.name AS company, adverts.description AS description, 
        departments.name AS department, adverts.worktype, adverts.experience, adverts.created_at, adverts.type, adverts.title,adverts.descHtml
        FROM adverts 
        INNER JOIN companies ON adverts.company = companies.id 
        INNER JOIN departments ON adverts.department = departments.id 
        WHERE adverts.company = companies.id;";
    
        $result = mysqli_query($this->conn, $sql);
    
        if ($result === false) {
            // Sorguda bir hata olduğunu belirlemek için hata izini alın
            echo "Sorguda bir hata oluştu: " . mysqli_error($this->conn);
            // Hata durumunda diğer işlemleri durdurabilir veya uygun bir şekilde ele alabilirsiniz.
            return [];
        }
    
        $data = [];
    
        // Sorgu başarılıysa verileri çekin
        while ($row = mysqli_fetch_assoc($result)) {
            $advertID = $row['id'];
            $data[] = [
                "id" => $row["id"],
                "company" => $row["company"],
                "department" => $row["department"],
                "description" => $row["description"],
                "created_at" => $row["created_at"],
                "levels" => $row["deneyimseviyesi"],
                "type" => $row["type"],
                "worktype" => $row["worktype"],
                "title" => $row["title"],
                "descHtml" => $row["descHtml"],
                "logo" => $row["user_logo"],
                'skills'  => $this->getSkillsByAdvertId($advertID),
                'softSkilss'  => $this->getSoftsByAdvertId($advertID),
                'langs'  => $this->getLangsByAdvertId($advertID),
                'questions'  => $this->getQuestionByAdvertId($advertID)
            ];
        }
    
        return $data;
    }


public function getAdverts()
{
    $sql = "SELECT adverts.id,companies.name AS company, 
                   adverts.description AS description,  
                   departments.name AS department, 
                   adverts.worktype, 
                   adverts.experience, 
                   adverts.created_at,
                   adverts.type,
                   adverts.title
        FROM adverts
        INNER JOIN companies ON adverts.company = companies.id
        INNER JOIN departments ON adverts.department = departments.id
    ";

    $result = mysqli_query($this->conn, $sql);

    if ($result === false) {
        // Sorguda bir hata olduğunu belirlemek için hata izini alın
        echo "Sorguda bir hata oluştu: " . mysqli_error($this->conn);
        // Hata durumunda diğer işlemleri durdurabilir veya uygun bir şekilde ele alabilirsiniz.
        return [];
    }

    $data = [];
    // Sorgu başarılıysa verileri çekin
    while ($row = mysqli_fetch_assoc($result)) {
        $advertID = $row['id'];
        
        $data[] = [
            "id" => $row["id"],
            "company" => $row["company"],
            "department" => $row["department"],
            "description" => $row["description"],
            "created_at" => $row["created_at"],
            "levels" => $row["experience"],
            "type" => $row["type"],
            "logo" => $row["user_logo"],
            "worktype" => $row["worktype"],
            "descHtml" => $row["descHtml"],
            "title" => $row["title"],
            'skills'  => $this->getSkillsByAdvertId($advertID),
            'softSkils'  => $this->getSoftsByAdvertId($advertID),
            'langs'  => $this->getLangsByAdvertId($advertID),
            'questions'  => $this->getQuestionByAdvertId($advertID)
        ];
    }

    return $data;
}
public function deleteAdvert($id)
    {
        $sql = "DELETE FROM adverts WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }

public function getSkillsByAdvertId($advertID) {
    $query = "SELECT skills.name AS skill 
              FROM advert_skills 
              INNER JOIN skills ON advert_skills.skill = skills.id WHERE advert=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $advertID);
    $stmt->execute();
    $result = $stmt->get_result();

    $skills = array();

    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }

    return $skills;
}
public function getSoftsByAdvertId($advertsID) {
    $query = "SELECT soft_skills.name AS skill 
              FROM advert_soft_skills 
              INNER JOIN soft_skills ON advert_soft_skills.soft_skill = soft_skills.id WHERE advert=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $advertsID);
    $stmt->execute();
    $result = $stmt->get_result();

    $skills = array();

    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }

    return $skills;
}
public function getLangsByAdvertId($advertsID) {
    $query = "SELECT languages.name AS language 
              FROM advert_languages 
              INNER JOIN languages ON advert_languages.language = languages.id WHERE advert=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $advertsID);
    $stmt->execute();
    $result = $stmt->get_result();

    $skills = array();

    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }

    return $skills;
}
public function getQuestionByAdvertId($advertsID) {
    $query = "SELECT aq.* FROM advert_questions aq 
             INNER JOIN adverts ON aq.advert = adverts.id WHERE adverts.id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $advertsID);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = array();

    while ($row = $result->fetch_assoc()) {
       $questions[] = array(
        "question" => $row["question"],
        "id" => $row["id"],
    );
    }

    return $questions;
}
public function applyAdvert($user_id, $advert_id)
{
    $id = uniqid();
    $sql = "
        INSERT INTO appeals(id, user, advert,created_at, updated_at)
        VALUES(?, ?,  ?,NOW(), NOW())
    ";

    $stmt = mysqli_prepare($this->conn, $sql);


    mysqli_stmt_bind_param($stmt, "sss", $id, $user_id, $advert_id);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        echo "Kayıt başarıyla oluşturuldu.";
    } else {
        http_response_code(200);
        echo "Başvuru işlemi başarısız";
    }
}
public function getApply($user_id, $advert_id)
{
    $sql = "
        SELECT *
        FROM appeals
        WHERE user = '" . $user_id ."' AND advert = '" . $advert_id . "'";

    $result = mysqli_query($this->conn, $sql);
    $rows = []; // Boş bir dizi oluştur

    while ($result && $row = mysqli_fetch_assoc($result)) {
        $userExperience = new UserExperience();
        $userEducation = new UserEducation();
        $userSkill = new UserSkill();
        $user = new User();
        $row['adverts'] = $this->getAdvertsByID($advert_id);
        $row['userExperience'] = $userExperience->getExperienceByUserId($user_id);
        $row['userEducation'] = $userEducation->getEducationByUserId($user_id);
        $row['userSkills'] = $userSkill->getSkillByUserId($user_id);
        $row['userSoftSkills'] = $userSkill->getSoftSkillByUserID($user_id);
        $row['userLangs'] = $userSkill->getLaungByUserID($user_id);
        $row['userPhoto'] = $user->getPhoto($user_id);
        $row['userVideo'] = $user->getVideoWithQuestion($user_id);
        $row['companyVideo'] = $this->getVideo($user_id, $advert_id);
        $row['userInfo'] = $user->getUserInfo($user_id);
        $rows[] = $row; // Diziyi doldur
    }

    return $rows;
}

public function getApllyAdvert($advert_id)
{
    $sql = "
        SELECT *
        FROM appeals
        WHERE advert = '" . $advert_id . "'";


    $result = mysqli_query($this->conn, $sql);

    $rows = []; // Boş bir dizi oluştur

    while ($result && $row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $userExperience = new UserExperience();
        $userEducation = new UserEducation();
        $userSkill = new UserSkill();
        $user = new User();
        $row['adverts'] = $this->getAdvertsByID($advert_id);
        $row['userExperience'] = $userExperience->getExperienceByUserId($user_id);
        $row['userEducation'] = $userEducation->getEducationByUserId($user_id);
        $row['userSkills'] = $userSkill->getSkillByUserId($user_id);
        $row['userSoftSkills'] = $userSkill->getSoftSkillByUserID($user_id);
        $row['userLangs'] = $userSkill->getLaungByUserID($user_id);
        $row['userPhoto'] = $user->getPhoto($user_id);
        $row['userInfo'] = $user->getUserInfo($user_id);
        $row['userVideo'] = $user->getVideoWithQuestion($user_id);
        $row['companyVideo'] = $this->getVideo($user_id, $advert_id);
        $rows[] = $row; // Diziyi doldur
    }

    return $rows;

}

public function getVideo($user_id, $adv_id)
{
    $query = "SELECT * FROM answers uv WHERE uv.user = ? and uv.advert=?;";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ss", $user_id,$adv_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "video" => $row["video"],
            "question" => $row["question"],
        ];
    }

    return $data;
}

public function insertVideo($user_id, $adv_id,$videoPath,$question)
{
    $id = uniqid();
    // Önce videoyu veritabanına eklemek için bir sorgu oluşturun
    $query = "INSERT INTO answers (id, user, advert, video,question) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssss", $id,$user_id, $adv_id, $videoPath,$question);

    // Video yolu, video dosyasının sunucuda saklandığı dizini ve dosya adını içermelidir.

    // Şimdi sorguyu çalıştırın
    if ($stmt->execute()) {
        return true; // Video başarıyla eklendi
    } else {
        return false; // Video eklenirken bir hata oluştu
    }
}

}