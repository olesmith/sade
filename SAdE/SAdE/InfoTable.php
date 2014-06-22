<?php

class SAdEInfoTable extends SAdEClass
{
    //*
    //* function InfoTable, Parameter list: $item
    //*
    //* Prints an info table, containing nested Info:
    //* 
    //* Unit
    //* School
    //* Period
    //* Class
    //* Disc
    //* Student
    //* Teacher
    //*
    //*
    //*

    function InfoTable()
    {
        $table=array();
        /* if (!empty($this->Unit)) */
        /* { */
        /*     $this->Unit2InfoTable($table); */
        /* } */

        if (!empty($this->School))
        {
            /* $this->School2InfoTable($table); */

            $this->ReadSchoolPeriods();
        }


        if (!empty($this->School))
        {
            if (!empty($this->Period))
            {
                $this->Period2InfoTable($table);
                if (!empty($this->Class))
                {
                    $this->Class2InfoTable($table);

                    $disc=$this->GetGET("Disc");
                    if (!empty($disc))
                    {
                        $this->ClassDiscsObject->ReadDisc(0,FALSE);
                    }

                    $student=$this->GetGET("Student");
                    if (!empty($student))
                    {
                        $this->StudentsObject->ReadStudent(0,FALSE);
                    }
                }
            }
        }


        print 
            $this->H(1,"SAdE2").
            $this->FrameIt
            (
                $this->Html_Table
                (
                   "",
                   $table,
                   array(),
                   array(),
                   array(),
                   FALSE,
                   FALSE
                )
            ).
            "<P>";
    }

    //*
    //* Transfers data read into $this->Unit, into empty $table.
    //*

    function Unit2InfoTable(&$table)
    {
        array_push
        (
           $table,
           array
           (
              $this->B("Unidade:"),
              $this->UnitsObject->MakeShowField("Department",$this->Unit)
           )
        );

    }

    //*
    //* Transfers data read into $this->School, into empty $table.
    //*

    function School2InfoTable(&$table)
    {
        array_push
        (
           $table,
           array(),array(),
           array
           (
              $this->B("Escola:"),
              $this->SchoolsObject->MakeShowField("Name",$this->School)
           )
        );
    }

    //*
    //* Transfers data read into $this->School, into empty $table.
    //*

    function Period2InfoTable(&$table)
    {
        array_push
        (
           $table,
           array
           (
              $this->B("Ano/Semestre:"),
              $this->PeriodsObject->GetPeriodTitle($this->Period)
           )
        );
    }


    //*
    //* Transfers data read into $this->Class, into empty $table.
    //*

    function Class2InfoTable(&$table)
    {
        $this->GradeObject->Grade2InfoTable($table);
        array_push
        (
           $table,
           array(),array(),
           array
           (
              $this->B("Turma:"),
              $this->ClassesObject->MakeShowField("Name",$this->Class)
           )
        );

        if (!empty($this->Teacher))
        {
            array_push
            (
               $table,
               array
               (
                  $this->B("Professor(a) da Turma:"),
                  $this->PeopleObject->MakeShowField("Name",$this->Teacher)
               )
            );
        }
   }
}

?>
