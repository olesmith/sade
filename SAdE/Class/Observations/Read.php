<?php

class ClassObservationsRead extends ClassObservationsUpdate
{
    //*
    //* function ReadObservation, Parameter list: $class,$question,$student,,$n
    //*
    //* Returns registered value, consulting POST, if allowed: $edit=1. 
    //*

    function ReadObservation($class,$student,$n)
    {
        $item=$this->ObservationSqlWhere($class,$student,$n);


        $res=$this->SelectUniqueHash
        (
           "",
           $item,
           TRUE,
           array()
        );

        if (empty($res)) { $item[ "Value" ]="";$item[ "ResponsibleValue" ]=""; }
        else             { $item=$res; }
       

        return $item;
    }
}

?>