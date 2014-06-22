<?php

include_once("../MySql2/Table.php");
include_once("LeftMenu.php");
include_once("Login.php");
include_once("Session.php");
include_once("SubModules.php");
include_once("Backup.php");
include_once("Modules.php");
include_once("Setup.php");
include_once("../Application/Messages.php");
include_once("Help.php");
include_once("TInterface.php");
include_once("Perms.php");
include_once("Profiles.php");
include_once("Handlers.php");


class Application extends Handlers
{
    var $CommonData=array();
    var $SplitVars=array();
    var $SearchVars=array();
    var $HtmlError=FALSE;
    var $LoginType="";
    var $Handle=TRUE;
    var $BaseDB="";
    var $DBs=array();

    var $SetupPath="System";
    var $ActionsPath="Actions";
    var $ConfigPaths=array("../Application",".");
    var $Layout=array
    (
       "Font"      => "",
       "Light"     => "#dcf7fa",
       "LightDark" => "#90a1a3",
       "Dark"      => "#464e4f",
       "White"     => "#FFFFFF",
       "Black"     => "#000000",
    );

    var $LatexClearPage="\n\\clearpage\n\n";
    var $LatexClearDoublePage="\n\\cleardoublepage\n\n";
    //var $LatexGreyRows="\\rowcolors{2}{gray!35}{}\n";
    //var $LatexWhiteRows="\\rowcolors{2}{gray!0}{}\n";
    var $LatexGreyRows="";
    var $LatexWhiteRows="";

    var $LatexFilters=array
    (
       "Unit" => array
       (
          "PreKey" => "",
          "Object" => "UnitsObject",
       ),
    );

    function RotateColors($nn=1)
    {
        $n=$this->GetGET("Colors");
        if (empty($n)) { $n=0; }

        if (empty($n)) { $n=$nn; }

        for ($m=1;$m<=$n;$m++)
        {
            $tmp=$this->Layout[ "Light" ];
            $this->Layout[ "Light" ]=$this->Layout[ "LightDark" ];
            $this->Layout[ "LightDark" ]=$this->Layout[ "Dark" ];
            $this->Layout[ "Dark" ]=$tmp;
        }
    }

    function Application($args=array())
    {
        //$this->RotateColors(2);

        $this->InitTime();
        $this->SetURL();
        $this->Hash2Object($args);

        $this->GlobalSetupDefs();
        $this->InitApplication();

        if ($this->Handle)
        {
            $this->Handle();
        }
    }

    //*
    //* function __destruct, Parameter list: 
    //*
    //* Application destructor.
    //*

    function __destruct()
    {
        if (!empty($this->Module) && method_exists($this->Module,"SendGMails"))
        {
            $this->Module->SendGMails();
        }

        $this->HtmlDocTail();
        $this->HtmlTail();

        global $Queries;
        if (is_array($Queries))
        {
            print $this->HtmlList($Queries);
        }
    }

    //*
    //* function SetLatexMode, Parameter list: 
    //*
    //* Changes some character constants to use with LatexMode=1.
    //*

    function SetLatexMode()
    {
        $this->Sigma   = '$'."\\Sigma".  '$';
        $this->Mu      = '$'."\\Mu".     '$';
        $this->Percent = "\\%";

        $this->LatexMode=TRUE;
    }


    //*
    //* function InitApplication, Parameter list: 
    //*
    //* Application initializer.
    //*

    function InitApplication()
    {
        $this->ModuleName=$this->GetGET("ModuleName");
        $this->DoClassInit("TInterface",$this->CommonData[ "Hashes" ]);

        if (isset($this->CommonData[ "Hashes" ][ "MySql" ]))
        {
            $this->DoClassInit("MySql",$this->CommonData[ "Hashes" ]);
        }

        if (isset($this->CommonData[ "Hashes" ][ "Mail" ]))
        {
            $this->DoClassInit("Mail",$this->CommonData[ "Hashes" ]);
        }

        if (isset($this->CommonData[ "Hashes" ][ "Login" ]))
        {
            $this->DoClassInit("Login",$this->CommonData[ "Hashes" ]);
        }

        if ($this->PublicInterface==1)
        {
            $this->PublicAllowed=1;
        }

        $this->ReadProfiles();
        if (isset($this->CommonData[ "Hashes" ][ "Login" ]))
        {
            $this->DoClassInit("Session",$this->CommonData[ "Hashes" ]);

            $this->DetectLoginType();
            $this->DetectProfile();
            $this->ReadPermissions();
        }
        else
        {
            $this->LoginType="Public";
            $this->Profile="Public";
        }


        $this->GlobalActions();
    }

    //*
    //* function ConfigFiles, Parameter list: $file,&$hash
    //*
    //* Walks though $this->ConfigPaths, looking for files
    //* $this->ConfigPaths/$this->SetupPath/$file.
    //*

    function ConfigFiles($rpath,$files,$paths=array())
    {
        if (!is_array($files)) { $files=array($files); }

        if (count($paths)==0) { $paths=$this->ConfigPaths; }

        $rfiles=array();
        foreach ($files as $file)
        {
            foreach ($paths as $path)
            {
                $rfile=$path."/".$rpath."/".$file;
                if (file_exists($rfile))
                {
                    array_push($rfiles,$rfile);
                }
            }
        }
 
        return $rfiles;
    }
    //*
    //* function ConfigFiles2Hash, Parameter list: $file,&$hash
    //*
    //* Walks though $this->ConfigPaths, looking for files
    //* $this->ConfigPaths/$this->SetupPath/$file.
    //* Each file is read with method ReadPHPArray(),
    //* additively added to $hash.
    //*

    function ConfigFiles2Hash($rpath,$files,&$hash,$paths=array())
    {
        if (!is_array($files)) { $files=array($files); }

        if (count($paths)==0) { $paths=$this->ConfigPaths; }

        foreach ($this->ConfigFiles($rpath,$files,$paths) as $file)
        {
            $this->ReadPHPArray($file,$hash);
        }
 
        return $hash;
    }

    //*
    //* function GetListOfProfiles, Parameter list:
    //*
    //* 
    //*

    function GetListOfProfiles()
    {
        $profiles=array
        (
           "Public" =>1,
           "Person" =>1,
        );
        foreach ($this->ValidProfiles as $profile)
        {
            $profiles[ $profile ]=1;
        }
        $profiles[ "Admin" ]=1;

        return array_keys($profiles);
    }


    //*
    //* function GlobalActions, Parameter list:
    //*
    //* 
    //*

   function GlobalSetupDefs()
    {
        $this->SetupFileDefs=array();
        $this->ConfigFiles2Hash($this->SetupPath,"Globals.Defs.php",$this->SetupFileDefs);

        $this->GlobalActions();
        $this->ReadSetupFiles();
    }



    //*
    //* function GlobalActions, Parameter list:
    //*
    //* 
    //*

    function GlobalActions()
    {
        $this->Actions=array();
        $this->ConfigFiles2Hash($this->ActionsPath,"Actions.php",$this->Actions);
        foreach (array_keys($this->Actions) as $action)
        {
            $this->AddDefaultActionKeys($this->Actions[ $action ]);
        }
    }

    //*
    //* function Profile2Application, Parameter list:
    //*
    //* Transfer current profile to $this (does allowing of actions).
    //*

    function Profile2Application()
    {
        $this->GlobalActions();

        if ($this->LoginType=="") { $this->LoginType="Public"; }
        if ($this->Profile=="") { $this->Profile="Public"; }

        $profiles=$this->Profiles[ $this->Profile ][ "Application" ];
        foreach ($profiles[ "Actions" ] as $name => $val)
        {
            if (is_array($val))
            {
                $this->Actions[ $name ][ $this->LoginType ]=$val[ "Access" ];
                foreach ($val[ "Attributes" ] as $key => $value)
                {
                    $this->Actions[ $name ][ $key ]=$value;
                }
            }
            else
            {
                $this->Actions[ $name ][ $this->LoginType ]=$val;
            }
        }
    }

    //*
    //* function PostInitSubModule, Parameter list: $obj
    //*
    //* Initializes profiles, actions and ItemData for $obj.
    //*

    function PostInitSubModule($obj)
    {
        $obj->InitProfile($obj->ModuleName);
        $obj->InitActions();
        $obj->PostInit();
    }


    //*
    //* function SetSubClassSqlTable, Parameter list: $item
    //*
    //* 
    //*

    function SubClassSqlTableName($item,$class,$name)
    {
        $obj=$class."Object";

        return preg_replace
        (
           '/_'.$name.'/',
           "_".$class,
           $this->SqlTableName()
        );
     }

    //*
    //* function UpdateTablesStructure, Parameter list: $classes
    //*
    //* Update table structures for $classes.
    //*

    function UpdateTablesStructure($classes)
    {
        foreach ($classes as $class)
        {
            $obj=$class."Object";
            $this->$obj->UpdateTableStructure();
        }
    }

    //*
    //* function LatexGreyRows, Parameter list: 
    //*
    //* Acessor for $this->LatexGreyRows.
    //*

    function LatexGreyRows()
    {
        $greyrow="";
        if ($this->GetPOST("NoGreys")!=1)
        {
            $greyrow=$this->LatexGreyRows;
        }

        return $greyrow;
    }

    //*
    //* function LatexWhiteRows, Parameter list: 
    //*
    //* Acessor for $this->LatexWhiteRows.
    //*

    function LatexWhiteRows()
    {
        $greyrow="";
        if ($this->GetPOST("NoGreys")!=1)
        {
            $greyrow=$this->LatexWhiteRows;
        }

        return $greyrow;
    }

    //*
    //* Transfers data read into $this->Unit, into $this->ApplicationObj->CompanyHash.
    //*

    function Unit2CompanyHash()
    {
        if (!empty($this->Unit))
        { 
            foreach (array_keys($this->Unit) as $key)
            {
                $this->CompanyHash[ $key ]=$this->Unit[ $key ];
            }

            $this->CompanyHash[ "Institution" ]="";
            if (!empty($this->Unit[ "Institution" ]))
            {
                $this->CompanyHash[ "Institution" ]=$this->Unit[ "Institution" ];
            }

            if (!empty($this->Unit[ "Title" ]))
            {
                $this->CompanyHash[ "Department" ]=$this->Unit[ "Title" ];
            }

            $this->CompanyHash[ "Url" ]="";
            if (!empty($this->Unit[ "WWW" ]))
            {
                $this->CompanyHash[ "Url" ]=$this->Unit[ "WWW" ];
            }
            elseif (!empty($this->Unit[ "Url" ]))
            {
                $this->CompanyHash[ "Url" ]=$this->Unit[ "Url" ];
            }

            $this->CompanyHash[ "City" ]="";
             if (!empty($this->Unit[ "City" ]))
            {
                $this->CompanyHash[ "City" ]=$this->Unit[ "City" ];
            }

            $this->CompanyHash[ "State" ]="";
            if (!empty($this->Unit[ "State" ]))
            {
                $this->CompanyHash[ "State" ]=$this->Unit[ "State" ];
            }

            $this->CompanyHash[ "ZIP" ]="";
             if (!empty($this->Unit[ "" ]))
            {
                $this->CompanyHash[ "ZIP" ]="CEP: ".$$this->Unit[ "ZIP" ];
            }
        }
    }
}

?>