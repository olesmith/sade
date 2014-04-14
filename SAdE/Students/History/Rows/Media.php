<?php


class StudentsHistoryMedia extends StudentsHistoryMark
{
 
    //*
    //* function AddMediaCells, Parameter list: &$row,,$disc,$markshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMediaCells(&$row,$disc,$markshash)
    {
        if ($markshash[ "NAssessments" ]>0)
        {
            array_push
            (
               $row,
               $markshash[ "Media" ],
               "*"
            );
        }
        else
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "",
                  1
               ),
               "*"
            );
        }
    }


    //*
    //* function AddMediaTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddMediaTitles(&$titles,$disc)
    {
        array_push
        (
           $titles,
           "M",
           "*"
       );
    }


}

?>