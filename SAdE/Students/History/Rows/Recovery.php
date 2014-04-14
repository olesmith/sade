<?php


class StudentsHistoryRecovery extends StudentsHistoryMedia
{
     //*
    //* function AddRecoveryCells, Parameter list: &$row,$n,$disc,$markshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddRecoveryCells(&$row,$n,$disc,$markshash)
    {
        if ($markshash[ "NAssessments" ]>0)
        {
            $mark="";
            if (!empty($markshash[ "Marks" ][ $n ]))
            {
                $mark=$markshash[ "Marks" ][ $n ];
            }

            $media="";
            if (!empty($markshash[ "RecoveryMarks" ][ $n ]))
            {
                $media=$markshash[ "RecoveryMarks" ][ $n ];
            }

            array_push($row,$mark,$media,"*");
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
    //* function AddRecoveryTitles, Parameter list: &$titles,$n,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddRecoveryTitles(&$titles,$n,$disc)
    {
        array_push
        (
           $titles,
           $this->SUB("R",$n),
           $this->SUB("M",$n),
           "*"
        );
    }
}

?>