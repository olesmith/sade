<?php

include_once("Students/History/Rows/Mark.php");
include_once("Students/History/Rows/Media.php");
include_once("Students/History/Rows/Recovery.php");
include_once("Students/History/Rows/Recoveries.php");
include_once("Students/History/Rows/MarkResult.php");
include_once("Students/History/Rows/Absence.php");
include_once("Students/History/Rows/AbsenceTotal.php");
include_once("Students/History/Rows/Result.php");
include_once("Students/History/Rows/Marks.php");
include_once("Students/History/Rows/Absences.php");


class StudentsHistoryRows extends StudentsHistoryAbsences
{
    //*
    //* function StudentDiscHistoryRow, Parameter list: &$row,$n,$student,$disc,$period,$class,$grade,$gradeperiod,$rperiod
    //*
    //* Generates student history html table.
    //* 
    //*

    function StudentDiscHistoryRow(&$row,$n,$student,$disc,$period,$class,$grade,$gradeperiod,$rperiod)
    {
        $this->ApplicationObj->ClassDiscsObject->ReadDiscData($disc);

        $row=array($n,$disc[ "Name" ]);

        //Add all mark related cells
        $markshash=$this->AddMarksCells($row,$class,$disc,$student);

        //Add all absence related cells
        $absenceshash=$this->AddAbsencesCells($row,$class,$disc,$student);

        //Add all result related cells
        $this->AddResultCells($row,$class,$disc,$student,$markshash,$absenceshash);

        return ($markshash[ "NAssessments" ]+$absenceshash[ "NAssessments" ]);
    }
    
    //*
    //* function StudentDiscHistoryTitles, Parameter list: &$table,$student,$period,$class,$grade,$gradeperiod,$rperiod
    //*
    //* Generates second HistoryTableTitles.
    //* 
    //*

    function StudentDiscHistoryTitles(&$table,$student,$period,$class,$grade,$gradeperiod,$rperiod,$classstudent)
    {
        $titles=array("No.","Disciplina");

        $this->AddMarksTitles($titles,$class);
        $this->AddAbsencesTitles($titles,$class);
        $this->AddResultTitles($titles,$class);

        $titles=$this->B($titles);
        array_push($table,$this->B($titles));
    }

    //*
    //* function MakeGradeEntryRow, Parameter list: $edit,$student,$gradeentry
    //*
    //* Creates a row with grade entry.
    //* 
    //*

    function MakeGradeEntryRow($edit,$student,$gradeentry)
    {
        $row=array();
        if (!empty($gradeentry))
        {
            $per="";
            $gradeper="";
            if (!empty($gradeentry[ "Period" ][ "Name" ])) { $per=$gradeentry[ "Period" ][ "Name" ]; }
            if (!empty($gradeentry[ "GradePeriod" ][ "Name" ])) { $gradeper=$gradeentry[ "GradePeriod" ][ "Name" ]; }

            array_push($row,$per,$gradeper);

            if (!empty($gradeentry[ "ClassStudent" ]))
            {
                array_push
                (
                   $row,
                   $gradeentry[ "Class" ][ "Name" ],
                   "Sim"
                );

                if (
                      $this->StudentHasRecords
                      (
                         $student,
                         $this->ApplicationObj->GetPeriodName($gradeentry[ "Period" ])
                      )
                   )
                {
                    array_push($row,"Sim","");
                }
                else
                {
                    array_push($row,"");
                    $this->MakePeriodClassSelect($edit,$row,$student,$gradeentry,$gradeentry[ "Class" ][ "ID" ]);
                }
            }
            else
            {
                array_push($row,"-","Nao","Nao");
                
                $this->MakePeriodClassSelect($edit,$row,$student,$gradeentry);
            }

        }
        else
        {
            //array_push($row,"??");
        }

        return $row;
    }    
}

?>