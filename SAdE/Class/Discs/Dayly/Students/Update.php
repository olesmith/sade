<?php


class ClassDiscsDaylyStudentsUpdate extends ClassDiscsDaylyStudentsTable
{
    //*
    //* function UpdateDaylyStudents, Parameter list: 
    //*
    //* Handles DaylyStudents table. Incl. update.
    //*

    function UpdateDaylyStudents()
    {
        foreach ($this->ApplicationObj->Students as $student)
        {
            $this->ApplicationObj->ClassObservationsObject->UpdateObservations
            (
               $this->ApplicationObj->Class,
               $student
            );
        }
     }
}

?>