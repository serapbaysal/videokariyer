<?php

namespace Controller\Question;

require_once "src/Model/Question.php";

use Model\Questions;


class Question
{
    private $questions;


    public function __construct()
    {
        $this->questions= new Questions\Question();
    }
    public function createQuestion()
    {
        $question = $_POST["question"];


        $this->questions->createQuestion($question);
    }
}