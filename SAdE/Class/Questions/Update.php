<?php

include_once("Class/Questions/Field.php");


class ClassQuestionsUpdate extends ClassQuestionsField
{
    //*
    //* function QuestionSqlWhere, Parameter list: $class,$question,$student,$n
    //*
    //* Returns sql where clause specifying student question.
    //*

    function QuestionSqlWhere($class,$question,$student,$n)
    {
        return array
        (
           "Class" => $class[ "ID" ],
           "Question" => $question[ "ID" ],
           "Assessment" => $n,
           "Student" => $student[ "StudentHash" ][ "ID" ],
        );
    }


    //*
    //* function UpdateQuestionField, Parameter list: $class,$question,$student,$n
    //*
    //* Updates and creates Questions input field.
    //*

    function UpdateQuestionField($class,$question,$student,$n)
    {
        $item=$this->ReadQuestion($class,$question,$student,$n);
        $value=$item[ "Value" ];

        $newvalue=$this->GetPOST
        (
           $this->QuestionCGIField($class,$question,$student,$n)
        );

        if ($newvalue=="") { $newvalue=NULL; }

        if ($newvalue!=$value)
        {
            $value=preg_replace('/[^\d,\.]/',"",$newvalue);
            $value=preg_replace('/,/',".",$value);

            $where=$this->QuestionSqlWhere($class,$question,$student,$n);

            $item=$where;
            $item[ "Value" ]=$value;
            //$item[ "Teacher" ]=$teacherid;

            $this->AddOrUpdate("",$where,$item);
        }
    }


    //*
    //* function UpdateQuestionFields, Parameter list: $class,$question,$student
    //*
    //* Updates questions fields.
    //*

    function UpdateQuestionFields($class,$question,$student)
    {
        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            $this->UpdateQuestionField($class,$question,$student,$n);
        }
    }

    //*
    //* function UpdateQuestionary, Parameter list: $class,$questions,$student
    //*
    //* Updates list of questions fields.
    //*

    function UpdateQuestionary($class,$questions,$student)
    {
        foreach ($questions as $question)
        {
            $this->UpdateQuestionFields($class,$question,$student);
        }
    }

    //*
    //* function UpdateQuestionaries, Parameter list: $class,$student,$questionaries
    //*
    //* Updates list of questions fields.
    //*

    function UpdateQuestionaries($class,$student)
    {
        $this->ReadQuestionaries($class);
        foreach ($this->Questions as $questionary)
        {
            $this->UpdateQuestionary($class,$questionary[ "Questions" ],$student);
        }
    }

}

?>