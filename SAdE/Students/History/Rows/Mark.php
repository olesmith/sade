<?php


class StudentsHistoryMark extends StudentsHistoryRead
{
    //*
    //* function AddMarkCells, Parameter list: &$row,,$disc,$markshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMarkCells(&$row,$disc,$markshash)
    {
        if ($markshash[ "NAssessments" ]>0)
        {
            for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
            {
                $mark="";
                if (!empty($markshash[ "Marks" ][ $n ]))
                {
                    $mark=$markshash[ "Marks" ][ $n ];
                }

                array_push($row,$mark);
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
    //* function AddMarkTitles, Parameter list: &$titles,$class
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMarkTitles(&$titles,$class)
    {
        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push($titles,$this->SUB("N",$n));
        }

        array_push($titles,"*");
   }
  
}

?>