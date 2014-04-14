<?php

include_once("Class/Observations/Import.php");
include_once("Class/Observations/Fields.php");
include_once("Class/Observations/Tables.php");
include_once("Class/Observations/Update.php");
include_once("Class/Observations/Read.php");


class ClassObservations extends ClassObservationsRead
{

    //*
    //* Variables of ClassDiscs class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function ClassObservations($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Name");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }


        return $item;
    }


}

?>