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
           array("ID","Name","GradePeriod","Grade"),
           FALSE,
           "Grade","GradePeriod","Name"
        );

        $pclasses=array();
        foreach ($classes as $rclass)
        {
            $grade=$rclass[ "Grade" ];
            if (!isset($pclasses[ $grade ])) { $pclasses[ $grade ]=array(); }

            $gper=$rclass[ "GradePeriod" ];
            if (!isset($pclasses[ $grade ][ $gper ])) { $pclasses[ $grade ][ $gper ]=array(); }

            $cname=$rclass[ "Name" ].sprintf("%06d",$rclass[ "ID" ]);
            $pclasses[ $grade ][ $gper ][ $cname ]=$rclass;
        }        

        $ids=array(0);
        $names=array("");

        $grades=array_keys($pclasses);
        sort($grades,SORT_NUMERIC);
        foreach ($grades as $grade)
        {
            $gradename=$this->ApplicationObj->GradeObject->MySqlItemValue("","ID",$grade,"Name");
            array_push($ids,"disabled");
            array_push($names,$gradename);

            $gpers=array_keys($pclasses[ $grade ]);
            sort($gpers,SORT_NUMERIC);
            foreach ($gpers as $gper)
            {
                $gpername=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue("","ID",$gper,"Name");
                array_push($ids,"disabled");
                array_push($names,$gpername);

                $classes=array_keys($pclasses[ $grade ][ $gper ]);
                sort($classes);
                foreach ($classes as $cname)
                {
                    array_push($ids,$pclasses[ $grade ][ $gper ][ $cname ][ "ID" ]);
                    array_push
                    (
                       $names,
                       $this->ApplicationObj->ClassesObject->ClassName($pclasses[ $grade ][ $gper ][ $cname ])
                    );
                }
            }
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