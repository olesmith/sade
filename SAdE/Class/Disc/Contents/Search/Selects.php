<?php


class ClassDiscContentsSelects extends Common
{
    //*
    //*
    //* function SemesterSearchSelect, Parameter list: 
    //*
    //* Generates html select field for semesters.
    //*

    function SemesterSearchSelect($disc)
    {
        $values=array(0);
        $names=array("");
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            array_push($values,$n);
            array_push($names,$n);
        }

        return $this->MakeSelectField
        (
           "Semester",
           $values,
           $names,
           $this->GetPOST("Semester")
        );
    }

    //*
    //*
    //* function MonthSearchSelect, Parameter list: $period
    //*
    //* Generates html select field for months.
    //*

    function MonthSearchSelect($period)
    {
        $values=array(0);
        $names=array("");

        $months=$this->ApplicationObj->PeriodsObject->GetMonths($period);
        for ($n=1;$n<=count($months);$n++)
        {
            array_push($values,$n);
            array_push($names,$this->ApplicationObj->PeriodsObject->GetMonthName($months[ $n-1 ]));
        }

        return $this->MakeSelectField
        (
           "Month",
           $values,
           $names,
           $this->GetPOST("Month")
        );
    }

    //*
    //*
    //* function WeekDaySearchSelect, Parameter list: 
    //*
    //* Generates html select field for weekdays.
    //*

    function WeekDaySearchSelect()
    {
        return $this->ApplicationObj->SchoolsObject->SchoolWeekDaysSelect
        (
           "WeekDay",
           $this->GetPOST("WeekDay")
        );
    }

    //*
    //*
    //* function DateSearchSelect, Parameter list: 
    //*
    //* Generates html select field for weekdays.
    //*

    function DateSearchSelect()
    {
        $values=array(0);
        $names=array("");

        for ($n=1;$n<=31;$n++)
        {
            array_push($values,$n);
            array_push($names,$n);
        }

        return $this->MakeSelectField
        (
           "Date",
           $values,
           $names,
           $this->GetPOST("Date")
        );
    }

}

?>