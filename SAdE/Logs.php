<?php


class Logs extends Common
{
    var $LogGETVars=array
    (
       "ModuleName","Action",
       "Unit","School","Period","Class",
       "Disc","Student","Teacher"
    );
    var $LogPOSTVars=array("Edit","Update","Transfer","Save");

    //*
    //*
    //* Constructor.
    //*

    function Logs($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->NItemsPerPage=25;
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
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Logs/',$module))
        {
            return $item;
        }

        return $item;
    }

    //*
    //* function LogEntry, Parameter list: $msgs,$level=5
    //*
    //* Log entry $msg.
    //*

    function LogEntry($msgs,$level=5)
    {
        if (is_array($msgs)) { $msgs=join("\n",$msgs); }

        $log=array
        (
           "ATime"   => time(),
           "CTime"   => time(),
           "MTime"   => time(),
           "Year"     => $this->CurrentYear(),
           "Month"    => $this->CurrentYear().sprintf("%02d",$this->CurrentMonth()),
           "Date"    => $this->TimeStamp2DateSort(),
           "Debug"   => $level,
           "Login"   => $this->ApplicationObj->LoginData[ "ID" ],
           "Profile" => $this->ApplicationObj->Profile,
           "Message" => $msgs,
           "IP"      => $_SERVER['REMOTE_ADDR'],
        );

        foreach ($this->LogGETVars as $getvar)
        {
            if (isset($_GET[ $getvar ]))
            {
                $log[ $getvar ]=$this->GetGET($getvar);
            }
        }

        foreach ($this->LogPOSTVars as $getvar)
        {
            if (isset($_POST[ $getvar ]))
            {
                $log[ "POST_".$getvar ]=$this->GetPOST($getvar);
            }
        }

        $this->MySqlInsertItem("",$log);
    }
}

?>