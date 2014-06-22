<?php

include_once("Users/Import.php");
include_once("Users/Add.php");
include_once("Users/Teacher.php");


class Users extends UsersTeacher
{
    var $TeacherScheduleTeacherData=array("Name","Email","Status");
    var $TeacherScheduleDiscData=array("Class","Name");
    var $TeacherScheduleDiscActions=array("Edit","Discs","Students","Hours","Dayly");


    //*
    //* Variables of  class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function Users($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("Name");
        $this->AlwaysReadData=array("Name","School");

        array_unshift($this->ItemDataFiles,"../People/Data.php");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        //Adds profiles data
        parent::PostProcessItemData();

        $this->Actions[ "Show" ][ "AccessMethod" ]="CheckShowAccess";
        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";

        $this->SetItemDataDefaults();
        $this->SetItemDataPermissions();

        if (preg_match('/(Clerk|Coordinator)/',$this->ApplicationObj->Profile))
        {
            /* $sids=array(); */
            /* foreach ($this->ApplicationObj->Schools() as $school) */
            /* { */
            /*     array_push($sids,$school[ "ID" ]); */
            /* } */

            /* //$this->SqlWhere[ "School" ]=$this->ApplicationObj->SchoolIDs(); */

            $this->ItemName="Professor(a)";
            $this->ItemsName="Professores";
       }
    }

    //*
    //* function SetItemDataDefaults, Parameter list:
    //*
    //* Sets the defaults of ItemData: Staes and Cities.
    //*

    function SetItemDataDefaults()
    {
        foreach (array("State","BirthState","PRN1State","PRN2State","PRN3State") as $data)
        {
            $this->ItemData[ $data ][ "Values" ]=$this->ApplicationObj->States_Short;
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "State" ];
        }

        foreach (array("City","BirthCity","PRN1City","PRN2City","PRN3City") as $data)
        {
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "City" ];
        }
        foreach (array("ZIP") as $data)
        {
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "ZIP" ];
        }
    }


    //*
    //* function SetItemDataPermissions, Parameter list:
    //*
    //* Sets the defaults of ItemData permissions.
    //*

    function SetItemDataPermissions()
    {
        //May enable teacher status
        if (preg_match('/(Admin|Clerk|Secretary)/',$this->ApplicationObj->Profile))
        {
            $this->ItemData[ "Profile_Teacher" ][  $this->ApplicationObj->Profile ]=2;
            $this->ItemData[ "Profile_Teacher" ][  "Search" ]=TRUE;
        }
        elseif (preg_match('/(Coordinator)/',$this->ApplicationObj->Profile))
        {
            $this->ItemData[ "Profile_Teacher" ][  $this->ApplicationObj->Profile ]=1;
            $this->ItemData[ "Profile_Teacher" ][  "Search" ]=TRUE;
        }
        else
        {
            $this->ItemData[ "Profile_Teacher" ][  $this->ApplicationObj->Profile ]=1;
        }

        //May enable clerk status
        if (preg_match('/(Admin|Secretary)/',$this->ApplicationObj->Profile))
        {
            $this->ItemData[ "Profile_Clerk" ][  $this->ApplicationObj->Profile ]=2;
            $this->ItemData[ "Profile_Clerk" ][  "Search" ]=TRUE;

            $this->ItemData[ "Profile_Coordinator" ][  $this->ApplicationObj->Profile ]=2;
            $this->ItemData[ "Profile_Coordinator" ][  "Search" ]=TRUE;
        }
        else
        {
            $this->ItemData[ "Profile_Clerk" ][  $this->ApplicationObj->Profile ]=1;
        }

        //May enable secretary status
        if (preg_match('/(Admin)/',$this->ApplicationObj->Profile))
        {
            $this->ItemData[ "Profile_Secretary" ][  $this->ApplicationObj->Profile ]=2;
            $this->ItemData[ "Profile_Secretary" ][  "Search" ]=TRUE;
        }
        else
        {
            $this->ItemData[ "Profile_Secretary" ][  $this->ApplicationObj->Profile ]=1;
        }
    }

     //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
        $unsearches=array("PRN","PIS","PRN1","SUS","Department");

        $profiles=preg_grep('/^Profile_/',array_keys($this->ItemData));
        foreach ($profiles as $data)
        {
            //$this->ItemData[ $data ][ "Search" ]=FALSE;
        }

        foreach ($unsearches as $data)
        {
            $this->ItemData[ $data ][ "Search" ]=FALSE;
        }

        if ($this->GetGET("Teachers")==1)
        {
            $this->ItemName="Professor";
            $this->ItemsName="Professore(a)s";
        }
        elseif ($this->GetGET("Clerks")==1)
        {
            $this->ItemName="Secretario(a) Escolar";
            $this->ItemsName="Secretario(a)s Escolares";
        }
   }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Users")
        {
            return $item;
        }

        $udatas=$this->TakeUndefinedListOfKeys
        (
           $item,
           $this->ApplicationObj->Unit,
           array
           (
              array
              (
                 "Key" => "State",
                 "Keys" => array("State","BirthState","PRN2State","BirthCertState"),
              ),
              array
              (
                 "Key" => "City",
                 "Keys" => array("City","BirthCity","BirthCertCity"),
              ),
              array
              (
                 "Key" => "",
                 "Keys" => array("ZIP"),
              ),
           ),
           TRUE
        );

        return $item;
    }

    //*
    //* function CheckShowAccess, Parameter list: $item
    //*
    //* Checks if $item may be shown.
    //* Admin may always.
    //* Secretary may if Unit matches login unit
    //* Outher persons may if ID matches login ID
    //*

    function CheckShowAccess($item)
    {
        if (empty($item[ "ID" ])) { return FALSE; }

        $res=FALSE;
        if (preg_match('/^(Admin|Secretary|Clerk|Coordinator)$/',$this->ApplicationObj->Profile))
        {
            $res=TRUE;
        }
        /* elseif (preg_match('/^(Clerk|Coordinator)$/',$this->ApplicationObj->Profile)) */
        /* { */
        /*     $res=TRUE; */
        /* } */
        elseif (preg_match('/^(Teacher|Student)$/',$this->ApplicationObj->Profile))
        {
            if ($item[ "ID" ]==$this->ApplicationObj->LoginData[ "ID" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }
    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited or shown.
    //* Admin may always.
    //* Secretary may if Unit matches login unit
    //* Outher persons may if ID matches login ID
    //*

    function CheckEditAccess($item)
    {
        if (empty($item[ "ID" ])) { return FALSE; }

        $res=$this->CheckShowAccess($item);;
        if ($res && preg_match('/^(Clerk|Coordinator)$/',$this->Profile))
        {      
            $res=FALSE;
            $schools=$this->ApplicationObj->AccessibleSchools();
            if (!empty($schools[ $item[ "School" ]]))
            {
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for People.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        if ($this->LoginType=="Public")
        {
            $where[ "ID" ]=0;
        }
        elseif ($this->Profile=="Teacher")
        {
            $where[ "Profile_Teacher" ]=2;
        }

        return $where;
    }   
}

?>