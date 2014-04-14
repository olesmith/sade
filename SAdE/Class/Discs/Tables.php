<?php

include_once("Class/Discs/Tables/Init.php");
include_once("Class/Discs/Tables/Discs.php");
include_once("Class/Discs/Tables/Students.php");
include_once("Class/Discs/Tables/Generate.php");
include_once("Class/Discs/Tables/Display.php");
include_once("Class/Discs/Tables/Info.php");

class ClassDiscsTables extends ClassDiscsTablesInfo
{

    //*
    //* function DiscsNAssessments, Parameter list: 
    //*
    //* Finds overall number of assessments for all discs in $this->ApplicationObj->Discs.
    //*

    function DiscsNAssessments()
    {
        $nassessments=0;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $nassessments=$this->Max($disc[ "NAssessments" ],$nassessments);
        }

        return $nassessments;
    }
}

?>