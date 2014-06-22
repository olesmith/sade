<?php

include_once("Class/Absences/Import.php");
include_once("Class/Absences/Update.php");
include_once("Class/Absences/Calc.php");
include_once("Class/Absences/TitleRows.php");
include_once("Class/Absences/Tables.php");


class ClassAbsences extends ClassAbsencesTables
{

    //*
    //* Variables of ClassAbsences class:
    //*

    var $NAssessments=0;
    var $Absences=array();


    //*
    //*
    //* Constructor.
    //*

    function ClassAbsences($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Name");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
        $this->ApplicationObj->ReadClass();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }

        return $item;
    }



    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=TRUE;

        return $res;
    }


    //*
    //* function PaintStudentResult, Parameter list: $res
    //*
    //* Creates green AP, if $res is TRUE, red RE otherwise
    //*

    function PaintStudentResult($res)
    {
        $results=array
        (
           0 => "",
           1 => "RE",
           2 => "AP",
        );

        if (isset($results[ $res ]))
        {
            $res=$results[ $res ];
        }

        return $res;
    }
    
    //*
    //* function ReadStudentDiscAbsences, Parameter list: $class,$disc,$student,$assessment
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscAbsence($class,$disc,$student,$assessment)
    {
        $where=array
        (
           "Class"      => $class[ "ID" ],
           "ClassDisc"  => $disc[ "ID" ],
           "Student"    => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $assessment,
        );

        $res=$this->RowSum("",$where,"Absences");
        if (empty($res)) { return ""; }
        else             { return $res; }
    }

    //*
    //* function ReadStudentDiscSecEdit, Parameter list: $classid,$discid,$studentid,$assessment
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscSecEdit($class,$disc,$student,$assessment)
    {
        $where=array
        (
           "Class"     => $class[ "ID" ],
           "ClassDisc"     => $disc[ "ID" ],
           "Student"    => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $assessment,
        );

        $items=$this->SelectHashesFromTable("",$where,array("ID","SecEdit","Absences"));

        $secedit=2;
        foreach ($items as $item)
        {
            if (!empty($item[ "Absences" ]) && $item[ "SecEdit" ]==1)
            {
                $secedit=1;
                break;
            }
        }

        return $secedit;

        //if (empty($res)) { return 2; }
        //else             { return $res[ "SecEdit" ]; }
    }

    //*
    //* function ReadStudentDiscAbsences, Parameter list: $class,$disc,$student
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscAbsences($class,$disc,$student)
    {
        $absences=array();
        for ($assessment=1;$assessment<=$disc[ "NAssessments" ];$assessment++)
        {
            $absences[ $assessment ]=$this->ReadStudentDiscAbsence($class,$disc,$student,$assessment);
        }

        return $absences;
    }

    //*
    //* function ReadAndCalcStudentDiscAbsences, Parameter list: $class,$disc,$studentid
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadAndCalcStudentDiscAbsences($class,$disc,$student)
    {
        return $this->CalcStudentDiscAbsences
        (
           $disc,
           $this->ReadStudentDiscAbsences($class,$disc,$student)
        );
    }


}

?>