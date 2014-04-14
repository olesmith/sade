<?php

class Modules extends Backup
{
    var $AllowedModules=array();
    var $Access=array();
    var $CurrentDBName="";
    var $ModuleLevel=0;
    var $ModuleName="";
    var $ModuleFile="";
    var $Module=NULL;
    var $Modules=array();
    var $MySqlActions=TRUE;
    var $DefaultAction="Search";

    var $LoadMTime,$ExecMTime;
    var $SavePath;
   

    //*
    //* function InitModules, Parameter list: $hash=array()
    //*
    //* Initializes module vars, as in $hash.
    //*

    function InitModules($hash=array())
    {
        $this->Hash2Object($hash);
        $this->ModuleFile=$this->ModuleName;
    }


    //*
    //* function HasModuleAccess, Parameter list: $module=""
    //*
    //* Checks if we have  module access - returns TRUE/FALSE.
    //* Uses $profiledef[ $module ][ "Access" ] to assess if allowed.
    //* If $module is empty or not given, uses $this->ModuleName as module.
    //*

    function HasModuleAccess($module="")
    {
        if ($module=="") { $module=$this->ModuleName; }

        $profiledef=$this->GetProfileDef();

        $file=$this->ModuleProfileFile($module);
        if (!file_exists($file))
        {
            die("Application::Modules::HasModuleAccess: No such file ".$file);
        }
        $this->Access=$this->ReadPHPArray($file);
        $this->Access=$this->Access[ "Access" ];

        if (
            (
             isset($this->Access[ $this->LoginType ])
             &&
             $this->Access[ $this->LoginType ]>0
            )
            ||
            (
             isset($this->Access[ $this->Profile ])
             &&
             $this->Access[ $this->Profile ]>0
            )
           )
        {
            return TRUE;
        }

        return FALSE;
    }


    //*
    //* function RequireModuleAccess, Parameter list: $module=""
    //*
    //* Requires module access - exits if not.
    //*

    function RequireModuleAccess($module="")
    {
        if ($module!="") { $this->ModuleName=$module; }
        if ($this->HasModuleAccess($module))
        {
            if ($this->Module && method_exists($this->Module,"RequireModuleAccess"))
            { 
                $this->Module->RequireModuleAccess();
            }

            return;
        }

        if (!empty($this->SavePath))
        {
            header( 'Location: '.$this->SavePath);
        }
        else
        {
            print "Aplication::Modules::RequireModuleAccess: No module access: $module ".$this->LoginType." - exiting";
        }
        exit();  
    }


    //*
    //* function LoadModule, Parameter list: $module=""
    //*
    //* Loads module - no updating to $this!
    //*

    function LoadModule($module,$args=array(),$initdbtable=TRUE)
    {
        $file=$this->SubModulesVars[ $module ][ "SqlFile" ];

        //Load module file
        include_once("./".$file);

         //We must have access to this table/module
        $this->RequireModuleAccess($module);

        $mhash=array
        (
           "ApplicationObj"  => $this,
           "ReadOnly"        => $this->ReadOnly,
           "DBHash"          => $this->DBHash,
           "LoginType"       => $this->LoginType,
           "LoginData"       => $this->LoginData,
           "LoginID"         => $this->LoginID,
           "AuthHash"        => $this->AuthHash,
           "ModuleName"      => $module,
           "SqlTable"        => $this->SqlTable,
           "SqlTableVars"    => $this->SqlTableVars,
           "DefaultAction"   => $this->DefaultAction,
           "DefaultProfile"  => $this->DefaultProfile,
           "Profile"         => $this->Profile,
           "ModuleLevel"     => 1,
           "CompanyHash"     => $this->CompanyHash,
           "MailInfo"        => $this->MailInfo,
           "URL_CommonArgs"  => $this->URL_CommonArgs,
           "MySqlActions"    => $this->MySqlActions,
         );

        if (isset($this->Period))
        {
            $mhash[ "Period" ]=$this->Period;
        }

        foreach ($args as $key => $value)
        {
            $mhash[ $key ]=$value;
        }

        //Create MySql2 object!!
        $newobj=new $module ($mhash);

        $this->Module=$newobj;
        $newobj->InitTime();

        $modulelocation=$module."Object";
        $this->$modulelocation=$newobj;

        $this->Modules[ $module ]=$newobj;

        $this->ReadModuleSetup($newobj);
        foreach ($this->SqlTableVars as $id => $var)
        {
            $newobj->$var=$this->$var;
        }

        $newobj->InitProfile();
        if (file_exists($this->ModuleDataFile($module)))
        {
            $newobj->InitData($initdbtable); //TRUE to update DB cols
        }

        if ($this->MySqlActions)
        {
            $newobj->InitActions();
        }

        if (file_exists($this->ModuleLatexDataFile()))
        {
            $newobj->InitLatexData();
        }

        $this->SetModulePermsSqlWhere($module,$newobj);

        $this->Modules[ $modulelocation ]=$newobj;


        return $newobj;
    }

    //*
    //* function PostInit, Parameter list: 
    //*
    //* Container method, for adding last hour application post inits.
    //*

    function PostInit()
    {
    }

    //*
    //* function InitModule, Parameter list: $module=""
    //*
    //* Calls module's handler.
    //*

    function InitModule($mod="",$args=array(),$initdbtable=TRUE)
    {
        if ($mod!="") { $this->ModuleName=$mod; }
        if ($this->Module) { return; }

        if (empty($this->ModuleFile)) { $this->ModuleFile=$this->ModuleName; }
        if (preg_grep('/^'.$this->ModuleName.'$/',$this->AllowedModules))
        {
            $this->LoadMTime=time();

            $this->Module=$this->LoadModule($this->ModuleName,$args,$initdbtable);
            if (file_exists($this->ModuleDataFile($this->ModuleName)))
            {
               $this->LoadSubModules();
            }

            if (method_exists($this->Module,"PostInit"))
            {
                $this->Module->PostInit();
            }

            $this->PostInit();
            $this->ModuleName=$this->Module->ModuleName;
        }
        else
        {
            print $this->ModuleName.": Permission denied - bye-bye";
        }
    }

    //*
    //* function Handle, Parameter list: $module
    //*
    //* Calls module's handler.
    //*

    function HandleModule($module="")
    {
        $this->InitModule($module);

        $this->ExecMTime=time();
        if ($this->Module)
        {
            $this->Module->InitSearch();

            $this->Module->LoginType=$this->LoginType;

            $cookies=array();
            if (isset($this->Module->CGIArgs))
            {
                $cookies=array_keys($this->Module->CGIArgs);
            }

            $this->SetCookieVars($cookies);
            $this->Module->Handle=TRUE; //bug - SetCookieVars changes Handle??
            $this->Module->AddSearchVars2Cookies();
            $this->Module->Handle=TRUE;
            $this->Module->SetCookieVars();

            $this->Module->Handle();
        }

        $this->ExecMTime=time()-$this->ExecMTime;
    }


    //*
    //* function HasMenuAccess, Parameter list: $menu
    //*
    //* Checks if current user has access to menuitem $menu
    //*

    function HasMenuAccess($menudef)
    {
        $access=FALSE;
        if ($menudef[ $this->LoginType ]!=0)
        {
            $access=TRUE;
        }
        elseif ($menudef[ "ConditionalAdmin" ]==1 && $this->MayBecomeAdmin())
        {
            $access=TRUE;
        }

        return $access;
    }

    //*
    //* function ReadModuleSetup, Parameter list: $moduleobj
    //*
    //* Reads module specific setup.
    //*

    function ReadModuleSetup($moduleobj)
    {
        $setupdefs=$this->ReadPHPArray($this->SetupPath."/Modules.Defs.php");
        $setupdefs=$this->ReadPHPArray
        (
           $moduleobj->SetupDataPath()."Module.Defs.php",
           $setupdefs
        );

        $this->ReadSetupFiles($setupdefs,$moduleobj);

        if (isset($this->Period))
        {
            $moduleobj->Period=$this->Period;
        }
    }

}
?>