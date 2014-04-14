<?php


class ClassStatusCalc extends Common
{
    //*
    //* function CalcStudentDiscStatus, Parameter list: $markshash,$absenceshash
    //*
    //* Populates discipline marks for student.

    function CalcStudentDiscStatus($markshash,$absenceshash)
    {
        $res=0;
        if ($markshash[ "MarkResult" ]==2)
        {
            if ($absenceshash[ "AbsencesResult" ]==2)
            {
                $res=1;
            }
            else
            {
                $res=1;
            }
        }
        elseif ($markshash[ "MarkResult" ]==1)
        {
            if ($absenceshash[ "AbsencesResult" ]==2)
            {
                $res=2;
            }
            else
            {
                $res=3;
            }
        }

        return $res;
    }

}

?>