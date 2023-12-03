<?php
namespace Model\Users;

use DB\DB;
use Firebase\JWT\JWT;

use Dotenv\Dotenv;

mysqli_report(MYSQLI_REPORT_OFF);

$dotenv = Dotenv::createUnsafeImmutable("./src");
$dotenv->load();

class User
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
        header('Content-Type: application/json; charset=utf-8');
    }

public function register($name, $surname, $username, $email, $tel, $password, $role_id)
{
    $id = uniqid();
    $sql = "
        INSERT INTO users(id,name, surname, username, email, phone, password, created_at, updated_at)
        VALUES(?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ";

    $stmt = mysqli_prepare($this->conn, $sql);
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);

    mysqli_stmt_bind_param($stmt, "sssssss", $id,$name, $surname, $username, $email, $tel, $hashedPass);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        // UserRole tablosuna ekleme yapma işlemi (örnek olarak)
        $this->addUserRole($id, $role_id);
       if($role_id==2){
        $this->addCompanies($id, $name);
       }

       return $id;
    } else {
        http_response_code(400);
        echo "Bu bilgilere sahip kullanıcı bulunmaktadır. Lütfen farklı kimlik bilgileriyle giriş yapın veya kaydolun.";
    }
}

public function addUserRole($userId, $role_id)
{
    $id = uniqid();
    $sql = "
        INSERT INTO user_roles(id, user, role)
        VALUES(?, ?, ?)
    ";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $id,$userId, $role_id);
    mysqli_stmt_execute($stmt);
}
public function addCompanies($userId, $name)
{
    $id = uniqid();
    $sql = "INSERT INTO companies(id, user, name) VALUES (?,?, ?)";
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $id,$userId, $name); // "is" -> integer, string
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

    
    public function login(string $email, $username, $password)
{
    $data = [];
    if ($email != "") {
        $userSql = "
            SELECT CONCAT(name , ' ' , surname) AS fullname, email, password, phone, id,userPhoto
            FROM users
            WHERE email = '$email'
        ";
        $userResult = mysqli_query($this->conn, $userSql);

        $rows = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

        if (empty($rows)) {
            return "User not found";
        }

        foreach ($rows as $row) {
            $payload = [
                $email, $password
            ];

            $sql = "
                UPDATE users
                SET access_token = ?
                WHERE email = ?
            ";

            $result = $this->conn->prepare($sql);

            $access_token = JWT::encode($payload, $_ENV["JWT_SECRET"], "HS256");
            $verified = password_verify($password, $row["password"]);

            if ($verified) {
                $result->bind_param("ss", $access_token, $email);
                $result->execute();
            } else {
                return $verified;
            }

            if (!$result->error) {
                $data[] = $row;
            }
        }

        return $data;
    } else if ($username != "") {
        $userSql = "
            SELECT id, CONCAT(name , ' ' , surname) AS fullname, email, password, phone
            FROM users
            WHERE username = '$username'
        ";
        $userResult = mysqli_query($this->conn, $userSql);

        $rows = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

        if (empty($rows)) {
            return "User not found"; // Kullanıcı bulunamadığında hata döndür
        }

        foreach ($rows as $row) {
            $payload = [
                $username, $password
            ];

            $access_token = JWT::encode($payload, $_ENV["JWT_SECRET"], "HS256");

            $sql = "
                UPDATE users
                SET access_token = ?
                WHERE username = ?
            ";

            $result = $this->conn->prepare($sql);
            $verified = password_verify($password, $row["password"]);
            
            if ($verified) {
                $result->bind_param("ss", $access_token, $username);
                $result->execute();

                $data[] = $row;
            } else {
                return $verified;
            }

        }

        return $data;
    }
}


    public function getAllUsers()
    {
        $sql = "
                SELECT *
                FROM users
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "name" => $row["name"] . " " . $row["surname"],
                "email" => $row["email"],
                "username" => $row["username"],
                "username2" => $row["username"]

            ];

        }

        return $data;
    }

    public function getAllVideoQues()
    {
        $sql = "
                SELECT *
                FROM video_questions
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "id" => $row["id"],
                "question" => $row["question"],
            ];

        }

        return $data;
    }

    public function getUserByID($id)
    {
        $sql = "
           SELECT *
           FROM users
           WHERE id= '$id'
        ";
        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_row($result);

        return [
            // name, surname
            "name" => $row[1] . " " . $row[2],
            "email" => $row[3],
            "username" => $row[4]
        ];
    }
    public function getUserRolebyID($id){
        $sql = "SELECT CONCAT(users.name , ' ', users.surname) AS fullname, roles.name AS role 
                FROM user_roles
                INNER JOIN users ON users.id = user_roles.user
                INNER JOIN roles ON roles.id = user_roles.role
                WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $experienceData = $result->fetch_all(MYSQLI_ASSOC);
        return $experienceData;
    }

    public function addPhoto($photoData,$userId) {
   
        $query = "UPDATE users SET userPhoto = ? WHERE id =?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss",$photoData ,$userId);

        if ($stmt->execute()) {
            echo "Fotoğraf başarıyla oluşturuldu.";
        } else {
            echo "İşlem Başarısız.";
        }
    }
    public function getPhoto($userId) {
   
        $query = "SELECT userPhoto FROM users WHERE users.id =?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $experienceData = $result->fetch_all(MYSQLI_ASSOC);
        return $experienceData;
    }

    public function getAllCity()
    {
        $sql = "
                SELECT *
                FROM cities
            ";
        $result = mysqli_query($this->conn, $sql);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


        foreach ($rows as $row) {
            $data[] = [
                "id" => $row["id"],
                "city" => $row["name"],
            ];

        }
        return $data;
    }
    public function getIlcebyID($id){
        $sql = "SELECT * FROM districts WHERE city = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $experienceData = $result->fetch_all(MYSQLI_ASSOC);
        return $experienceData;
    }

    public function getUserInfo($id){
        $sql = "SELECT u.*, up.* 
                FROM users u 
                INNER JOIN user_profiles up ON u.id = up.user 
                WHERE u.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userInfo = $result->fetch_assoc();
        return $userInfo;
    }

    public function createUserInfo($userId,$userIlce,$userCity,$userAddres,$userWeb,$userMail,$userLink,$userGithub,$userCover){
        $id = uniqid();
        $sql = "INSERT INTO user_profiles 
                        (
                         id,
                         user,
                         district, 
                         city, 
                         addres, 
                         web,
                         email,
                         link,
                         github,
                         cover
                         ) VALUES (?,?, ?, ?, ?,?,?,?,?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssssss",$id,$userId,$userIlce,$userCity,$userAddres,$userWeb,$userMail,$userLink,$userGithub,$userCover);
        if ($stmt->execute()) {
            echo " başarıyla oluşturuldu.";
        } else {
            echo "İşlem Başarısız.";
        }
    }

    public function updateUserInfo($user_id, $userIlce, $userCity, $userAddres, $userWeb, $userMail, $userLink, $userGithub, $userCover) {
        // Önce kullanıcının varlığını kontrol edin (kullanıcı profili kaydı var mı?).
        $sql = "UPDATE user_profiles SET 
                         district=?, 
                         city=?, 
                         addres=?, 
                         web=?, 
                         email=?, 
                         link=?, 
                         github=?, 
                         cover=? WHERE user=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssss", $userIlce, $userCity, $userAddres, $userWeb, $userMail, $userLink, $userGithub, $userCover, $user_id);
        $result = $stmt->execute();

        if($result) {
            echo "The record has been updated successfully";
        } else {
            echo "An error has been occurred";
        }
        $stmt->close();
        
    }


    public function addVideo($user_id,$question_id,$video)
    {
        // Aynı kullanıcı ve soru kombinasyonuna sahip videoyu sorgula
        $query = "SELECT * FROM user_videos WHERE user = ? AND question = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $user_id, $question_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        // Eğer böyle bir video bulunursa, güncelleme yap
        if (mysqli_num_rows($result) > 0) {
            $sql = "UPDATE user_videos SET video = ? WHERE user = ? AND question = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $video, $user_id, $question_id);
            $ok = mysqli_stmt_execute($stmt);
    
            if ($ok) {
                echo "Video başarıyla güncellendi.";
            } else {
                http_response_code(500);
                echo "Video güncellenirken bir hata oluştu.";
            }
        } else {
            $id = uniqid();
            // Video bulunmuyorsa yeni bir kayıt oluştur
            $sql = "INSERT INTO user_videos (id, user, question, video) VALUES (?,?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $id,$user_id, $question_id, $video);
            $ok = mysqli_stmt_execute($stmt);
    
            if ($ok) {
                echo "Video başarıyla kaydedildi.";
            } else {
                http_response_code(500);
                echo "Video kaydedilirken bir hata oluştu.";
            }
        }
    }

    public function getVideoWithQuestion($user_id)
{
    $query = "SELECT *
              FROM user_videos uv
              INNER JOIN video_questions q ON uv.question = q.id
              WHERE uv.user = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    // TODO: hata gelebilir question_id yok
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "video" => $row["video"],
            "question_id" => $row["question_id"],
            "question" => $row["question"],
            "user" => $row["user"],
        ];
    }

    return $data;
}

public function deleteVideoWithQuestion($user_id, $question_id)
{
    // Önce veritabanından video bilgilerini alın
    $query = "SELECT video FROM user_videos WHERE user = ? AND question = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ss", $user_id, $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Belirtilen kullanıcı ve soru ID'sine sahip video bulunamadı.
        return false;
    }

    // Veritabanından videoyu silme işlemi
    $deleteQuery = "DELETE FROM user_videos WHERE user = ? AND question = ?";
    $deleteStmt = $this->conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ss", $user_id, $question_id);

    if ($deleteStmt->execute()) {
        // Silme işlemi başarılı oldu
        $deletedVideo = $result->fetch_assoc()["video"];
        // Silinen videoyu dosya sisteminden de silebilirsiniz, eğer gerekiyorsa.
        unlink("wwwroot/assets/images/users/videos/".$deletedVideo);
        return true;
    } else {
        // Silme işlemi başarısız oldu
        return false;
    }
}



}