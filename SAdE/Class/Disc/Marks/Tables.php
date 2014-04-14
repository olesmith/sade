<?php


class ClassDiscMarksTables extends ClassDiscMarksTable
{
    //*
    //* function ConfigureDaylyMarksTables, Parameter list: 
    //*
    //* Distributes Marks table over several pages, breaking
    //* when number of columns exceeds 
    //*

    function ConfigureDaylyMarksTables()
    {
        $include=array
        (
           "Semesters" => array(),
           "SemesterResults" => 0,
           "Recoveries" => array(),
           "Result" => 0,
        );

        $page=0;
        $includes=array($include);

        $rwidth=0;
        for ($semester=1;$semester<=$this->ApplicationObj->Disc[ "NAssessments" ];$semester++)
        {
            $width=count($this->Assessments[ $semester ]);
            if ($width>1) { $width++; }

            //A semester fills at least three cell widths
            $width=$this->Max(3,$width);

            if ( ($width+$rwidth)>$this->NMarkCells)
            {
                array_push($includes,$include);
                $rwidth=0;
                $page++;
            }

            $includes[ $page ][ "Semesters" ][ $semester ]=TRUE;
            $rwidth+=$width;
        }

        $width=2;
        if ( ($width+$rwidth)>$this->NMarkCells)
        {
            array_push($includes,$include);
            $rwidth=0;
            $page++;
        }

        $includes[ $page ][ "SemesterResults" ]=TRUE;
        $rwidth+=$width;


        for ($recovery=1;$recovery<=$this->ApplicationObj->Disc[ "NRecoveries" ];$recovery++)
        {
            $width=count($this->Assessments[ $recovery ]);
            if ($width>1) { $width++; }

            if ( ($width+$rwidth)>$this->NMarkCells)
            {
                array_push($includes,$include);
                $rwidth=0;
                $page++;
            }

            $includes[ $page ][ "Recoveries" ][ $recovery ]=TRUE;
            $rwidth+=$width;
        }

        $width=2;
        if ( ($width+$rwidth)>$this->NMarkCells)
        {
            array_push($includes,$include);
            $rwidth=0;
            $page++;
        }

        $includes[ $page ][ "Result" ]=TRUE;
        $rwidth+=$width;

        return $includes;

    }
    //*
    //* function DaylyMarksTables, Parameter list: $nitemsperpage=0
    //*
    //* Handles Dayly Marks pages - splits marks over several pages.
    //*

    function DaylyMarksTables($nitemsperpage=0)
    {
        $includes=$this->ConfigureDaylyMarksTables();

        $tables=array();
        foreach ($includes as $include)
        {
            $tables=array_merge
            (
               $tables,
               $this->DaylyMarksTable
               (
                  0,
                  $this->ApplicationObj->ClassDiscAbsencesObject->NStudentsPerPage,
                  $include
               )
            );
        }

        return $tables;
    }

}

?>