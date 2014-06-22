<?php


class ClassDiscMarksCell extends ClassDiscMarksUpdate
{
    //*
    //* function DaylyMarksStudentMarkCell, Parameter list: $edit,$student,$m,$assessment,$mark
    //*
    //* Generates mark cell for student.
    //*

    function DaylyMarksStudentMarkCell($edit,$student,$m,$assessment,$mark)
    {
        if (preg_match('/\d/',$mark)) { $mark=sprintf("%.1f",$mark); }

        $attention="";
        if ($mark>$assessment[ "MaxVal" ])
        {
            $attention=$this->SPAN
            (
               "*",
               array
               (
                  "TITLE" => 
                    "Nota Invalida! ".
                    sprintf("%.1f",$mark).">".
                   sprintf("%.1f",$assessment[ "MaxVal" ]),
                  "STYLE" => 'color: red;'
               )
            );
        }

        if ($edit==1)
        {
            return $this->MakeInput
            (
               $this->StudentMarkCGIName($student,$assessment),
               $mark,
               1,
               array("TABINDEX" => $m)
            ).
            $attention;
        }
        else
        {
            return $mark." ".$attention;
        }
    }
}

?>