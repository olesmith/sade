<?php

class ClassDiscsDaylyStudentsTable extends ClassDiscsCGI
{

    //*
    //* function DaylyStudentsTable, Parameter list: $edit=0,$tedit=0
    //*
    //* Generates DaylyStudents table.
    //*

    function DaylyStudentsTable($edit=0,$tedit=0)
    {
        $titles=array_merge
        (
           array("No.","Dados"),
           $this->ApplicationObj->StudentsObject->GetDataTitles($this->StudentData)
        );
        array_push($titles,"Observações");

        $table=array($this->B($titles));

        $n=1;
        foreach ($this->ApplicationObj->Students as $student)
        {
            $row=array
            (
               $this->B(sprintf("%02d",$n)),
               $this->ApplicationObj->StudentsObject->ActionEntry("Show",$student[ "StudentHash" ]),
            );

            foreach ($this->StudentData as $data)
            {
                array_push
                (
                   $row,
                   $this->ApplicationObj->StudentsObject->MakeShowField($data,$student[ "StudentHash" ])
                );
            }

            array_push
            (
               $row,
               $this->ApplicationObj->ClassObservationsObject->ObservationsHtmlTable
               (
                  $this->ApplicationObj->Class,
                  $student,
                  $edit,
                  $tedit,
                  TRUE //plural, no title: Observations::
               )
            );

            array_push($table,$row);


            $n++;
        }

        return $table;

    }
}

?>