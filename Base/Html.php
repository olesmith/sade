<?php

include_once("../Base/File.php");
include_once("Language.php");
include_once("Time.php");
include_once("Filters.php");
include_once("Base.php");
include_once("Zip.php");
include_once("../Base/Mail.php");
include_once("Log.php");
include_once("Html/Form.php");


class Html extends HtmlForm
{
    var $URL;
    var $LatexMode=FALSE;
    var $HiddenVars=array();

    var $MessageList=array();
    var $ExtraPathVars=array();
    var $HtmlMessages="Html.php";

//*
//* function MakeHiddenHash, Parameter list: $name,$value
//*
//* Creates and args hash with hidden args and their values.
//* 
//*

function MakeHiddenHash($hash=array())
{
    $hiddens=$this->HiddenVars;

    $rhash=array();
    for ($n=0;$n<count($hiddens);$n++)
    {
        $rhash[ $hiddens[$n] ]=$this->GetCGIVarValue($hiddens[$n]);
    }

    foreach ($hash as $key => $value) { $rhash[ $key ]=$hash[ $key ]; }

    return $rhash;
}


//*
//* function MakeHiddens, Parameter list: $name,$value
//*
//* Creates the HIDDEN fields in class array HiddenVars.
//* 
//*

function MakeHiddenArgs($hash=array())
{
    $hash=$this->MakeHiddenHash($hash);
    return $this->Hash2Query($hash);
}


}


?>