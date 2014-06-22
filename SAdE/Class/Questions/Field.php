<?php


class ClassQuestionsField extends ClassQuestionsLegend
{
    //*
    //* function QuestionCGIField, Parameter list: $class,$question,$student,$n
    //*
    //* Returns name of CGI field, associated with  
    //*

    function QuestionCGIField($class,$question,$student,$n)
    {
        return join
        (
           "_",
           array
           (
              "Question",
              $question[ "ID" ],
              $student[ "StudentHash" ][ "ID" ],
              $n
           )
        );
    }


    //*
    //* function QuestionField, Parameter list: $class,$student,$question,$edit=0,$tedit=0
    //*
    //* Creates the row with question, one for each 
    //*

    function QuestionField($class,$question,$student,$n,$edit=0,$tedit=0)
    {        
        $item=$this->ReadQuestion($class,$question,$student,$n);
        return preg_replace
        (
           '/NAME=[\'"](\S+)[\'"]/',
           "NAME='".
           $this->QuestionCGIField($class,$question,$student,$n).
           "' TABINDEX='".$n."'",
           $this->MakeField($edit,$item,"Value",TRUE)
        );
    }

    //*
    //* function QuestionsFieldCGIRegex, Parameter list: $class,$question,$student
    //*
    //* Returns CGI key name of mark field.
    //*

    function QuestionsFieldCGIRegex($class,$question,$student)
    {
        return join
        (
           "_",
           array
           (
              "Question",
              $class[ "ID" ],
              $question[ "ID" ],
              $student[ "StudentHash" ][ "ID" ]
           )
        );
    }
}

?>