<?php


class StudentsHistoryClassSelect extends StudentsHistoryTable
{
    //*
    //* function PeriodClassSelectName, Parameter list: $gradeentry
    //*
    //* Generates select field with according classes.
    //* 
    //*

    function PeriodClassSelectName($gradeentry)
    {
        return "Class_".$gradeentry[ "Period" ][ "ID" ];
    }

    //*
    //* function MakePeriodClassSelect, Parameter list: $edit,&$row,$student,$gradeentry,$value=0
    //*
    //* Generates select field with according classes.
    //* 
    //*

    function MakePeriodClassSelect($edit,&$row,$student,$gradeentry,$value=0)
    {
        if (empty($gradeentry[ "Period" ]) || empty($gradeentry[ "Grade" ]) || empty($gradeentry[ "GradePeriod" ])) { return; }

        $classes=$this->ApplicationObj->ClassesObject->SelectHashesFromTable
        (
           "",
           array
           (
              "Period"      => $gradeentry[ "Period" ][ "ID" ],
              "Grade"       => $gradeentry[ "Grade" ][ "ID" ],
              "GradePeriod" => $gradeentry[ "GradePeriod" ][ "ID" ],
           )
        );

        $ids=array(0);
        $names=array("");
        $rvalue="";
        foreach ($classes as $class)
        {
            array_push($ids,$class[ "ID" ]);
            $name=
                $this->ApplicationObj->ClassesObject->ClassName($class).", ".
                $this->ApplicationObj->GetPeriodName($gradeentry[ "Period" ]);

            array_push($names,$name);

            if ($class[ "ID" ]==$value) { $rvalue=$name; }
        }

        $field="";
        if ($edit==1)
        {
            $field=$this->MakeSelectField
            (
               $this->PeriodClassSelectName($gradeentry),
               $ids,
               $names,
               $value
            );
        }
        elseif ($value>0)
        {
            $field=$rvalue;
        }

        if (count($classes)>0)
        {
            array_push($row,$field);
        }
        else
        {
            array_push($row,"Nenhuma Turma Disp.");
        }
    }
}

?>