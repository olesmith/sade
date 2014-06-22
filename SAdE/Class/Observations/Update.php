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

        $update=FALSE;

        $value=$item[ "Value" ];
        $newvalue=$this->GetPOST
        (
           $this->ObservationCGIField($class,$student,$n)
        );

        if ($newvalue=="") { $newvalue=NULL; }
        if ($newvalue!=$value)
        {
            $update=TRUE;
            $item[ "Value" ]=$newvalue;
        }

        $rvalue=$item[ "ResponsibleValue" ];
        $newvalue=$this->GetPOST
        (
           $this->ResponsibleObservationCGIField($class,$student,$n)
        );

        if ($newvalue=="") { $newvalue=NULL; }
        if ($newvalue!=$rvalue)
        {
            $update=TRUE;
            $item[ "ResponsibleValue" ]=$newvalue;
        }

        if ($update)
        {
            $where=$this->ObservationSqlWhere($class,$student,$n);
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