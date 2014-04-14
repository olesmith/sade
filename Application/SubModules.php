<?php

class SubModules extends Session
{

    //*
    //* function LoadSubModule, Parameter list: $submodule,$initstructure=FALSE
    //*
    //* Load submodule corresponding to $submodule.
    //* Include and create; and do minimal initilizing,
    //*

    function LoadSubModule($submodule,$initstructure=FALSE)
    {
        $file=$this->SubModulesVars[ $submodule ][ "SqlFile" ];
        $class=$this->SubModulesVars[ $submodule ][ "SqlClass" ];
        $object=$this->SubModulesVars[ $submodule ][ "SqlObject" ];
        $table=$this->SubModulesVars[ $submodule ][ "SqlTable" ];

        //Already loaded, just return
        if (isset($this->$object) || $class==$this->ModuleName) { return $object; }

        include_once($file);
        $this->$object=new $class
        (
           array
           (
              "ApplicationObj" => $this,
              "ModuleObj"      => $this->Module,
              "DBHash"         => $this->DBHash,
              "LoginType"      => $this->LoginType,
              "LoginData"      => $this->LoginData,
              "AuthHash"       => $this->AuthHash,
              "ModuleName"     => $class,
              "SqlTable"       => $table,
              "SqlTableVars"   => $this->SqlTableVars,
              "DefaultAction"  => "Search",
              "Profile"        => $this->Profile,
              "ModuleLevel"    => 2,
           )
        );

        $this->Module->$object=$this->$object;

        $this->Modules[ $object ]=$this->$object;
 
        $this->ReadSubModuleSetup($this->$object);
        $this->$object->SqlTable=$table;

        foreach ($this->SqlTableVars as $id => $var)
        {
            if (isset($this->$var))
            {
                $this->$object->$var=$this->$var;
            }
        }

        $this->$object->InitProfile();
        $this->$object->InitData($initstructure);
        $this->Module->$object=$this->$object;

        $this->SetModulePermsSqlWhere($class,$this->$object);

        $this->$object->SqlTable=$table;
        $this->$object->ApplicationObj=$this;

        return $object;
    }


    //*
    //* function ReadSubModuleSetup, Parameter list: $data,$module
    //*
    //* Reads module specific setup.
    //*

    function ReadSubModuleSetup($module)
    {
        $setupdefs=$this->ReadPHPArray($this->SetupPath."/Globals.Defs.php");
        $setupdefs=$this->ReadPHPArray($this->SetupPath."/Modules.Defs.php",$setupdefs);
        if (empty($module->ModuleName))
            {
                //   var_dump($this->SubModulesVars);
            }
        $object=$this->SubModulesVars[ $module->ModuleName ][ "SqlObject" ];
        $setupdefs=$this->ReadPHPArray
        (
           $this->$object->SetupDataPath()."Module.Defs.php",
           $setupdefs
        );

        $smod=$this->$object->ModuleName;
        $this->ReadSetupFiles($setupdefs,$this->$object,FALSE);
        $this->$object->ModuleName=$smod;
    }


    //*
    //* function LoadSubModules, Parameter list:
    //*
    //* Detect submodules, include and create them; finally do a minimal initilizing,
    //*

    function LoadSubModules()
    {
        $this->SubModules=array();
        $rsubmodules=$this->ModuleDependencies[ $this->ModuleName ];

        while (count($rsubmodules)>0)
        {
            $rrsubmodules=array();
            foreach ($rsubmodules as $id => $module)
            {
               if (!preg_grep('/^'.$module.'$/',$this->SubModules))
                {
                    if (is_array($this->ModuleDependencies[ $module ]))
                    {
                        foreach ($this->ModuleDependencies[ $module ] as $mid => $mod)
                        {
                            array_push($rrsubmodules,$mod);
                        }
                    }

                    array_push($this->SubModules,$module);
                }
            }

            $rsubmodules=$rrsubmodules;
        }

        foreach ($this->SubModules as $id => $module)
        {
            $obj=$this->LoadSubModule($module);
        }
    }
}
?>