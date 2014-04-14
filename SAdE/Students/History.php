<?php


include_once("Students/History/Read.php");
include_once("Students/History/Rows.php");
include_once("Students/History/Student.php");
include_once("Students/History/Table.php");
include_once("Students/History/ClassSelect.php");
include_once("Students/History/Update.php");

class StudentsHistory extends StudentsHistoryUpdate
{
    var $StudentClasses=NULL;
    var $StudentGrades=NULL;
    var $StudentPeriods=NULL;
    var $SemesterMode=NULL;


     //*
    //* function HtmlHistory, Parameter list: $edit,$student
    //*
    //* Generates student history html table.
    //* 
    //*

    function HtmlHistory($edit,$student)
    {
        $periodsentries=$this->ReadStudentEntries($student);

        $periods=array_keys($periodsentries);

        if (count($periods)==0)
        {
            return $this->H(2,"Nenhuma Entrada Localizada");
        }

        sort($periods);
        $periods=array_reverse($periods);

        return $this->StudentHistoryTable($edit,$student,$periodsentries);        
    }

    //*
    //* function HandleHistory, Parameter list: 
    //*
    //* Generates student history.
    //* 
    //*

    function HandleHistory()
    {
        $this->ItemHash[ "StudentHash" ]= $this->ItemHash;

        print
            $this->H(1,"Historico do(a) Aluno(a)").
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
            $this->H(2,"Historico").
            $this->HtmlHistory(1,$this->ItemHash).
            "";
    }

}

?>