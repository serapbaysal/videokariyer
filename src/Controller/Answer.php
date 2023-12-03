<?php

namespace Controller\Answer;


use Model\Answers;

require_once "src/Model/Answer.php";
class Answer
{
    private $answers;

    public function __construct()
    {
        $this->answers = new Answers\Answer();
    }

    public function answerQuestion()
    {
        $user = $_POST["user"];
        $question = $_POST["question"];
        $advert = $_POST["advert"];
        $answer = $_POST["answer"];

        $this->answers->createAnswer($user, $question, $advert, $answer);
    }

}