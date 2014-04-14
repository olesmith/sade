<?php


class ClassDiscMarksRow extends ClassDiscMarksCells
{
    var $ResultNames=array(0 => "-",1 => "RE",2=>"AP");

    //*
    //* function DaylyMarksStudentRow, Parameter list: $edit,$n,$student,$assess,$include
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentRow($edit,$n,$student,$assess,$include)
    {
        $studmarks=$this->ReadDaylyStudent($assess,$student);

        if ($edit==1 && $this->GetPOST("Save")==1)
        {
            $this->UpdateStudentMarks($student,$studmarks);
        }

        $row=$this->DaylyMarksStudentCells($n,$student);

        if (!empty($include[ "Semesters" ]))
        {
            $row=array_merge
            (
               $row,
               $this->DaylyMarksStudentSemestersCells($edit,$student,$assess,$studmarks,$include)
             );
        }

        if ($this->ApplicationObj->Disc[ "NRecoveries" ]>0)
        {
            if (!empty($include[ "SemesterResults" ]))
            {
                $row=array_merge
                (
                   $row,
                   $this->DaylyMarksStudentSemesterResultCells($student,$assess,$studmarks)
                );
            }

            if (!empty($include[ "Recoveries" ]))
            {
                $row=array_merge
                (
                   $row,
                   $this->DaylyMarksStudentRecoveriesCells($edit,$student,$assess,$studmarks,$include)
                );
            }
        }

        if (!empty($include[ "Result" ]))
        {
            $row=array_merge
            (
               $row,
               $this->DaylyMarksStudentFinalResultCells($student,$assess,$studmarks)
            );
        }
 
        return $row;
    }
   

}

?>