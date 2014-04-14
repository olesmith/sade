<?php

include_once("Fields/Time.php");
include_once("Fields/File.php");
include_once("Fields/Select.php");
include_once("Fields/Input.php");
include_once("Fields/Show.php");
include_once("Fields/Field.php");

class Fields extends FieldFields
{
    //*
    //* Variables of Fields class:
    //*


    //*
    //* Returns true if $data is an ENUM.
    //*

    function DataIsEnum($data)
    {
        if (isset($this->ItemData[ $data ]) && preg_match('/ENUM/',$this->ItemData[ $data ][ "Sql" ]))
        {
            if ($this->ItemData[ $data ][ "AltTable" ])
            {
                return FALSE;
            }
            return TRUE;
        }

        return FALSE;
    }

    //*
    //*
    //* Returns true if $data is an Sql INT type.
    //*

    function DataIsIntType($data)
    {
        if (isset($this->ItemData[ $data ]) && $this->ItemData[ $data ][ "Sql" ]=="INT")
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    //*
    //*
    //* Returns true if $data is an Sql derived type.
    //* That is, it's value is an index in $this->ItemData[ $data ][ "SqlTable" ]
    //*

    function DataIsSqlType($data)
    {
        if (
              isset($this->ItemData[ $data ][ "SqlTable" ]) 
              &&
              $this->ItemData[ $data ][ "SqlTable" ]!=""
           )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    //*
    //*
    //* Returns true if $data is a derived type.
    //*

    function DataIsDerived($data)
    {
        if (
            isset($this->ItemData[ $data ]) && 
            (
               $this->ItemData[ $data ][ "SqlDerivedNamer" ]!="" ||
               $this->ItemData[ $data ][ "Derived" ]!=""
            )
           )
        {
            if ($this->ItemData[ $data ][ "AltTable" ]!="")
            {
                return FALSE;
            }
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

   

    //*
    //* function MakePRNField, Parameter list: $data,$item,$edit=1
    //*
    //* 
    //*

    function MakePRNField($data,$item,$edit=1)
    {
        $prn="";
        if (isset($item[ $data ]))
        {
            $prn=$item[ $data ];
            if (preg_match('/^(\d\d\d)\.?(\d\d\d)\.?(\d\d\d)-?(\d\d)$/',$item[ $data ],$matches))
            {
                $prn=$matches[1].".".$matches[2].".".$matches[3]."-".$matches[4];
            }
        }

        if ($edit==0)
        {
            return $prn;
        }
        else
        {
            return $this->MakeInput($data,$prn,15);
        }
    }

    



     //*
    //*
    //* function SystemLink, Parameter list: $url,$text,$title="",$dest="",$options=array()
    //*
    //* Generate link within the system, preserving internal URL parameters.
    //*

    function SystemLink($url,$text,$title="",$dest="",$options=array())
    {
        $rurl=$this->ScriptQueryHash();
        foreach ($url as $key => $value)
        {
            $rurl[ $key ]=$value;
        }

        if (!empty($dest))
        {
            $options[  "TARGET" ]=$dest;
        }

        return $this->Href
        (
           "?".$this->Hash2Query($rurl),
           $text,
           $title,
           $options[  "TARGET" ],
           "",
           FALSE,
           $options
         );
    }
}

?>