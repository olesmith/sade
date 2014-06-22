<?php

include_once("Class/Discs/StatusTable/CGI.php");
include_once("Class/Discs/StatusTable/Cells.php");
include_once("Class/Discs/StatusTable/SearchRows.php");
include_once("Class/Discs/StatusTable/Trimester.php");
include_once("Class/Discs/StatusTable/Trimesters.php");
include_once("Class/Discs/StatusTable/Row.php");
include_once("Class/Discs/StatusTable/Titles.php");

class ClassDiscsStatusTable extends ClassDiscsStatusTableTitles
{
    var $DiscStatusData=array("Class","GradeDisc","Teacher",);
    var $DiscStatusActions=array("Edit","DiscAbsences","DiscMarks","DiscTotals","Dayly",);
    var $DiscStatusTrimesterData=array
    (
       "DayliesLimit" => array
       (
          "Checked" => TRUE,
       ),
       "DayliesClosed" => array
       (
          "Checked" => TRUE,
       ),
       "DayliesClosedTime" => array
       (
          "Checked" => TRUE,
       )
    );
    var $DiscStatusTrimesterExtended=array
    (
       "NLessons" => array
       (
          "Name" => "Aulas",
          "Title" => "Aulas Lançadas",
          "Method" => "TrimesterNWeightsCell",
          "Checked" => TRUE,
       ),
       "NAbsences" => array
       (
          "Name" => "Chamadas",
          "Title" => "Chamadas Lançadas",
          "Method" => "TrimesterAbsencesCell",
          "Checked" => FALSE,
       ),
       "NContents" => array
       (
          "Name" => "Encontros",
          "Title" => "Encontros Lançadas",
          "Method" => "TrimesterNContentsCell",
          "Checked" => FALSE,
       ),
       "NAssessments" => array
       (
          "Name" => "Avaliações",
          "Title" => "Avaliações Lançadas",
          "Method" => "TrimesterNAssessmentsCell",
          "Checked" => TRUE,
       ),
       "NMarks" => array
       (
          "Name" => "Notas",
          "Title" => "Notas Lançadas",
          "Method" => "TrimesterNMarksCell",
          "Checked" => FALSE,
       ),
    );


    //*
    //* function DiscsStatusTable, Parameter list: $edit
    //*
    //* Creates Discs status table.
    //* 
    //*

    function DiscsStatusTable($edit)
    {
        $table=$this->DiscsStatusTableTitles();

        $n=1;
        foreach ($this->ItemHashes as $disc)
        {
            $this->ApplicationObj->ClassDiscsObject->UpdateDisc2TeacherTable($disc);
            $table=array_merge
            (
               $table,
               $this->DiscsStatusTableRows($n++,$disc)
            );
        }

        return array
        (
           $this->Html_Table
           (
              "",
              $table,
              array("BORDER" => 1),
              array(),
              array(),
              FALSE,FALSE
           )
        );
    }
}

?>