<?php

namespace Model\UserVideos;
use DB\DB;



class UserVideo
{
    private $conn;

    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->connectDB();
    }
    public function createUserVideo($user, $question, $video)
    {
        // Aynı kullanıcı ve soru kombinasyonuna sahip videoyu sorgula
        $query = "SELECT * FROM user_videos WHERE user = ? AND question = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $user, $question);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Eğer böyle bir video bulunursa, güncelleme yap
        if (mysqli_num_rows($result) > 0) {
            $sql = "UPDATE user_videos SET video = ? WHERE user = ? AND question = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $video, $user, $question);
            $ok = mysqli_stmt_execute($stmt);

            if ($ok) {
                echo ("Video başarıyla güncellendi.");
            } else {
                http_response_code(400);
                echo ("Video güncellenirken bir hata oluştu.");
            }
        } else {
            $id = uniqid();
            // Video bulunmuyorsa yeni bir kayıt oluştur
            $sql = "INSERT INTO user_videos (id, user, question, video) VALUES (?,?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $id,$user, $question, $video);
            $ok = mysqli_stmt_execute($stmt);

            if ($ok) {
                echo ("Video başarıyla kaydedildi.");
            } else {
                http_response_code(400);
                echo ("Video kaydedilirken bir hata oluştu.");
            }
        }
    }
}