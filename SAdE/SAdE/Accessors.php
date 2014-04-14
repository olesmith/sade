<?php


class SAdEAccessors  extends SAdELeftMenu
{
    var $School=array();
    var $Schools=array();

    var $Periods=array();
    var $Period=array();

    var $Class=array();
    var $Classes=array();

    //*
    //* function AccessVar, Parameter list: $var,$key=""
    //*
    //* Returns $this->$var($key), tries to read if empty.
    //*

    function AccessVar($var,$key="")
    {
        if (empty($this->$var))
        {
            $read="Read".$var;
            $this->$read();
            if (empty($this->$var))
            {
                $this->PrintCallStack();
                die("AccessVar: ".$var." not found...");
            }
        }

        if (!empty($key))
        {
            $item=$this->$var;
            $value="";
            if (isset($item[ $key ])) { $value=$item[ $key ]; }

            return $value;
        }

        return $this->$var;
    }

    //*
    //* function AccessVars Parameter list: $var,$id=0,$key=""
    //*
    //* Returns $this->$var($key), tries to read if empty.
    //*

    function AccessVars($var,$id=0,$key="")
    {
        if (empty($this->$var))
        {
            $read="Read".$var;
            $this->$read();
            if (empty($this->$var))
            {
                $this->PrintCallStack();
                die("No ".$var." found/permitted...");
            }
        }

        if (!empty($id) && $id>0)
        {
            foreach ($this->$var as $sid => $item)
            {
                if ($id==$item[ "ID" ])
                {
                    if (!empty($key))
                    {
                        $value="";
                        if (isset($$item[ $key ])) { $value=$item[ $key ]; }

                        return $value;
                    }

                    return $item;
                }
            }
        }

       return $this->$var;
    }

    //*
    //* function AccessVarsKeyValues Parameter list: $var,$key="ID"
    //*
    //* Returns list values of $this->$var $key.
    //*

    function AccessVarsKeyValues($var,$key="ID")
    {
        $values=array();
        foreach ($this->$var() as $item)
        {
            array_push($values,$item[ $key ]);
        }

        return $values;
    }

    //*
    //* function School, Parameter list: $key=""
    //*
    //* Returns $this->Schools, tries to read if empty.
    //*

    function School($key="")
    {
        return $this->AccessVar("School",$key);
    }

    //*
    //* function Schools, Parameter list: $id=0,$key=""
    //*
    //* Returns $this->Schools, tries to read if empty.
    //*

    function Schools($id=0,$key="")
    {
        return $this->AccessVars("Schools",$id,$key);
    }



    //*
    //* function SchoolIDs, Parameter list:
    //*
    //* Opens Unit DB and connects.
    //*

    function SchoolIDs()
    {
        return $this->AccessVarsKeyValues("Schools","ID");
    }


    //*
    //* function Period, Parameter list: $key=""
    //*
    //* Returns $this->Period, tries to read if empty.
    //*

    function Period($key="")
    {
        return $this->AccessVar("Period",$key);
    }

    //*
    //* function Periods, Parameter list: $id=0,$key=""
    //*
    //* Returns $this->Periods, tries to read if empty.
    //*

    function Periods($id=0,$key="")
    {
        return $this->AccessVars("Periods",$id,$key);
    }


    //*
    //* function GetClass, Parameter list: $key=""
    //*
    //* Returns $this->Class, tries to read if empty.
    //*

    function GetClass($key="")
    {
        return $this->AccessVar("Class",$key);
    }

    //*
    //* function Classes, Parameter list: $id=0,$key=""
    //*
    //* Returns $this->Periods, tries to read if empty.
    //*

    function Classes($id=0,$key="")
    {
        return $this->AccessVars("Classes",$id,$key);
    }



}

?>
