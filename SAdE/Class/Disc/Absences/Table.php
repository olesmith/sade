<?php


class ClassDiscAbsencesTable extends ClassDiscAbsencesTitles
{
    //*
    //* function DaylyAbsencesTable, Parameter list: $edit,$month
    //*
    //* Handles Dayly Absences pages.
    //*

    function DaylyAbsencesTable($edit,$month)
    {
        $this->ApplicationObj->DaylyMonths=$this->ApplicationObj->PeriodsObject->GetMonths();
        $chs=$this->ApplicationObj->ClassDiscContentsObject->ReadDaylyContents();

        if ($edit==1 && $this->GetPOST("Save")==1)
        {
            $this->UpdateStudentsAbsenceTable();
        }

        $table=$this->DaylyAbsencesTableTableTitleRows($month,$chs);

        $date=$this->GetGET("Date");
        $today=$this->TimeStamp2DateSort();

        $rdate="";
        foreach ($this->ApplicationObj->Contents as $semester => $contents)
        {
            foreach ($contents as $content)
            {
                $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);

                    if ($datekey==$date) { $rdate=$datekey; }
                elseif (empty($date) && $datekey==$today) { $rdate=$datekey; }
            }
        }

        $n=1;
        foreach ($this->ApplicationObj->Students as $student)
        {
            //Not matriculated - disappear from daylies
            if (intval($student[ "StudentHash" ][ "Status" ])==8) { continue; }

            array_push
            (
               $table,
               $this->DaylyAbsencesStudentRow($edit,$n++,$month,$student,$rdate,$chs)
            );
        }

        return $table;
    }



    //*
    //* function DaylyAbsencesStatsTable, Parameter list: 
    //*
    //* Handles Dayly Absences pages.
    //*

    function DaylyAbsencesStatsTable($type)
    {
        $this->ApplicationObj->DaylyMonths=$this->ApplicationObj->PeriodsObject->GetMonths();

        $chs=$this->ApplicationObj->ClassDiscContentsObject->ReadDaylyContents();

        $table=$this->DaylyAbsencesStatsTitleRows($type,$chs);

        $n=1;
        foreach ($this->ApplicationObj->Students as $student)
        {
            //Not matriculated - disappear from daylies
            if (intval($student[ "StudentHash" ][ "Status" ])==8) { continue; }

            array_push
            (
               $table,
               $this->DaylyAbsencesStudentStatsRow($type,$n++,$student,$chs)
            );
        }

        return $table;
    }
}

?>