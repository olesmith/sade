<?php


class StudentsMatriculateFields extends StudentsAdd
{
     //*
    //* function ClassStudentSqlTable, Parameter list: $period
    //*
    //* Return name of class student sqltable.
    //* 
    //*

    function ClassStudentSqlTable($period)
    {
        return 
            $this->ItemHash[ "School" ]."_".
            $this->ApplicationObj->GetPeriodName($period).
            "_ClassStudents";

    }

    //*
    //* function SelectFieldName, Parameter list: $period
    //*
    //* Return name of select field.
    //* 
    //*

    function SelectFieldName($period)
    {
        return "Period_".$period[ "ID" ]."_Class";
    }
    //*
    //* function SelectFieldForceName, Parameter list: $period
    //*
    //* Return name of select field.
    //* 
    //*

    function SelectFieldForceName($period)
    {
        return $this->SelectFieldName($period)."_Force";
    }

    //*
    //* function AllClassesSelect, Parameter list: $period,$classid
    //*
    //* Generate all classes select field.
    //* 
    //*

    function AllClassesSelect($period,$class)
    {
        $classes=$this->ApplicationObj->ClassesObject->SelectHashesFromTable
        (
           "",
           array("Period" => $period[ "ID" ]),
           array("ID","Name","GradePeriod"),
           FALSE,
           "Grade","GradePeriod","Name"
        );

        $ids=array(0);
        $names=array("");
        foreach ($classes as $rclass)
        {
            array_push($ids,$rclass[ "ID" ]);
            array_push($names,$this->ApplicationObj->ClassesObject->ClassName($rclass));
        }

        return $this->MakeSelectField
        (
           $this->SelectFieldName($period),
           $ids,
           $names,
           $class[ "ID" ]
        );
    }

    //*
    //* function EquivalentClassesSelect, Parameter list: $period,$class
    //*
    //* Generate all classes select field.
    //* 
    //*

    function EquivalentClassesSelect($period,$class)
    {
        $classes=$this->ApplicationObj->ClassesObject->SelectHashesFromTable
        (
           "",
           array
           (
              "Period"      => $period[ "ID" ],
              "Grade"       => $class[ "Grade" ],
              "GradePeriod" => $class[ "GradePeriod" ],
           ),
           array("ID","Name","GradePeriod"),
           FALSE,
           "Name"
        );

        $ids=array(0);
        $names=array("");
        foreach ($classes as $class)
        {
            array_push($ids,$class[ "ID" ]);
            array_push($names,$this->ApplicationObj->ClassesObject->ClassName($class));
        }

        return $this->MakeSelectField
        (
           $this->SelectFieldForceName($period),
           $ids,
           $names,
           $class[ "ID" ]
        );
    }

}

?>