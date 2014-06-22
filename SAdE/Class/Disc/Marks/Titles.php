<?php


class ClassDiscMarksTitles extends ClassDiscMarksRow
{
    var $TrimesterRow=0;
    var $MaxsRow=1;
    var $ShortsRow=3;
    var $LongsRow=2;
    var $StudentDataActions=array();

    //*
    //* function DaylyMarksTableTitleRows, Parameter list: $assess,$include
    //*
    //* Handles Dayly Marks pages.
    //*

    function DaylyMarksTableTitleRows($assess,$include)
    {
        $table=array
        (
           array(),
           array(),
           array(),
           array(),
        );

        $this->DaylyMarksStudentTitleRows($table);

        if (!empty($include[ "Semesters" ]))
        {
            $this->DaylyMarksTrimestersTitleRows($table,$assess,$include);
        }

        if ($this->ApplicationObj->Disc[ "NRecoveries" ]>0)
        {
            if (!empty($include[ "SemesterResults" ]))
            {
                $this->DaylyMarksTrimestersResultTitleRows($table,$assess);
            }

            if (!empty($include[ "Recoveries" ]))
            {
                $this->DaylyMarksRecoveriesTitleRows($table,$assess,$include);
            }
        }

        if (!empty($include[ "Result" ]))
        {
            $this->DaylyMarksFinalResultTitleRows($table,$assess);
        }

        if (!$this->LatexMode())
        {
            $this->DaylyMarksMessageTitleRow($table);
        }

        return $table;
    }
}

?>