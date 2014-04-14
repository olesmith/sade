<?php

include_once("Class/Discs/Dayly/Students/Table.php");
include_once("Class/Discs/Dayly/Students/Update.php");
include_once("Class/Discs/Dayly/Students/Handle.php");

class ClassDiscsDaylyStudents extends ClassDiscsDaylyStudentsHandle
{
    var $StudentData=array("Name","Matricula","MatriculaDate","Status","StatusDate1",);

    //*
    //* function PostOrDefault, Parameter list: $post,$default
    //*
    //* Returns POST NStudentsPP or default.
    //*

    function PostOrDefault($post,$default)
    {
        $value=$this->GetPOST($post);
        if (empty($value))
        {
            $value=$default;
        }

        return $value;
    }

    //*
    //* function ComponentCGIKey, Parameter list: $component,$class,$disc
    //*
    //* Return CGI Dayly Print checkbox key. If ==1, we print...
    //*

    function ComponentCGIKey($component,$class,$disc)
    {
        return $component."_".$class[ "ID" ]."_".$disc[ "ID" ];
    }

    //*
    //* function ComponentCGIValue, Parameter list: $component,$class,$disc
    //*
    //* Returns POST NStudentsPP or default.
    //*

    function ComponentCGIValue($component,$class,$disc)
    {
        return $this->GetPOST
        (
           $this->ComponentCGIKey($component,$class,$disc)
        );
    }


}

?>