<?php

include_once("CSS.php");
include_once("Tags.php");
include_once("List.php");
include_once("Table.php");
include_once("Href.php");
include_once("Input.php");

global $NForms,$NFields;
$NForms=0;
$NFields=0;


class HtmlForm extends HtmlInput
{
    //*
    //* function AddCommonArgs2Hash, Parameter list: &$args
    //*
    //* Adds hash entries to $args, according to $this->URL_CommonArgs.
    //* 
    //*

    function AddCommonArgs2Hash(&$args)
    {
        if ($this->URL_CommonArgs)
        {
            $args=$this->Query2Hash($this->URL_CommonArgs,$args);
        }
    }


    //*
    //* function StartForm, Parameter list: $action,$method="post",$enctype=0
    //*
    //* Creates leading part of a FORM.
    //* 
    //*

    function StartForm($action="",$method="post",$enctype=0,$options=array())
    {
        global $NForms;
        $NForms++;

        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);
        $query=$this->Hash2Query($args);

        $this->AddCommonArgs2Hash($args);

        if (preg_match('/(.*)\?(.*)/',$action,$matches))
        {
            $aargs=$matches[2];
            $action=$matches[1];
            $args=$this->Query2Hash($aargs,$args);
        }

        if (method_exists($this,"GroupDataCGIVar"))
        {
            unset($args[ $this->GroupDataCGIVar() ]);
        }

        $options[ "ID" ]="Form".$NForms;
        $options[ "METHOD" ]=$method;
        $options[ "ACTION" ]="?".$this->Hash2Query($args);
        $options[ "ENCTYPE" ]="multipart/form-data";
        if ($enctype!=0)
        {
            $options[ "ENCTYPE" ]="application/x-www-form-urlencoded";
        }

        return $this->HtmlTag("FORM","",$options)."\n";
    }


    //*
    //* function EndForm, Parameter list:
    //*
    //* Creates trailing part of a FORM.
    //* 
    //*

    function EndForm($nohiddens=TRUE)
    {
        $hiddens="";
        if ($nohiddens)
        {
            $hiddens=$this->MakeHiddenFields();
        }

        return 
            $hiddens.
            $this->HtmlTag("/FORM")."\n";
    }


    //*
    //* function HtmlForm, Parameter list: $contents,$edit=0,$update=array(),$action="",$method="post",$enctype=0,$options=array(),$hiddens=array()
    //*
    //* Creates FORM with $contents.
    //* 
    //*

    function HtmlForm($contents,$edit=0,$update=array(),$action="",$method="post",$enctype=0,$options=array(),$hiddens=array())
    {
        $html="";
        if ($edit==1)
        {
            if (!empty($update))
            {
                $updatevar=$update[ "CGI" ];
                $updatemethod=$update[ "Method" ];
                $args=$update[ "Args" ];

                if (!empty($updatemethod))
                {
                    $this->$updatemethod($args);
                }
            }
            else
            {
                $update[ "CGI" ]="Update";
            }


            $html.=
                $this->StartForm($action,$method,$enctype,$options).
                $this->Buttons();
        }

        $html.=$contents;

        if ($edit==1)
        {
            $html.=
                $this->MakeHidden($update[ "CGI" ],1).
                $this->Buttons().
                $this->EndForm();
        }

        return $html;
    }
}


?>