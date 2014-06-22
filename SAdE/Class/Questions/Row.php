<?php


class ClassQuestionsRow extends ClassQuestionsTables
{
    //*
    //* function QuestionTitleRow, Parameter list: $class,$student,$questionaire
    //*
    //* Creates the row with question, one for each 
    //*

    function QuestionTitleRow($class,$student,$questionaire)
    {        
        $row=array
        (
           //$this->B("No."),
           $this->MultiCell
           (
              sprintf("%02d",$questionaire[ "Number" ])
              .": ".
              $questionaire[ "Name" ],
              $class[ "NAssessments" ]+3
           ),
        );

        //if (!$this->LatexMode()) { array_push($row,""); }


        return $row;
    }


    //*
    //* function QuestionRow, Parameter list: $class,$student,$question,$edit=0,$tedit=0
    //*
    //* Creates the row with question, one for each 
    //*

    function QuestionRow($class,$student,$question,$edit=0,$tedit=0)
    {        
        $row=array
        (
           $this->B(sprintf("%02d",$question[ "Number" ]).":"),
           $question[ "Name" ],
        );

        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push
            (
               $row,
               $this->QuestionField($class,$question,$student,$n,$edit,$tedit)
            );
        }

        array_push($row,"-");

        return $row;
     }
}

?>