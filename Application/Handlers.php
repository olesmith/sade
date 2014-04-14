<?php



class Handlers extends Profiles
{
    var $DefaultAction="Start";

    //*
    //* function Handle, Parameter list:
    //*
    //* The main handler. Everything passes through here!
    //* Dispatches an Application or a Module action. 
    //* If it's a global action, handle it here.
    //* Ex: Logon, logoff, change password, etc.
    //* For admin, the admin utilities (in left menu).
    //*

    function Handle()
    {
        $action=$this->GetCGIVarValue("Action");
        if ($action=="") { $action=$this->DefaultAction; }

        $this->ModuleName=$this->GetCGIVarValue("ModuleName");

        if (
            empty($this->ModuleName)
            &&
            isset($this->Actions[ $action ])
           )
        {
            if ($this->ActionAllowed($action))
            {
                $handler=$this->Actions[ $action ][ "Handler" ];
                if (method_exists($this,$handler))
                {
                    $this->$handler();
                }
                else { print "No handler $handler, action $action"; exit(); }
            }
            else { print "No '".$this->LoginType."' access to action $action"; exit(); }
        }
        elseif ($this->ModuleName!="")
        {
            $this->HandleModule();
        }
        else
        {
           $this->HandleStart(); 
        }



        //exit();
    }


    //*
    //* function InitHTML, Parameter list:
    //*
    //* Does the basics for initializing cookes, cgi, etc,
    //* writes http header and doc head.
    //*

    function InitHTML()
    {
        $this->SetCookieVars();

        $this->HtmlHead();
        $this->HtmlDocHead();
    }

    //*
    //* function HandleStart, Parameter list:
    //*
    //* The Start Handler. Should display some basic info.
    //*

    function HandleStart()
    {
        if ($this->GetCGIVarValue("Action")=="Start")
        {
            $this->ResetCookieVars();
        }

        $this->InitHTML();

        print
            "\n<BR>\n".
            "<TABLE BORDER=1 ALIGN='center' WIDTH='50%'><TR><TD>\n".
            $this->DIV
            (
             "Seja bem Vindo à:",
             array
             (
              "CLASS" => 'applicationtitle',
             )
            ).
            "\n<BR><BR>\n".
            $this->DIV
            (
             $this->HtmlSetupHash[ "DocTitle" ]."<BR><BR>",
             array
             (
              "CLASS" => 'applicationname',
             )
            ).
            "\n<BR><BR>\n".
            $this->DIV
            (
             "Por favor, navegue usando o menu na esquerda.",
             array
             (
              "CLASS" => 'applicationtitle',
             )
            ).
            "</TD></TR></TABLE>\n<BR>\n";
    }


    //*
    //* function Logon, Parameter list: 
    //*
    //* If authenticated, calls HandleList, else call LogonForm
    //*

    function HandleLogon()
    {
        if ($this->LoginType=="Public")
        {
            $this->LoginForm();
            exit();
        }
        else
        {
            $this->HandleStart();
        }
    }

    //*
    //* function Logon, Parameter list: 
    //*
    //* Carries out logoff, ie: Calls DoLogoff and exits.
    //*

    function HandleLogoff()
    {
        $this->DoLogoff();
        exit();
    }

    //*
    //* Presents change password form and exits.
    //*

    function HandleNewPassword()
    {
        $this->ChangePasswordForm();
        exit();
    }

    //*
    //* Handles the edit personal data form.
    //*

    function HandleMyData()
    {
        $this->ModuleName="People";
        $this->InitModule();

        $this->HtmlHead();
        $this->HtmlDocHead();

        $this->PeopleObject->HandleMyData();
        exit();
    }


    //*
    //* function HandleAdmin, Parameter list:
    //*
    //* The Admin Handler.
    //*

    function HandleAdmin()
    {
        if ($this->MayBecomeAdmin())
        {
            $this->SetCookie("Admin",1,time()+$this->CookieTTL);
            $this->HandleStart();
        }
    }

    //*
    //* function HandleNoAdmin, Parameter list:
    //*
    //* The NoAdmin Handler.
    //*

    function HandleNoAdmin()
    {
        $this->HandleStart();
        $this->SetCookie("Admin",0,time()-$this->CookieTTL);
    }

    //*
    //* function HandleSU, Parameter list:
    //*
    //* The Shift User Handler.
    //*

    function HandleSU()
    {
        $this->ShiftUserForm();
    }

    //*
    //* function HandleBackup, Parameter list:
    //*
    //* The Backup Handler.
    //*

    function HandleBackup()
    {
        $this->BackupForm();
    }

    //*
    //* function HandleLog, Parameter list:
    //*
    //* The Log Handler.
    //*

    function HandleLog()
    {
        $this->LogsTable();
    }

    //*
    //* function HandleSetup, Parameter list:
    //*
    //* The Setup Handler.
    //*

    function HandleSetup()
    {
        $this->SetupFilesForm();
    }



    //*
    //* function TransferModuleProfiles, Parameter list:
    //*
    //* Transfers profiles for all handlers.
    //*

    function TransferModuleProfiles()
    {
        foreach (array_keys($this->ModuleDependencies) as $module)
        {
            $class=$this->ApplicationClass;

            $mhash=array
            (
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
                  "Handle"          => FALSE,
            );

            if (isset($this->Period))
            {
                $mhash[ "Period" ]=$this->Period;
            }

            $object=new $class ($mhash);
            $object->InitModule($module,array(),FALSE);

            $object->Module->TransferProfiles();
        }
    }

    //*
    //* function TransferProfiles, Parameter list:
    //*
    //* Transfers profiles for all handlers.
    //*

    function TransferProfiles()
    {
        $moduleaccesses=array();
        foreach (array_keys($this->ModuleDependencies) as $module)
        {
            $access=$this->ReadPHPArray($this->ModuleProfileFile($module));

            $access=$access[ "Access" ];

            $moduleaccesses[ $module ]=array();
            foreach ($this->GetListOfProfiles() as $profile)
            {
                $moduleaccesses[ $module ][ $profile ]=$access[ $profile ];
            }
        }

        if (isset($this->DBHash[ "Mod" ]) && $this->DBHash[ "Mod" ])
        {
            $file=$this->AccessProfilesFile();
            $this->WritePHPArray($file,$moduleaccesses);

            print $this->H(4,"System Accesses written to ".$file);
        }
    }


    //*
    //* function HandleModuleSetup, Parameter list:
    //*
    //* The Module Setup Handler.
    //*

    function HandleModuleSetup()
    {
        $this->InitHTML();

        $formtable="";
        if (isset($this->DBHash[ "Mod" ]) && $this->DBHash[ "Mod" ])
        {
            $formtable=
                $this->StartForm().
                $this->H
                (
                   5,
                   "Transferir Módulos: ".
                   $this->MakeCheckBox("TransferModule").
                   $this->Button("submit","Transferir")
                ).
                $this->EndForm();
            $formtable.=
                $this->StartForm().
                $this->H
                (
                   5,
                   "Transferir Sistema: ".
                   $this->MakeCheckBox("Transfer").
                   $this->Button("submit","Transferir")
                ).
                $this->EndForm();
        }
        
        if ($this->GetPOST("TransferModule")=='on')
        {
            $this->TransferModuleProfiles();
        }

        if ($this->GetPOST("Transfer")=='on')
        {
            $this->TransferProfiles();
        }

        print
            $this->H(2,"Permissions and Profiles").
            $this->HtmlTable
            (
               "",
               array
               (
                  array($formtable)
               )
            );
    }
}

?>