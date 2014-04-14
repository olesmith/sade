<?php


class Units extends Common
{

    //*
    //* Variables of Units class:

    var $HtmlTitleVars=array();
    var $HtmlIconVars=array();
    var $LatexTitleVars=array();
    var $LatexIconVars=array();

    //*
    //* function Units, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function Units($args=array())
   {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Name");
        $this->Sort=array("Name");
        $this->UploadFilesHidden=FALSE;

        $this->ItemData=$this->ReadPHPArray("System/Units/Data.Titles.php",$this->ItemData);

        $datas=array_keys($this->ItemData);
        $this->HtmlTitleVars=preg_grep('/^HtmlTitle/',$datas);
        $this->HtmlIconVars=preg_grep('/^HtmlIcon/',$datas);

        $this->LatexTitleVars=preg_grep('/^LatexTitle/',$datas);
        $this->LatexIconVars=preg_grep('/^LatexIcon/',$datas);

   }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Actions[ "Show" ][ "AccessMethod" ]="CheckEditAccess";
        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";
        $this->ItemData[ "State" ][ "Values" ]=$this->ApplicationObj->States;
   }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ItemDataGroups=$this->ReadPHPArray("System/Units/Groups.Titles.php",$this->ItemDataGroups);
        $this->ItemDataSGroups=$this->ReadPHPArray("System/Units/SGroups.Titles.php",$this->ItemDataSGroups);
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Postprocesses and returns $item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Units")
        {
            return $item;
        }

        if (!isset($item[ "ID" ]) || $item[ "ID" ]==0) { return $item; }

        return $item;
    }


    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for Units.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        if ($this->Profile=="Person")
        {
            $wheres[ "ID" ]=$this->LoginData[ "Unit" ];
        }

        return $where;
    }

    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited. Admin may -
    //* and Person, if LoginData[ "ID" ]==$item[ "ID" ]
    //*

    function CheckEditAccess($item)
    {
        if (empty($item[ "ID" ])) { return FALSE; }

        $res=FALSE;
        if (preg_match('/^Admin$/',$this->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Secretary)$/',$this->Profile))
        {
            if (!empty($item[ "ID" ]) && $item[ "ID" ]==$this->LoginData[ "Unit" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function TestUnitMailAccount, Parameter list: 
    //*
    //* Tests a units mail account, used by the system when sending mail.
    //# Shoulkd be registered as a trigger function for "AdmEmail" and
    //* "AdmEmailPassword". Just tests if we may send it a mail,
    //* using the same credentials on GMail.
    //*

    function TestUnitMailAccount($item,$data,$newvalue)
    {
        $item[ $data ]=$newvalue;
        $this->ApplicationObj->MailInfo[ $data ]=$newvalue;

        $res=$this->SendGMail
        (
           $item[ "AdmEmail" ],
           "Test Email",
           "Test Email"
        );

        if ($res)
        {
            print $this->H(3,$item[ "AdmEmail" ]." OK!!");
        }
        else
        {
            print $this->H(4,$item[ "AdmEmail" ]." NOT OK!!");
        }

        return $item;
    }

    //*
    //* Handles the edit my unit data form.
    //*

    function HandleMyUnit()
    {
        if ($this->LoginType!="Person")
        {
            $this->HandleUnit();
            exit();
        }

        $this->ReadItem($this->LoginData[ "Unit" ]);
        $this->EditForm("Editar Dados da Minha Entidade",array(),1,FALSE,array(),TRUE,array(),"?Action=MyUnit");
    }
}

?>