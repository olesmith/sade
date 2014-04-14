<?php



class StudentsHistoryAbsenceTotal extends StudentsHistoryAbsence
{

    //*
    //* function AddAbsenceTotalCells, Parameter list: &$row,,$disc,$absenceshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsenceTotalCells(&$row,$disc,$absenceshash)
    {
        if ($absenceshash[ "NAssessments" ]>0)
        {
            array_push
            (
               $row,
               sprintf("%02d",$absenceshash[ "Sum" ]),
               sprintf("%.1f",$absenceshash[ "Percent" ]),
               $this->ApplicationObj->ClassAbsencesObject->PaintStudentResult($absenceshash[ "AbsencesResult" ]),
               "*"
            );
        }
        else
        {
            array_push
            (
               $row,
               $this->MultiCell("",3),
               "*"
            );
        }
    } 

    //*
    //* function AddAbsenceTotalTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsenceTotalTitles(&$titles,$disc)
    {
        array_push
        (
           $titles,
           $this->ApplicationObj->Sigma,
           $this->ApplicationObj->Percent,
           $this->SUB("R","F"),
           "*"
        );
     }
}

?>