<?php

class Places extends Common
{

    //*
    //* Variables of Places class:

    var $DefaultUnitsData=array("State","City","ZIP","Phone","Fax","Email","WWW");

    //*
    //* function Places, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function Places($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Name","Department");
        $this->Sort=array("Name");
    }


    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";

        foreach (array("State") as $data)
        {
            $this->ItemData[ $data ][ "Values" ]=$this->ApplicationObj->States;
        }

        if (preg_match('/^(Secretary)$/',$this->Profile))
        {
            //$this->AddDefaults[ "Department" ]=$this->LoginData[ "Department" ];
            $this->AddFixedValues[ "Department" ]=$this->LoginData[ "Department" ];
        }

        foreach ($this->DefaultUnitsData as $key)
        {
            $this->AddDefaults[ $key ]=$this->ApplicationObj->Unit[ $key ];
        }
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Postprocesses and returns $item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Places")
        {
            return $item;
        }

        $this->TakeUndefinedListOfKeys
        (
           $item,
           $this->ApplicationObj->Unit,
           $this->DefaultUnitsData,
           TRUE
        );

        return $item;
    }


    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for Units.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        return $where;
    }

    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited. Admin may -
    //* and Person, if LoginData[ "ID" ]==$item[ "ID" ]
    //*

    function CheckEditAccess($item)
    {
        $res=FALSE;
        if (preg_match('/^(Admin|Secretary)$/',$this->Profile))
        {
            $res=TRUE;
        }
        /* elseif (preg_match('/^(Secretary)$/',$this->Profile)) */
        /* { */
        /*     $secretary=$this->ApplicationObj->DepartmentsObject->MySqlItemValue("","ID",$item[ "Department" ],"Secretary"); */
        /*     if ($secretary==$this->LoginData[ "ID" ]) */
        /*     {             */
        /*         $res=TRUE; */
        /*     } */
        /* } */

        return $res;
    }


}

?>