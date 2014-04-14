<?php


class StudentsHistoryResult extends StudentsHistoryAbsenceTotal
{
    //*
    //* function AddResultCells, Parameter list: &$row,,$disc,$markshash,$absenceshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddResultCells(&$row,$class,$disc,$student,$markshash,$absenceshash)
    {
        $res=$this->ApplicationObj->ClassDiscsObject->CalcFinalResult
        (
           0,
           $class[ "ID" ],
           $disc,
           $student[ "ID" ],
           $markshash,
           $absenceshash
        );
        array_push
        (
           $row,
           $this->ApplicationObj->ClassDiscsObject->PaintStudentResult($res)
        );
    }

    //*
    //* function AddResultTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddResultTitles(&$titles,$disc)
    {
        array_push
        (
           $titles,
           "R"
        );
     }
 
}

?>