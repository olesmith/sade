<?php


class StudentsHistoryMarkResult extends StudentsHistoryRecoveries
{
    //*
    //* function AddMarkResultCells, Parameter list: &$row,$disc,$markshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMarkResultCells(&$row,$disc,$markshash)
    {
        if ($markshash[ "NAssessments" ]>0)
        {
            array_push
            (
               $row,
               $markshash[ "MediaFinal" ],
               $this->ApplicationObj->ClassMarksObject->PaintStudentResult($markshash[ "MarkResult" ]),
               "*"
            );
        }
        else
        {
            array_push
            (
               $row,
               $this->MultiCell("",2),
               "*"
            ); 
        }
    }

    //*
    //* function AddMarkResultTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*
    function AddMarkResultTitles(&$titles,$disc)
    {
        array_push
        (
           $titles,
           $this->SUB("M","F"),
           $this->SUB("R","N"),
           "*"
        );
    }
}

?>