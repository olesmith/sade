<?php

include_once("Students/Remanage/Selects.php");
include_once("Students/Remanage/Trimester.php");
include_once("Students/Remanage/Trimesters.php");
include_once("Students/Remanage/Start.php");
include_once("Students/Remanage/Tables.php");
include_once("Students/Remanage/Forms.php");


class StudentsRemanage extends StudentsRemanageForms
{
    var $DestinationClass=array();
    var $DestinationSchool=array();
   

    //*
    //* function HandleRemanage, Parameter list: 
    //*
    //* Generates info and form for deciding internal studente remanagement.
    //* 
    //*

    function HandleRemanage()
    {
        $edit=1;

        $this->ApplicationObj->ClassesObject->SchoolAndPeriod2SqlTables();

        $this->ItemHash[ "StudentHash" ]=$this->ItemHash;

        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           "",
           array("Student" => $this->ItemHash[ "ID" ])
        );

        //$classstudent=$this->ReadStudentHistoryEntries($this->ItemHash);
        $classstudent[ "StudentHash" ]=$this->ItemHash[ "StudentHash" ];
        $this->ApplicationObj->Student=$classstudent;

        print
            $this->H(1,"Remanejar Aluno(a)").
            $this->HtmlTable
            (
               "",
               $this->ItemTable
               (
                  0,
                  array(),
                  FALSE,
                  array("Name","Matricula","MatriculaDate","Status","StatusDate1","StatusDate2")
               )
            ).
            $this->StudentRemanageTable().
            $this->RemanageDecisionForm($classstudent).
            "";
    }
}

?>