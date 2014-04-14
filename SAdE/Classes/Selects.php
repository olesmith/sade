<?php

class ClassesSelects extends ClassesLeftMenu
{
    //*
    //* function GenPeriodSelect, Parameter list: $item,$item,$edit=0
    //*
    //* Generates Period select field.
    //*

    function GenPeriodSelect($data,$item,$edit=0)
    {
        if ($edit==0)
        {
            return $item[ $data."_Name" ];
        }

        $this->ApplicationObj->Periods=array();
        $this->ApplicationObj->ReadSchoolPeriods(TRUE);

        $periods=array();
        foreach ($this->ApplicationObj->Periods as $period)
        {
            array_push($periods,$period);
        }

        //$periods=array_reverse($periods);

        $ids=array(0);
        $names=array("");
        foreach ($periods as $period)
        {
            array_push($ids,$period[ "ID" ]);
            array_push($names,$period[ "Name" ]);
        }

        $value="";
        if (!empty($item[ $data ])) { $value=$item[ $data ]; }
        return $this->MakeSelectField($data,$ids,$names,$value);
    }

    //*
    //* function GenGradeSelect, Parameter list: $item,$item,$edit=0
    //*
    //* Generates Period select field.
    //*

    function GenGradeSelect($data,$item,$edit=0)
    {
        if ($edit==0)
        {
            if (!empty($item[ $data."_Name" ]))
            {
                return $item[ $data."_Name" ];
            }

            return "";
        }
        $this->ApplicationObj->GradeObject->ReadGrades();

        $grades=array();
        foreach ($this->ApplicationObj->Grades as $grade)
        {
            array_push($grades,$grade);
        }

        $grades=array_reverse($grades);

        $ids=array(0);
        $names=array("");
        foreach ($grades as $grade)
        {
            array_push($ids,$grade[ "ID" ]);
            array_push($names,$grade[ "Name" ]);
        }

        $value="";
        if (!empty($item[ $data ])) { $value=$item[ $data ]; }
        if (empty($value)) { $value=$this->GetPOST("Grade"); }
        return $this->MakeSelectField($data,$ids,$names,$value);
    }

   //*
    //* function GenGradePeriodSelect, Parameter list: $item,$item,$edit=0
    //*
    //* Generates Period select field.
    //*

    function GenGradePeriodSelect($data,$item,$edit=0)
    {
        if ($edit==0)
        {
            return $item[ $data."_Name" ];
        }

        $grade=$this->GetPOST("Grade");
        if (empty($grade) && !empty($item[ "Grade" ])) { $grade=$item[ "Grade" ]; }
        $this->ApplicationObj->GradeObject->ReadGradePeriods($grade);

        $grperiods=array();
        foreach ($this->ApplicationObj->Grades[ $grade-1 ][ "Periods" ] as $grperiod)
        {
            array_push($grperiods,$grperiod);
        }

        $ids=array(0);
        $names=array("");
        foreach ($grperiods as $grperiod)
        {
            array_push($ids,$grperiod[ "ID" ]);
            array_push($names,$grperiod[ "Name" ]);
        }

        $value="";
        if (!empty($item[ $data ])) { $value=$item[ $data ]; }
        if (empty($value)) { $value=$this->GetPOST("GradePeriod"); }
        return $this->MakeSelectField($data,$ids,$names,$value);
    }
}

?>