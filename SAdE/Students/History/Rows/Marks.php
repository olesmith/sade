<?php


class StudentsHistoryMarks extends StudentsHistoryResult
{
    //*
    //* function AddMarksCells, Parameter list: &$row,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMarksCells(&$row,$class,$disc,$student)
    {
        //Calc Marks
        $markshash=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
        (
           $disc,
           $this->ApplicationObj->ClassMarksObject->ReadStudentDiscMarks($class,$disc,$student)
        );

        $this->AddMarkCells($row,$disc,$markshash);
        $this->AddMediaCells($row,$disc,$markshash);
        $this->AddRecoveriesCells($row,$disc,$markshash);
        $this->AddMarkResultCells($row,$disc,$markshash);

        return $markshash;
    }
    
    //*
    //* function AddMarksTitles, Parameter list: &$titles,$class
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMarksTitles(&$titles,$class)
    {
        $this->AddMarkTitles($titles,$class);
        $this->AddMediaTitles($titles,$class);
        $this->AddRecoveriesTitles($titles,$class);
        $this->AddMarkResultTitles($titles,$class);
    }
    

}

?>