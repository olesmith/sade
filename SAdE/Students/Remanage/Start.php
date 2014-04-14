<?php



class StudentsRemanageStart extends StudentsRemanageTrimesters
{
    //*
    //* function StartColsTitles, Parameter list: 
    //*
    //* Creates informative table,. assisiting decision to remanage student.
    //* 
    //*

    function StartColsTitles()
    {
        return array
        (
           array($this->MultiCell("",2)),
           array($this->MultiCell("",2)),
           $this->B(array("No.","Disciplina")),
        );
    }

    //*
    //* function StartCols, Parameter list: $n,$disc
    //*
    //* Creates informative table,. assisiting decision to remanage student.
    //* 
    //*

    function StartCols( $n,$disc)
    {
        $row=array
        (
           sprintf("%02d",$n++),
           $disc[ "Name" ]
        );

        return $row;
    }
}

?>