<?php

class Departments extends Common
{

    //*
    //* Variables of Departments class:


    //*
    //* function Units, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function Departments($args=array())
   {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Name","Secretary");
        $this->Sort=array("Name");
        $this->IncludeAllDefault=FALSE;
   }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        foreach (array("State") as $data)
        {
            $this->ItemData[ $data ][ "Values" ]=$this->ApplicationObj->States;
        }

        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";
        $this->ItemData[ "State" ][ "Values" ]=$this->ApplicationObj->States;
        foreach (array("State","WWW","Phone","Fax","Email","City","ZIP") as $key)
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
        if ($module!="Departments")
        {
            return $item;
        }

        if (!isset($item[ "ID" ]) || $item[ "ID" ]==0) { return $item; }

        if (isset($this->ApplicationObj->Unit))
        {
            $udatas=array();
            foreach (array_keys($this->ApplicationObj->Unit) as $data)
            {
                if (isset($this->ItemData[ $data ]) && empty($item[ $data ]))
                {
                    $item[ $data ]=$this->ApplicationObj->Unit[ $data ];
                    array_push($udatas,$data);
                }
            }

            if (count($udatas)>0)
            {
                $this->MySqlSetItemValues("",$udatas,$item);
            }
        }

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

        if ($this->LoginType=="Person")
        {
            //$wheres[ "Unit" ]=$this->LoginData[ "Unit" ];
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
        if (preg_match('/^(Admin)$/',$this->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Secretary)$/',$this->Profile))
        {
            if ($item[ "Secretary" ]==$this->LoginData[ "ID" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function ReadDepartment, Parameter list:
    //*
    //* Reads Department from GET.
    //*

    function ReadDepartment()
    {
        if ($this->ApplicationObj->Department) { return; }
        if (!$this->ApplicationObj->DepartmentsObject) { return; }

        $id=$this->GetCGIVarValue($this->ApplicationObj->DepartmentSearchField);
        $id=preg_replace('/[^\d]/',"",$id);

        if ($this->IsAnID($id))
        {
            $this->ApplicationObj->Department=$this->SelectUniqueHash
            (
               "",
               array("ID" => $id)
            );
        }
    }

}

?>