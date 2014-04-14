<?php


class ClassDiscContentsWheres extends ClassDiscContentsForm
{
    //*
    //*
    //* function PeriodSearchSqlWhere, Parameter list: $period
    //*
    //* Generates html select field for semesters.
    //*

    function PeriodSearchSqlWhere($period)
    {
        return $this->ApplicationObj->PeriodsObject->DayliesSqlWhere($period);
    }

    //*
    //*
    //* function DatesSearchSqlWhere, Parameter list: $period,$disc
    //*
    //* Generates dates sql where, from Search form.
    //*

    function DatesSearchSqlWhere($period,$disc)
    {
        $wheres=array($this->DatesSqlWhere($period));

        $semester=$this->GetPOST("Semester");
        if (!empty($semester))
        {
            array_push
            (
               $wheres,
               $this->ApplicationObj->PeriodsObject->Trimester2DatesSqlWhere($semester,$period)
            );
        }

        $month=$this->GetPOST("Month");
        if (!empty($month))
        {
            array_push
            (
               $wheres,
               "Month='".$month."'"
            );
        }

        $date=$this->GetPOST("Date");
        if (!empty($date))
        {
            array_push
            (
               $wheres,
               "Day='".$date."'"
            );
        }

        $wday=$this->GetPOST("WeekDay");
        $programmed=$this->GetPOST("Programmed");
        if (!empty($wday))
        {
            array_push
            (
               $wheres,
               "WeekDay='".$wday."'"
            );
        }
        elseif ($programmed==1)
        {
            $wdays=$this->DiscLessonDates($disc);
            array_push
            (
               $wheres,
               "WeekDay IN ('".join("','",$wdays)."')"
            );
        }

        return join(" AND ",$wheres);
    }
}

?>