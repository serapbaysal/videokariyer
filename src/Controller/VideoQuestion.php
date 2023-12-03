<?php

namespace Controller\VideoQuestion;
use Model\VideoQuestions;

require_once "src/Model/VideoQuestion.php";
class VideoQuestion
{
    private $videoQuestion;
    public function __construct()
    {
        $this->videoQuestion = new VideoQuestions\VideoQuestion();
    }

    public function createVideoQuestion()
    {
        $question = $_POST["question"];

        $this->videoQuestion->createVideoQuestion($question);
    }
}