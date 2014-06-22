<?php


class StudentsMatriculateRow extends StudentsMatriculateFields
{
    //*
    //* function MatriculaPeriodRow, Parameter list: $period,$n
    //*
    //* Generates student matriculate entry for $period.
    //* 
    //*

    function MatriculaPeriodRow($period,$n)
    {
        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           $this->ClassStudentSqlTable($period),
           array("Student" => $this->ItemHash[ "StudentHash" ][ "ID" ])
        );

        $class=array("ID" => 0);
        if (!empty($classstudent))
        {
            $class=$this->ApplicationObj->ClassesObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Class" ])
             );
        }


        $row=array($n,$period[ "Name" ]);

        $hasrecords=$this->StudentHasRecords
        (
           $this->ItemHash,
           $this->ApplicationObj->GetPeriodName($period)
        );

        $cell1="";
        $cell2="";
        $cell3="-";
        if ($hasrecords && !empty($classstudent))
        {
            $cell1=$this->ApplicationObj->ClassesObject->ClassName($class);
            $cell2="Sim";

            if (!empty($class[ "ID" ]) && $n==1)
            {
                $this->ItemHash[ "Period" ]=$class[ "Period" ];
                $this->ItemHash[ "Class" ]=$class[ "ID" ];

                //$cell3=$this->EquivalentClassesSelect($period,$class);
                $cell3=$this->ActionEntry("Remanage",$this->ItemHash);
            }
        }
        else
        {
            $cell1=$this->AllClassesSelect($period,$class);
            $cell2="Não";
        }

        array_push($row,$cell1,$cell2,$cell3);

        return $row;
    }
}

?>