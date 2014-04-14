<?php

class ClassDiscsTablesGenerate extends ClassDiscsTablesStudents
{
    //*
    //* function GenerateTable, Parameter list: 
    //*
    //* Branches: Student or Disc table.
    //*

    function GenerateTable($edit=0,$tedit=0,$form=TRUE)
    {
        if ($this->PerDisc)
        {
            if ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
            {
                $this->PerDisc=FALSE;
            }
        }

        $table=$this->MakeInfoTable($form);

        if ($this->PerDisc)
        {
            if ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Quantitative)
            {
                array_push
                (
                   $table,
                   $this->DiscTable($edit,$tedit)
                );
            }
            else
            {
                $this->PerDisc=FALSE;
                $table=array
                (
                   $this->ApplicationObj->StudentsObject->InfoTable
                   (
                      $this->ApplicationObj->Student
                   ),
                   array($this->HandleQuestionaries($edit,$tedit))
                );
            }
        }
        else
        {
            if ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Quantitative)
            {
                array_push
                (
                   $table,
                   $this->StudentTable($edit,$tedit)
                );
            }
            elseif ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
            {
               array_push
                (
                   $table,
                   array($this->HandleQuestionaries($edit,$tedit))
                );
            }
        }

        return $table;
    }
}

?>