<?php

include_once("Class/Questions/Import.php");


class ClassQuestions extends ClassQuestionsImport
{

    //*
    //* Variables of ClassDiscs class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function ClassQuestions($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Grade","Class","Period","GradePeriod");
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

        $this->Actions[ "Edit" ][ "HrefArgs" ]="ModuleName=Classes&Action=Disc&Disc=#ID";
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