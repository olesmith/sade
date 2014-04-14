<?php


class StudentsHistoryAbsence extends StudentsHistoryMarkResult
{
    //*
    //* function AddAbsenceCells, Parameter list: &$row,,$disc,$absenceshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsenceCells(&$row,$disc,$absenceshash)
    {
        if ($absenceshash[ "NAssessments" ]>0)
        {
            for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
            {
                $absence="";
                if (!empty($absenceshash[ "Absences" ][ $n ]))
                {
                    $absence=$absenceshash[ "Absences" ][ $n ];
                }

                array_push($row,$absence);
            }

            array_push($row,"*");
        }
        else
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "",
                  $disc[ "NAssessments" ]
               ),
               "*"
            );
        }
    }

    //*
    //* function AddAbsenceTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddAbsenceTitles(&$titles,$disc)
    {
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            array_push($titles,$this->SUB("F",$n));
        }

        array_push($titles,"*");
    }


}

?>