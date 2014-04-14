<?php

include_once("Class/Status/Tables.php");


class ClassStatus extends ClassStatusTables
{

    //*
    //* Variables of ClassStatus class:
    //*

    var $Status=array();
    var $StatusData=array("MediaFinal","Percent","Result",);
    var $StatusDataTitles=array("MF","FP","R",);

    var $StudentData=array("Name","Status","StatusDate1");
    var $StudentDataTitles=array("No.","Nome","Status");


    //*
    //*
    //* Constructor.
    //*

    function ClassStatus($args=array())
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
        $this->ApplicationObj->ReadClass();
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


    //*
    //* function PaintStudentResult, Parameter list: $res
    //*
    //* Creates green AP, if $res is TRUE, red RE otherwise
    //*

    function PaintStudentResult($res)
    {
        if ($res==1)
        {
            $res=$this->TextColor("green","AP");
        }
        elseif ($res==2 || $res==3 || $res==4)
        {
            $res=$this->TextColor("red","RE");
        }
        else { $res="-"; }

        return $res;
    }

    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=TRUE;

        return $res;
    }

    //*
    //* function ReadStudentDiscStatus, Parameter list: $classid,$discid,$studentid
    //*
    //* Reads Student Disc Status from DB.
    //*

    function ReadStudentDiscStatus($class,$disc,$student)
    {
        $hash=array
        (
           "Class"     => $class[ "ID" ],
           "ClassDisc" => $disc[ "ID" ],
           "Student"   => $student[ "StudentHash" ][ "ID" ],
        );

        $res=$this->SelectUniqueHash
        (
           "",
           $hash,
           TRUE,
           array("Status")
        );

        if (empty($res))
        {
            $hash[ "Status" ]=1;
            $res[ "Status" ]=1;
            $this->MySqlInsertItem("",$hash);
        }

        if (empty($res)) { return ""; }
        else             { return $res[ "Status" ]; }
    }

}

?>