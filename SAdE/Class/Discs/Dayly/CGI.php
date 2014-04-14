<?php


class ClassDiscsCGI extends ClassDiscsTables
{
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