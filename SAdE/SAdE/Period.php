<?php


class SAdEPeriod extends SAdESchool
{
    var $PeriodModules=array
    (
       "ClassDiscs","ClassDiscLessons","ClassDiscNLessons","ClassDiscWeights",
       "ClassStudents","ClassMarks","ClassAbsences","ClassStatus",
       "ClassQuestions","ClassObservations",
    );

    //*
    //* function ReadPeriods, Parameter list:
    //*
    //* Reads all periods according to $where.
    //* 
    //*

    function ReadPeriods()
    {
        if (empty($this->PeriodsObject))
        {
            $this->LoadSubModule("Periods");
        }
        return $this->PeriodsObject->ReadPeriods();
    }

    //*
    //* function ReadPeriod, Parameter list: $where=array()
    //*
    //* Reads all periods according to $where.
    //* 
    //*

    function ReadPeriod()
    {
        if (empty($this->PeriodsObject))
        {
            $this->LoadSubModule("Periods");
        }
        return $this->PeriodsObject->ReadPeriod();
    }


    //*
    //* function ReadSchoolPeriods, Parameter list:
    //*
    //* Reads periods referenced by school.
    //* 
    //*

    function ReadSchoolPeriods($all=FALSE)
    {
        $this->PeriodsObject->ReadPeriods();
        return;
    }


    //*
    //* function GetPeriodName, Parameter list: $period=array()
    //*
    //* Returns calling name of Period.
    //* 
    //*

    function GetPeriodName($period=array())
    {
        return $this->PeriodsObject->GetPeriodName($period);
    }

    //*
    //* function GetPeriodTitle, Parameter list: $period=array()
    //*
    //* Returns calling name of Period.
    //* 
    //*

    function GetPeriodTitle($period=array())
    {
        return $this->PeriodsObject->GetPeriodTitle($period);
    }


     //*
    //* function FindDatePeriod, Parameter list: $date,$mode
    //*
    //* Locates Period that $data belongs to.
    //* 
    //*

    function FindDatePeriod($date,$mode)
    {
        return $this->PeriodsObject->FindDatePeriod($date,$mode);
     }

    //*
    //* function LocatePeriod, Parameter list: $periodid
    //*
    //* Locates Period with ID $periodid.
    //* 
    //*

    function LocatePeriod($periodid)
    {
        return $this->PeriodsObject->LocatePeriod($periodid);
    }
}

?>
