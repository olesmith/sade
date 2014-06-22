<?php

include_once("../Application/TInterface/CSS.php");
include_once("../Application/TInterface/WindowTitle.php");
include_once("../Application/TInterface/HTMLHead.php");
include_once("../Application/TInterface/Support.php");
include_once("../Application/TInterface/Sponsors.php");
include_once("../Application/TInterface/Head.php");
include_once("../Application/TInterface/Tail.php");
include_once("../Application/TInterface/Icons.php");
include_once("../Application/TInterface/Messages.php");

global $HtmlMessages; //global and common for all classes
$HtmlMessages=array();

class TInterface extends TInterfaceMessages
{
    var $CSSFile="../MySql2/wooid.css";
    var $HtmlSetupHash,$CompanyHash; 
    var $Modules=array();
    var $PreTextMethod="";
    var $InterfacePeriods=array();
    var $NoTail=1;
    var $HeadersSend=0;
    var $DocHeadSend=0;
    var $HeadSend=0;
    var $HTML=FALSE;
    var $TInterfaceDataMessages="TInterface.php";

    var $HtmlStatusMessages=array();
    var $HtmlStatus=array();
    var $EmailMessage=array();
    var $TInterfaceTitles=array();
    var $TInterfaceLatexTitles=array();
    var $TInterfaceIcons=array();
    var $TInterfaceLatexIcons=array();

    //*
    //* sub InitTInterfaceTitles, Parameter list:
    //*
    //* Takes default titles from CompanyHash.
    //*
    //*

    function InitTInterfaceTitles()
    {
        $this->TInterfaceTitles=array
        (
           $this->CompanyHash[ "Institution" ],
           $this->CompanyHash[ "Department" ],
           $this->CompanyHash[ "Address" ],
           $this->CompanyHash[ "Area" ].", ".
           $this->CompanyHash[ "City" ]."-".
           $this->CompanyHash[ "State" ].", CEP: ".
           $this->CompanyHash[ "ZIP" ],
           $this->CompanyHash[ "Url" ]." - ".
           $this->CompanyHash[ "Phone" ]." - ".
           $this->CompanyHash[ "Fax" ]." - ".
           $this->CompanyHash[ "Email" ],
        );

        $this->TInterfaceLatexTitles=$this->TInterfaceTitles;

        $this->TInterfaceIcons=array
        (
           1 => array
           (
              "Icon"   => $this->CompanyHash[ "HtmlIcon1" ],
              "Height" => "",
              "Width"  => "",
           ),
           2 => array
           (
              "Icon"   => $this->CompanyHash[ "HtmlIcon2" ],
              "Height" => "",
              "Width"  => "",
           ),
        );

        $this->TInterfaceLatexIcons=array
        (
           1 => array
           (
              "Icon"   => $this->CompanyHash[ "LatexIcon1" ],
              "Height" => "",
              "Width"  => "",
           ),
           2 => array
           (
              "Icon"   => $this->CompanyHash[ "LatexIcon2" ],
              "Height" => "",
              "Width"  => "",
           ),
        );

    }


    //*
    //* sub InitTInterface, Parameter list:
    //*
    //* Intializes TInterface setup.
    //*
    //*

    function InitTInterface()
    {
        $this->HtmlSetupHash=$this->ReadPHPArray($this->SetupPath."/Defs/Html.Data.php");
        $this->CompanyHash=$this->ReadPHPArray($this->SetupPath."/Defs/Company.Data.php");
 
        $this->InitTInterfaceTitles();
        if ($this->HtmlSetupHash[ "CharSet" ]=="")
        {
            $this->HtmlSetupHash[ "CharSet"  ]="utf-8";
        }
        if ($this->HtmlSetupHash[ "WindowTitle" ]=="")
        {
            $this->HtmlSetupHash[ "WindowTitle"  ]="Yes I am a Mother Nature Son...)";
        }
        if ($this->HtmlSetupHash[ "DocTitle" ]=="")
        {
            $this->HtmlSetupHash[ "DocTitle"  ]="Please give me a title (HtmlSetupHash->DocTitle)";
        }
        if ($this->HtmlSetupHash[ "Author" ]=="")
        {
            $this->HtmlSetupHash[ "Author"  ]="Prof. Dr. Ole Peter Smith, IME, UFG, ole'at'mat'dot'ufg'dot'br";
        }

        $this->ApplicationName=$this->HtmlSetupHash[ "ApplicationName"  ];
    }

    //*
    //* sub ThanksTable, Parameter list:
    //*
    //* Generates thanks table.
    //*
    //*

    function ThanksTable()
    {
        $table=array();
        $this->ConfigFiles2Hash($this->SetupPath,"Thanks.php",$table);

        if (count($table)>0)
        {
            array_unshift($table,array($this->U("Collaborators (in alfabetical order):")));
        }

        return
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center')
            );
    }

    //*
    //* sub Phrase, Parameter list:
    //*
    //* Generates our phrase...
    //*
    //*

    function Phrase()
    {
        return $this->DIV
        (
           "Life sure is a Mystery to be Lived<BR>\n".
           "Not a Problem to be Solved<BR>\n",
           array("CLASS" => 'phrase')
        );
    }   
}
?>