<?php

include_once("../Application/Application.php");


include_once("Common.php");
include_once("SAdE/Unit.php");
include_once("SAdE/School.php");
include_once("SAdE/Period.php");
include_once("SAdE/Class.php");
include_once("SAdE/InfoTable.php");
include_once("SAdE/LeftMenu.php");

class SAdE extends SAdELeftMenu
{
    var $SavePath="?Action=Start";
    var $ApplicationMessages="Application.php";

    // 1: $this->ApplicationObj->OnlyTotals
    // 2: $this->ApplicationObj->AbsencesYes
    var $OnlyTotals=1;
    var $AbsencesYes=2;

    // 1: $this->ApplicationObj->Quantitative
    // 2: $this->ApplicationObj->Qualitative
    var $Quantitative=1;
    var $Qualitative=2;


    //var $PeriodicalModules=array();

    var $RealUnitsObject=NULL;

    var $PeopleObject=NULL;
    var $UnitsObject=NULL;

    var $SqlVars=array();

    var $AppProfiles=array
    (
       "SAdE" => array
       (
          "Admin",
       ),
    );

     //*
    //* function SAdE, Parameter list: $args=array()
    //*
    //* SAdE constructor.
    //*

    function SAdE($args=array())
    {
        $this->LeftMenuFile="#Setup/LeftMenu.SAdE.php";

        if (isset($_POST[ "DiscID" ])) { $_GET[ "Disc" ]=$_POST[ "DiscID" ]; }
        if (isset($_POST[ "StudentID" ])) { $_GET[ "Student" ]=$_POST[ "StudentID" ]; }

        $this->SessionsTable="Sessions";
        $this->SqlVars=array("Unit" => $this->GetGET("Unit"));
        $this->SavePath="?Unit=".$this->GetGET("Unit")."&Action=Start";

        $args=$this->ReadPHPArray($this->SetupPath."/Setup.SAdE.php",$args);
        $args[ "ValidProfiles" ]=$this->AppProfiles[ "SAdE" ];

        $this->MayCreateSessionTable=TRUE;

        parent::Application($args);
    }


    //*
    //* function InitApplication, Parameter list: 
    //*
    //* Application initializer.
    //*

    function InitApplication()
    {
        parent::InitApplication();
        touch("Logs/index.php");
        touch("tmp/index.php");
     }

    //*
    //* function PostInitSession, Parameter list: $logindata=array()
    //*
    //* Does nothing, avaliable to be overriden, for actions to do right after
    //* user session has been established.
    //*

    function PostInitSession($logindata=array())
    {
        if (count($logindata)==0) { $logindata=$this->LoginData; }
        $unit=$this->GetCookieOrGET("Unit");

        if (!empty($logindata[ "Unit" ]) && $this->GetCookie("Admin")!=1)
        {
            if ($unit!=$logindata[ "Unit" ])
            {
                print "Não permitido..."; exit();
            }
        }
    }

    //*
    //* sub ApplicationWindowTitle, Parameter list:
    //*
    //* Overwrite Application Window Title generator.
    //*
    //*

    function ApplicationWindowTitle()
    {
        $title="SAdE, Admin";

        return preg_replace
        (
           '/#ItemName/',
           "",
           preg_replace
           (
              '/#ItemsName/',
              "",
              $this->HtmlSetupHash[ "ApplicationName"  ]."@".
              $title
            )
        );
    }



    //*
    //* function HandleStart, Parameter list:
    //*
    //* Overrrides the Handlers Start Handler. Should display some basic info.
    //*

    function HandleStart()
    {
        if ($this->GetCGIVarValue("Action")=="Start")
        {
            $this->ResetCookieVars();
        }

        $this->SetCookieVars();

        $this->HtmlHead();
        $this->HtmlDocHead();

        print
            $this->H(1,"SAdE - Sistema Administrativo Escolar").
            $this->H(2,"Administração de Unidades").
            "<BR>\n";

    }




}

$application=new SAdE();

?>
