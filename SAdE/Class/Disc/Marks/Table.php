<?php


class ClassDiscMarksTable extends ClassDiscMarksTitles
{
    var $AssessmentsTotals=array();

    //*
    //* function ConfigureDaylyMarksTable, Parameter list:
    //*
    //* Handles Dayly Marks pages.
    //*

    function ConfigureDaylyMarksTable()
    {
        $include=array
        (
           "Semesters" => array(),
           "SemesterResults" => 1,
           "Recoveries" => array(),
           "Result" => 1,
        );
        
        for ($semester=1;$semester<=$this->ApplicationObj->Disc[ "NAssessments" ];$semester++)
        {
            $include[ "Semesters" ][ $semester ]=TRUE;
        }

        for ($recovery=1;$recovery<=$this->ApplicationObj->Disc[ "NRecoveries" ];$recovery++)
        {
            $include[ "Recoveries" ][ $recovery ]=TRUE;
        }

        return $include;

    }


    //*
    //* function DaylyMarksTable, Parameter list: $edit,$nitemsperpage=0
    //*
    //* Handles Dayly Marks pages.
    //*

    function DaylyMarksTable($edit,$nitemsperpage=0,$include=array())
    {
        $assess=$this->ApplicationObj->ClassDiscAssessmentsObject->ReadDaylyAssessments();

        if ($nitemsperpage==0) { $nitemsperpage=count(array_keys($this->ApplicationObj->Students)); }

        if (empty($include))
        {
            $include=$this->ConfigureDaylyMarksTable();
        }

        $titles=$this->DaylyMarksTableTitleRows($assess,$include);

        if ($this->LatexMode())
        {
            $titles=array($titles[0],$titles[1],$titles[3]);
        }

        if (!$this->LatexMode()) { $nitemsperpage=2*count($this->ApplicationObj->Students); }

        //List of table per students, break for max no of students
        $tables=array();
        $table=$titles;

        $n=1;
        foreach ($this->ApplicationObj->Students as $student)
        {
            //Not matriculated - disappear from daylies!!!
            if (intval($student[ "StudentHash" ][ "Status" ])==8) { continue; }

            if ($n>1 && ($n % $nitemsperpage)==1)
            {
                array_push($tables,$table);
                $table=$titles;
            }

            array_push
            (
               $table,
               $this->DaylyMarksStudentRow($edit,$n++,$student,$assess,$include)
            );
        }

        //Table must have more rows than just the titles.
        if (count($table)>count($titles))
        {
            array_push($tables,$table);
        }

        return $tables;
    }
}

?>