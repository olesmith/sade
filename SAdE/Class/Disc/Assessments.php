<?php

include_once("Class/Disc/Assessments/Tables.php");
include_once("Class/Disc/Assessments/Update.php");
include_once("Class/Disc/Assessments/Read.php");
include_once("Class/Disc/Assessments/Handle.php");


class ClassDiscAssessments extends ClassDiscAssessmentsHandle
{
    //*
    //* Variables of ClassDiscAssessments class:
    //*

    var $Assessments=array();
    var $AssessmentData=array("Number","Name","MaxVal");
    var $AssessmentActions=array
    (
       /* "DaylyAssessments", */
       /* "DaylyMarks", */
       "DaylyMarksPrint"
    );

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscAssessments($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Date");
    }


    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->Actions[ "Delete" ][ "AccessMethod" ]="MayDelete";
    }

    //*
    //* function MayDelete, Parameter list: $assessment
    //*
    //* Genrerates sub horisontal menu for Assessments module.
    //*

    function MayDelete($assessment)
    {
        $res=FALSE;

        $nentries=$this->ApplicationObj->ClassDiscMarksObject->MySqlNEntries
        (
           "",
           array
           (
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc" => $this->ApplicationObj->Disc[ "ID" ],
              "Assessment" => $assessment[ "ID" ],
           )
        );

        if ($nentries==0) { $res=TRUE; }

        return $res;
    }

    //*
    //* function MakeAssessmentsMenu, Parameter list: $disc=array(),$stud=FALSE
    //*
    //* Genrerates sub horisontal menu for Assessments module.
    //*

    function MakeAssessmentsMenu($disc=array(),$stud=FALSE)
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $studarg="";
        if ($stud)
        {
            //$studarg="&Student=".$this->ApplicationObj->Student[ "ID" ];
        }

        foreach ($this->AssessmentActions as $action)
        {
            $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]=
                preg_replace
                (
                   '/#ID/',
                   $this->ApplicationObj->Class[ "ID" ],
                   $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]
                ).
                $studarg;
        }

        return preg_replace
        (
            '/#Student/',
            $this->ApplicationObj->Student[ "ID" ],
            $this->ApplicationObj->ClassesObject->MakeActionMenu
            (
               $this->AssessmentActions,
               "ptablemenu",
               $disc[ "ID" ]
            )
         ).
         $this->BR();
    }



}

?>