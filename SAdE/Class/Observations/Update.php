<?php

class ClassObservationsUpdate extends ClassObservationsTables
{
    //*
    //* function UpdateObservationField, Parameter list: $class,$student,$n
    //*
    //* Updates and creates Observation input field.
    //*

    function UpdateObservationField($class,$student,$n)
    {
        $item=$this->ReadObservation($class,$student,$n);
        $value=$item[ "Value" ];

        $newvalue=$this->GetPOST
        (
           $this->ObservationCGIField($class,$student,$n)
        );

        if ($newvalue=="") { $newvalue=NULL; }
        if ($newvalue!=$value)
        {
            $where=$this->ObservationSqlWhere($class,$student,$n);

            $item=$where;
            $item[ "Value" ]=$newvalue;
            //$item[ "Teacher" ]=$teacherid;

            $this->AddOrUpdate("",$where,$item);
        }
    }

    //*
    //* function UpdateObservations, Parameter list: $class,$student
    //*
    //* Updates questions fields.
    //*

    function UpdateObservations($class,$student)
    {
        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            $this->UpdateObservationField($class,$student,$n);
        }
    }
}

?>