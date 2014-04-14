<?php


class StudentsHistoryAbsences extends StudentsHistoryMarks
{
    //*
    //* function AddAbsencesCells, Parameter list: &$row,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsencesCells(&$row,$class,$disc,$student)
    {
        //Calc Absences
        $absenceshash=$this->ApplicationObj->ClassAbsencesObject->CalcStudentDiscAbsences
        (
           $disc,
           $this->ApplicationObj->ClassAbsencesObject->ReadStudentDiscAbsences($class,$disc,$student)
        );

        $this->AddAbsenceCells($row,$disc,$absenceshash);
        $this->AddAbsenceTotalCells($row,$disc,$absenceshash);

        return $absenceshash;
    }
    
    //*
    //* function AddAbsencesTitles, Parameter list: &$titles,$class
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsencesTitles(&$titles,$class)
    {
        $this->AddAbsenceTitles($titles,$class);
        $this->AddAbsenceTotalTitles($titles,$class);
    }
    

}

?>