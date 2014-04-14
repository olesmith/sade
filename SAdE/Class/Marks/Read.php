<?php

class ClassMarksRead extends ClassMarksImport
{
    //*
    //* function ReadStudentDiscMark, Parameter list: $classid,$discid,$studentid,$assessment
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscMark($class,$disc,$student,$assessment)
    {
        $res=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment),
           TRUE,
           array("ID","Mark")
        );

        if ($res[ "Mark" ]>10)
        {
            $res[ "Mark" ]/=10.0;
        }

        if (empty($res)) { return ""; }
        else             { return $res[ "Mark" ]; }
    }

    //*
    //* function ReadStudentDiscSecEdit, Parameter list: $classid,$discid,$studentid,$assessment
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscSecEdit($class,$disc,$student,$assessment)
    {
        $res=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment),
           TRUE,
           array("ID","SecEdit")
        );

        if (empty($res)) { return 2; }
        else             { return $res[ "SecEdit" ]; }
    }

    //*
    //* function ReadStudentDiscMarks, Parameter list: $classid,$disc,$studentid
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscMarks($class,$disc,$student)
    {
        $marks=array();
        for ($assessment=1;$assessment<=$disc[ "NAssessments" ]+$disc[ "NRecoveries" ];$assessment++)
        {
            $marks[ $assessment ]=$this->ReadStudentDiscMark($class,$disc,$student,$assessment);
        }

        return $marks;
    }


    //*
    //* function ReadAndCalcStudentDiscMarks, Parameter list: $classid,$disc,$studentid
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadAndCalcStudentDiscMarks($class,$disc,$student)
    {
        return $this->CalcStudentDiscMarks
        (
           $disc,
           $this->ReadStudentDiscMarks($class,$disc,$student)
        );
    }



    //*
    //* function DiscMarkAverage, Parameter list: $class,$disc,$assessment
    //*
    //* Fetches average from DB.
    //*

    function DiscMarkAverage($class,$disc,$assessment)
    {
        return $this->RowAverage
        (
           "",
           array
           (
              "Class" => $class[ "ID" ],
              "ClassDisc" => $disc[ "ID" ],
              "Assessment" => $assessment,
           ),
           "Mark"
        );
    }
}

?>