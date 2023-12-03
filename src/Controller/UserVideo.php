<?php

namespace Controller\UserVideo;
use Model\UserVideos;

require_once "src/Model/UserVideo.php";

class UserVideo
{
    private $userVideo;
    public function __construct()
    {
        $this->userVideo = new UserVideos\UserVideo();
    }

    public function createUserVideo()
    {
        $user = $_POST["user"];
        $question = $_POST["question"];
        $video = $_POST["video"];

        $this->userVideo->createUserVideo($user, $question, $video);
    }
}