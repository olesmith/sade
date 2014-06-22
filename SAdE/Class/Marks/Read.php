<?php

class ClassMarksRead extends ClassMarksImport
{
    //*
    //* function ReadStudentDiscMark, Parameter list: $classid,$discid,$studentid,$assessment,$secedit=1
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscMark($class,$disc,$student,$assessment,$secedit=1)
    {
        $res=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment,$secedit),
           TRUE,
           array("ID","Mark")
        );

        if ($res[ "Mark" ]>10.0)
        {
            $res[ "Mark" ]/=10.0;
        }

        if (empty($res[ "Mark" ])) { return ""; }
        else             { return sprintf("%.1f",$res[ "Mark" ]); }
    }

    //*
    //* function CalcStudentDiscMark, Parameter list: $classid,$discid,$studentid,$assessment
    //*
    //* Clc Student Disc Status from DB.
    //*

    function CalcStudentDiscMark($class,$disc,$student,$assessment)
    {
        $teachermark=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment,1),
           TRUE,
           array("ID","Mark")
        );
        if ($teachermark[ "Mark" ]>10)
        {
            $teachermark[ "Mark" ]/=10.0;
        }
 
        $secretarymark=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment,2),
           TRUE,
           array("ID","Mark")
        );

        if ($secretarymark[ "Mark" ]>10)
        {
            $secretarymark[ "Mark" ]/=10.0;
        }

        if (empty($secretarymark[ "Mark" ]) && empty($teachermark[ "Mark" ]) ) { return ""; }

        $mark=0.0;
        if (!empty($secretarymark))
        {
            $mark+=$secretarymark[ "Mark" ];
        }
        if (!empty($teachermark))
        {
            $mark+=$teachermark[ "Mark" ];
        }

        $mark=$this->Min($mark,10.0);

        return sprintf("%.1f",$mark);
    }

    //*
    //* function ReadStudentDiscSecEdit, Parameter list: $class,$disc,$student,$assessment,$secedit=1
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscSecEdit($class,$disc,$student,$assessment,$secedit=1)
    {
        $res=$this->SelectUniqueHash
        (
           "",
           $this->MarkFieldSqlWhere($class,$disc,$student,$assessment,$secedit),
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
            $marks[ $assessment ]=$this->CalcStudentDiscMark($class,$disc,$student,$assessment);
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