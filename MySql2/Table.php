<?php

include_once("../Base/CGI.php");
include_once("Profile.php");
include_once("Actions.php");
include_once("MySql.php");
include_once("Access.php");
include_once("Data.php");
include_once("HashesData.php");
include_once("DataGroups.php");
include_once("Enums.php");
include_once("SubItems.php");
include_once("Fields.php");
include_once("Item.php");
include_once("Items.php");
include_once("Search.php");
include_once("Sort.php");
include_once("Import.php");
include_once("Export.php");
include_once("Menues.php");

class Table extends Menues
{

    //*
    //* Variables of Table class:

    var $Handle=FALSE;
    var $ApplicationObj=NULL;
    var $ReadOnly=FALSE;
    var $SubModules=array();
    var $ModuleLevel=0;
    var $SetupData=array();
    var $GlobalData=array();
    var $Action="Search";
    var $NoHandle=0;
    var $NoRedirect=0;
    var $ListMessage=NULL;
    var $PreTextMethod=NULL;
    var $Level=0;
    var $LoginID=0;
    var $SplitVars=array();
    var $HtmlStatusMessages=array();
    var $HtmlStatus=array();
    var $DefaultAction="Search";
    var $AddSearchVarsToDataList=FALSE;
    var $IDGETVar=FALSE;

    var $NAdded=0;
    var $NUpdated=0;

    //*
    //* function Table, Parameter list: 
    //*
    //* Table constructor.
    //*

    function Table($hash=array())
    {
        $this->InitBase($hash);
    }

    //*
    //* function AddOrUpdate, Parameter list: $table,$where,&$item,$namekey="ID"
    //*
    //* Testt whether $item should be added or updated:
    //* If $this->SelectUniqueHash() returns an empty set, adds -
    //* Otherwise updates.
    //* 

    function AddOrUpdate($table,$where,&$item,$namekey="ID")
    {
        $res=parent::AddOrUpdate($table,$where,$item,$namekey);

        if ($res==1)
        {
            $msg="Added Item: ".$item[ $namekey ]." to ".$this->SqlTableName($table);
            $this->NAdded++;
        }
        elseif ($res==2)
        {
            $msg="Updated Item: ".$item[ $namekey ]." in ".$this->SqlTableName($table);
            $this->NUpdated++;
        }
        else
        {
            $msg="ERROR Item: ".$item[ $namekey ]." in ".$this->SqlTableName($table);
        }

        return $msg;

    }

    //*
    //* function AddHtmlStatusMessage, Parameter list: $msg
    //*
    //* Adds msg to list of status messages.
    //*

    function AddHtmlStatusMessage($msg)
    {
        if (!is_array($this->HtmlStatus) && !empty($this->HtmlStatus))
        {
            $this->HtmlStatus=array($this->HtmlStatus);
        }

        array_push($this->HtmlStatus,$msg);
    }

    //*
    //* function GetRealNameKey, Parameter list: $hash,$key="Name"
    //*
    //* Calls base method of same name, and filters
    //* over #ItemName and #ItemsName
    //*

    function GetRealNameKey($hash,$key="Name")
    {
        $value=parent::GetRealNameKey($hash,$key);

        $value=preg_replace('/#ItemsName/',$this->ItemsName,$value);
        $value=preg_replace('/#ItemName/',$this->ItemName,$value);

        return $value;
    }

    //*
    //* function ApplicationWindowTitle, Parameter list: 
    //*
    //* Returns module specific part of the application window title. 
    //* Supposed to be overwritten!
    //*

    function ApplicationWindowTitle()
    {
        $title=$this->ItemsName;
        if (!empty($this->Action) && !empty($this->Actions[ $this->Action ]))
        {
            $title.=" ".$this->Actions[ $this->Action ][ "Name" ];
        }

        return $title."-&gt;";
    }


    //*
    //* function VerifyPRN, Parameter list: $item
    //*
    //* Verifies brasilian PRN, rejects if invalid. Used as TriggerFunction for PRN.
    //*

    function VerifyPRN($item,$data,$newvalue)
    {
        $prn=preg_replace('/[\.-]/',"",$newvalue);

        $regexs=array();
        for ($n=1;$n<=9;$n++)
        {
            $regex="";
            for ($m=1;$m<=11;$m++) { $regex.=$n; }

            array_push($regexs,$regex);
        }

        if (
              strlen($prn)!=11
              ||
              preg_match('/('.join("|",$regexs).')/',$prn)
           )
        {
            print $this->H(4,"CPF: '".$prn."' inválido: 11 Dígitos!!");
            return $item;
        }
        else
        { 
            if (!$this->TestPRN($prn))
            {
           
                print $this->H(4,"CPF: '".$prn."' inválido!");

                return $item;
            }

            $item[ "PRN" ]=$prn;
            return $item;
        }
    }


    //*
    //* function StartForm, Parameter list: $action="",$method="post",$enctype=0
    //*
    //* Starts form with ModuleName GET variable set.
    //*

    function StartForm($href="",$method="post",$enctype=0,$options=array())
    {
        $matches=preg_split('/\?/',$href);
        $script="";
        $argstring="";

        if (isset($matches[0])) { $script=$matches[0]; }
        if (isset($matches[1])) { $argstring=$matches[1]; }

        $rargs=array();
        $rargs[ "ModuleName" ]=$this->ModuleName;

        $args=$this->Query2Hash($argstring,$rargs);

        return parent::StartForm("?".$this->Hash2Query($args),$method,$enctype);
    }

    //*
    //* function Href, Parameter list: $href,$name="",$title="",$target="",$class="",$noqueryargs=0,$options=array()
    //*
    //* Creates HREF with ModuleName GET variable set.
    //*

    function Href($href,$name="",$title="",$target="",$class="",$noqueryargs=0,$options=array())
    {
        $matches=preg_split('/\?/',$href);
        $script="";
        $argstring="";
        if (count($matches)>0) { $script=$matches[0]; }
        if (count($matches)>1) { $argstring=$matches[1]; }

        $rargs=array();
        //$rargs[ "ModuleName" ]=$this->ModuleName;
        $args=$this->Query2Hash($argstring,$rargs);

        return parent::Href
        (
           $script."?".$this->Hash2Query($args),
           $name,$title,$target,$class,$noqueryargs,$options
        );
    }


    //*
    //* function InitTable, Parameter list: $hash=array()
    //*
    //* Table initiailizer. Pt. does nothing.
    //*

    function InitTable($hash=array())
    {
    }

    //*
    //* function InitializeSetup, Parameter list: $module="",$file="Setup.php",$path="Setup",$globalfile="Globals.php",$globalpath=""
    //*
    //* Initializes setup. Reads SetupData, GlobalData, Item Data and Item Groups.
    //* Finally, initializes Modules.
    //*

    function InitializeSetup($module="",$file="Setup.php",$path="Setup",$globalfile="Globals.php",$globalpath="")
    {
        if ($globalpath=="") { $globalpath=$path; }

        $this->ReadSetupData($module,$file,$path);
        $this->ReadGlobalData($globalfile,$globalpath);
        $this->ReadItemDatasAndGroups($module);
        $this->InitModules($module);
    }

    //*
    //* function ReadSetupData, Parameter list: $module="",$file="Setup.php",$path="Setup"
    //*
    //* Reads setup data from $path/$file, then calls InitBase on this->SetupData[ "Vars" ]
    //* and $this->SetupData[ "Vars_".$module ].
    //*

    function ReadSetupData($module="",$file="Setup.php",$path="Setup")
    {
        $rfile=$path."/".$file;
        $this->SetupData=$this->ReadPHPArray($rfile);;
        $this->InitBase($this->SetupData[ "Vars" ]);
        $this->InitBase($this->SetupData[ "Vars_".$module ]);
    }


    //*
    //* function ReadGlobalData, Parameter list: $file="Globals.php",$path="Setup"
    //*
    //* Reads globals data from $path."/".$file, and then InitBase on $this->GlobalData.
    //*

   function ReadGlobalData($file="Globals.php",$path="Setup")
    {
        $rfile=$path."/".$file;
        $this->GlobalData=$this->ReadPHPArray($rfile);
        $this->InitBase($this->GlobalData);
    }

    //*
    //* function ReadItemDatasAndGroups, Parameter list: $module=""
    //*
    //* Reads Item Data, Groups and SGroups), in $module/_type.php.
    //*

    function ReadItemDatasAndGroups($module="")
    {
        if ($module=="") { $module=$this->ModuleName; }

         if (is_file($module."/Datas.php"))
        {
            $this->ItemData=$this->ReadPHPArray($module."/Datas.php",$this->ItemData);
        }

        if (is_file($module."/Groups.php"))
        {

            $this->ItemDataGroups=$this->ReadPHPArray($module."/Groups.php",$this->ItemDataGroups);
        }


        if (is_file($modulename."/Groups.Common.php"))
        {
            $this->ItemDataGroupsCommon=$this->ReadPHPArray($modulename."/Groups.Common.php");
        }

        if (is_file($module."/SGroups.php"))
        {
            $this->ItemDataSGroups=$this->ReadPHPArray($module."/SGroups.php");
        }
        if (is_file($modulename."/SGroups.Common.php"))
        {
            $this->ItemDataSGroupsCommon=$this->ReadPHPArray($modulename."/SGroups.Common.php");
        }
    }

    //*
    //* function InitModules, Parameter list: $module=""
    //*
    //* Reads Latex and Search data from $module."/Latex.Data.php" resp. $module."/Search.Data.php".
    //* Then calls Init on these, and sets $this->LeftMenu.
    //*

    function InitModules($module="")
    {
        if ($module=="") { $module=$this->ModuleName; }

        $inithash=array
        (
            "Latex"  => $module."/Latex.Data.php",
            "Search" => $module."/Search.Data.php",
         );

        foreach ($this->SetupData[ "Hashes" ] as $name => $def)
        {
            $inithash[ $name ]=$def;
        }

        $this->Init($inithash);
        $this->LeftMenu=$this->SetupData[ "Tables" ];
    }


  function HandleDownLoad($echo=TRUE)
  {
      $id=$this->GetGET("ID");
      $data=$this->GetGET("Data");
      $access=$this->GetDataAccessType($data,$this->ItemHash);

      if ($access>=1)
      {
          if ($id!="") { $this->ReadItem($id,array($data)); }
          $file=$this->ItemHash[ $data ];

          $matches=array();
          if (preg_match('/\.(\S{3})$/',$file,$matches))
          {
              $ext=$matches[1];
              $content=$this->MyReadFile($file);

              $this->SendDocHeader
              (
                 $ext,
                 preg_replace('/^\./',"",basename($file))
              );
              print join("",$content);
          }
      }
      else
      {
          print "Access denied";
      }

  }
  
  function HandleHelp()
  {      
      return $this->ApplicationObj->HandleHelp();;
  }
  
  function HandleAdd($echo=TRUE)
  {      
      $title=$this->GetRealNameKey($this->Actions[ "Add" ]);
      $ptitle=$this->GetRealNameKey($this->Actions[ "Add" ],"PName");

      return $this->AddForm($title,$ptitle,$echo);
  }
  
  
  function HandleShow($title="")
  {
      if ($this->GetGETOrPOST("LatexDoc")>0)
      {
          $this->HandlePrint();
      }

      if (empty($title))
      {
          $title=$this->GetRealNameKey($this->Actions[ "Show" ])." ".$this->ItemName;
      }

      if (count($this->ItemHash)>0)
      {
          return $this->EditForm
          (
             $title,
             $this->ItemHash,
             0
          );
      }
      else { print $this->ItemName." not found!"; }
  }

  function HandleEdit($echo=TRUE,$formurl=NULL,$title="")
  {
      if ($this->GetGETOrPOST("LatexDoc")>0)
      {
          $this->HandlePrint();
      }

      if (empty($title)) { $title=$this->GetRealNameKey($this->Actions[ "Edit" ])." ".$this->ItemName; }
      if (count($this->ItemHash)>0)
      {
          return $this->EditForm
          (
             $title,
             $this->ItemHash,
             1,
             FALSE,
             array(),
             $echo,
             array(),
             $formurl
          );
      }
      else { print $this->ItemName." not found!"; }

  }

  function HandleCopy()
  {
      $title=$this->GetRealNameKey($this->Actions[ "Copy" ]);
      $ptitle=$this->GetRealNameKey($this->Actions[ "Copy" ],"PName");

      preg_replace('/#ItemName/',$this->ItemName,$title);
      preg_replace('/#ItemsName/',$this->ItemName,$title);
      preg_replace('/#ItemName/',$this->ItemName,$ptitle);
      preg_replace('/#ItemsName/',$this->ItemName,$ptitle);

      $this->CopyForm($title,$ptitle);
  }

  function HandleDelete($echo=TRUE,$actionname="Delete",$formurl="?Action=Delete",$idvar="ID")
  {
      if ($this->ActionAllowed($actionname))
      {
          $title=$this->GetRealNameKey($this->Actions[ $actionname ]);
          $ptitle=$this->GetRealNameKey($this->Actions[ $actionname ],"PName");
          return $this->DeleteForm($title,$ptitle,array(),$echo,$formurl,$idvar);
      }
      else { print "Deletar não permitido"; }
  }

  function HandleLatexItem($item=array())
  {
      if (count($item)==0) { $item=$this->ItemHash; }
      $item=$this->TrimLatexItem($item);
      if (method_exists($this,"InitPrint")) { $item=$this->InitPrint($item); }

      $title=$this->ItemName." ".$item[ "ID" ].": ".$this->GetItemName($item);

      $this->ItemLatexTablePrint($title,$item);
  }

  function HandleLatexItems($where="")
  {
      $individual=$this->GetCGIVarValue("Individual");

      if ($individual==1)
      {
          $items=$this->ReadItems($where,array_keys($this->ItemData),FALSE,TRUE);
      }
      else
      {
          $items=$this->ReadItems($where);
      }

      $this->TrimLatexItems();

      $items=$this->SortItems();
      for ($n=0;$n<count($items);$n++)
      {
          if (method_exists($this,"InitPrint")) { $items[$n]=$this->InitPrint($items[$n]); }
      }

      if ($individual==1)
      {
          $this->ItemLatexTablesPrint($items);
      }
      else
      {
          $this->ItemsLatexTable("\\Large{Relatório de ".$this->ItemsName."}\n\n",$items);
      }
  }

  function HandlePrint($item=array())
  {
      if (count($item)==0) { $item=$this->ItemHash; }
      $item=$this->TrimLatexItem($item);

      $latexdocno=$this->CGI2LatexDocNo();

      if (!empty($this->LatexData[ "SingularLatexDocs" ][ "Docs" ][ $latexdocno ][ "AltHandler" ]))
      {
          $handler=$this->LatexData[ "SingularLatexDocs" ][ "Docs" ][ $latexdocno ][ "AltHandler" ];
          $this->$handler();
          exit();
      }

     if (method_exists($this,"InitPrint")) { $item=$this->InitPrint($item); }

     $this->PrintItem($item);
  }

  function HandlePrints($where="")
  {
      $datas=array_keys($this->ItemData);

      $latexdocno=$this->CGI2LatexDocNo();
      $nempties=$this->GetPOST("NEmptyLines");


      $sort="";
      if (isset($this->LatexData[ "PluralLatexDocs" ][ "Docs" ][ $latexdocno ][ "Sort" ]))
      {
          $sort=$this->LatexData[ "PluralLatexDocs" ][ "Docs" ][ $latexdocno ][ "Sort" ];
      }

      if ($sort=="")
      {
          $this->DetectSort();
      }
      else
      {
          $this->Sort=$sort;
      }

      $this->ReadItems($where,$datas,FALSE,TRUE); //searching, but no paging

      $this->TrimLatexItems();

      if (count($this->ItemHashes)==0)
      {
          $this->ApplicationObj->HtmlHead();
          $this->ApplicationObj->HtmlDocHead();
          print 
              $this->H(4,"Nemhum item selecionado!").
              $this->H(4,"Volte, define alguma chave de pesquisa (ou marque 'Incluir Todos') e tente novamente.");
          exit();
      }

      $this->SortItems();

      if (method_exists($this,"InitPrint"))
      {
          foreach (array_keys($this->ItemHashes) as $id)
          {
              $this->ItemHashes[$id]=$this->InitPrint($this->ItemHashes[$id]);
          }
      }

      if ($this->LatexData[ "PluralLatexDocs" ][ "Docs" ][ $latexdocno ][ "AltHandler" ])
      {
          $handler=$this->LatexData[ "PluralLatexDocs" ][ "Docs" ][ $latexdocno ][ "AltHandler" ];
          $this->$handler();
          exit();
      }

      $nmax=count(array_keys($this->ItemHashes) );

      $empty=array();
      foreach ($datas as $id => $data) { $empty[ $data ]=""; }
      for ($n=0;$n<$nempties;$n++)
      {
          $item=$empty;
          $item[ "No" ]=$nmax+$n+1;
          array_push($this->ItemHashes,$item);
      }

      $this->PrintItems($this->ItemHashes);
  }
  
  function HandleComposedAdd()
  {
      $this->HandleAdd();
      $this->HandleList("",TRUE,1);
  }

  function HandleExport()
  {
      $this->ExportForm();
  }

  function HandleProcess()
  {
      $this->PostProcessAllItems();
  }

  function HandleImport()
  {
      $this->ImportForm();
  }


  function HandleZip()
  {
      $this->ZipTree($this->GetUploadPath());
  }


  function HandleList($where="",$searchvarstable=TRUE,$edit=0,$group="",$omitvars=array(),$action="",$module="")
  {
      if ($this->GetGETOrPOST("LatexDoc")>0)
      {
          $this->HandlePrints($where);
      }

      $output=$this->GetGETOrPOST("Output");
      $outputs=array
      (
         "0" => "html",
         "1" => "pdf",
         "2" => "tex",
         "3" => "csv",
      );

      if ($output=="") { $output=0; }
      if (!isset($outputs[ $output ]))
      {
          print "Invalid output: $output<BR>";
          exit();
      }

      $output=$outputs[ $output ];

      if (empty($group))
      {
          $group=$this->GetActualDataGroup();
      }

      $datas=$this->GetGroupDatas($group);
      if ($group!="")
      {
          if ($where=="" && isset($this->ItemDataGroups[ $group ][ "SqlWhere" ]))
          {
              $where=$this->ItemDataGroups[ $group ][ "SqlWhere" ];
          }

          if (
                isset($this->ItemDataGroups[ $group ][ "Edit" ])
                &&
                $this->ItemDataGroups[ $group ][ "Edit" ]
             )
          {
              $edit=1;
          }
      }

      $this->DetectSort($group);

      if (count($this->ItemHashes)==0)
      {
          if ($where=="")
          {
              $this->ReadItems("",$datas);
          }
          else
          {
              $this->ReadItems($where,$datas,FALSE,FALSE);
          }
      }

      $action=$this->DetectAction();
      if ($this->CGI2Edit()==2)
      {
          $edit=1;
      }

      $title="";
      if (!empty($this->Actions[ "ShowList" ]))
      {
          $title=$this->GetRealNameKey($this->Actions[ "ShowList" ]);
      }

      if ($action=="EditList")
      {
          $edit=1;
      }

      if ($edit==1)
      {
          $title=$this->GetRealNameKey($this->Actions[ "EditList" ]);
      }

      $tdatas=$datas;
      if (isset($this->ItemDataGroups[ $group ][ "TitleData" ]))
      {
          $tdatas=$this->ItemDataGroups[ $group ][ "TitleData" ];
      }

      $table=array();
      if ($output=="html")
      {           
          $table=$this->ItemsTableDataGroup
          (
           $title,
           $edit,
           $group,
           array()
          );
      }
      elseif ($output=="pdf")
      {
          $table=$this->ItemsLatexTable();
      }
      elseif ($output=="tex")
      {
          $table=$this->ItemsLatexTable(TRUE);
      }
      elseif ($output=="csv")
      {
          $table=$this->ItemsCSVTable();
      }

      $searchvars=$this->GetDefinedSearchVars($datas);
      if ($this->AddSearchVarsToDataList)
      {
          $datas=$this->AddSearchVarsToDataList($datas);
      }

      if ($output=="html")
      {
          if ($searchvarstable)
          {
              print 
                  $this->SearchVarsTable($omitvars,"",$action,array(),array(),$module).
                  $this->BR();
          }
      }

      if (count($this->ItemHashes)>0)
      {
          if ($edit)
          {
              print 
                  $this->StartForm("?ModuleName=".$this->GetGET("ModuleName")."&Action=".$this->DetectAction()).
                  $this->MakeHidden("Update",1).
                  $this->Buttons();
          }
      }

      if (empty($table)) { $table=array(); }


      if ($output=="html")
      {
          $title=
              $this->GetMessage($this->ItemDataMessages,"PluralTableTitle").": ".
              $this->ItemDataGroups[ $group ][ "Name" ];

          if (FALSE) //31/05/2013 !$this->ItemDataGroups[ $group ][ "NoTitleRow" ])
          {   
              array_unshift
              (
                 $table,
                 $this->GetSortTitles($tdatas)
              );
          }

          if (!empty($this->ItemDataGroups[ $group ][ "TitleGenMethod" ]))
          {
              $method=$this->ItemDataGroups[ $group ][ "TitleGenMethod" ];
              array_unshift($table,array($this->$method()));
          }

          print 
              $this->H
              (
                 3,
                 preg_replace('/#ItemsName/',$this->ItemsName,$title)
              ).
              $this->HTML_Table
              (
                 "",
                 $table,
                 array("ALIGN" => 'center'),
                 array(),
                 array(),
                 TRUE
              );
      }

      if (count($this->ItemHashes)>0)
      {
          if ($edit)
          {
              print 
                  $this->MakeHiddenFields(TRUE).//include tabmovesdown hidden var
                  $this->ItemGroupHidden($group).
                  $this->ItemEditListHidden($edit).
                  join("\n",$this->SearchVarsAsHiddens()).
                  $this->MakeHidden("Update",1).
                  $this->MakeHidden("EditList",1).
                  $this->MakeHidden("__MTime__",time()).
                  $this->Buttons().
                  $this->EndForm();
          }
      }
  }

  function PrintDocHeads()
  {
      $this->ApplicationObj->HtmlHead();
      $this->ApplicationObj->HtmlDocHead();
  }


  function PrintDocHeadsAndLeftMenu($output=-1)
  {
      $this->PrintDocHeads();
      $this->TInterfaceMenu();
  }

  function SavePrintDocHeads($output=-1)
  {
      if ($output<0) { $output=$this->GetGETOrPOST("Output"); }

      $latex=0;
      $latex=$this->GetGETOrPOST("Latex");
      if ($latex==1) { return; }

      $latexdoc=$this->GetGETOrPOST("LatexDoc");
      if ($latexdoc=="") { $latexdoc=0; }

      if ($output==0 && $latexdoc==0)
      {
          $action=$this->DetectAction();
          if ($this->Actions[ $action ][ "NoHeads" ]!=1)
          {
              $this->PrintDocHeads();
              $this->ApplicationObj->HtmlDocHead();
          }

          if (
                !isset($this->Actions[ $action ][ "NoInterfaceMenu" ])
                ||
                $this->Actions[ $action ][ "NoInterfaceMenu" ]!=1
             )
          {
              $this->TInterfaceMenu(!$this->Actions[ $action ][ "Singular" ]);
          }
      }
  }

  function Handle()
  {
      if ($this->NoHandle!=0) { return; }

      //Do we need to read an item?
      $id=0;
      if ($this->IDGETVar)
      {
          $id=$this->GetGETOrPOST($this->IDGETVar);
      }

      if (empty($id))
      {
          $id=$this->GetGETOrPOST("ID");
      }

      if (!empty($id))
      {
          if (count($this->ItemHash)==0)
          {
              $this->ReadItem($id);
          }
          if (count($this->ItemHash)==0)
          {
              print "Table::Handle ".$this->ModuleName.": Not found or not allowed... (".$id.")";
              exit();
          }
      }

      //Do we have a suitable action?
      $action=$this->DetectAction();
      if ($action=="")
      {
          print "Table::Handle ".$this->ModuleName.": Undefined action '$action' - redirect\n";
          $this->Redirect();
          exit();
      }

      //Do we have an allowed action?
      $res=$this->TestActionAccess($action);
      if (!$res)
      {
          $this->Redirect();
          exit();
      }

      $handler=$this->Actions[ $action ][ "Handler" ];
      if ($handler=="")
      {
          $handler="Handle".$action;
      }

      if (!method_exists($this,$handler))
      {
          print $this->ModuleName.": Undefined handler, action $action (".$this->Actions[ $action ][ "Handler" ]."), $handler\n";
          $this->Redirect();
          exit();
      }

      $this->SavePrintDocHeads();
      if ($this->GetGET("Help")==1 && $this->DetectHelpFile())
      {
          $this->ShowHelp();
      }
      else
      {
          //this->ApplicationObj->PrintHelpLink();
          $this->$handler();
      }
  }
}

?>