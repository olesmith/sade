<?php


class Data extends Access
{
    var $TitleKeyShortName="ShortName";
    var $TitleKeyName="Name";
    var $TitleKeyTitle="Title";

    //*
    //* Variables of Data class:

    var $Masculine;
    var $ItemName,$ItemsName,$ItemNamer;
    var $SqlData,$SqlDerivers;
    var $ItemData=array(),$ItemDerivedData=array(),$ItemDataGroups=array(),$ItemDataSGroups=array();
    var $ItemDataFiles=array("Data.php");
    var $ItemDataMode="Php";
    var $TabMovesDownKey="_TabMovesDown";
    var $StringVars=array("Sql","Name","LongName");
    var $BoolVars=array("Compulsory","Visible","Admin","Person","Public","NoSort","ShowOnly","TimeType");
    var $ListVars=array("Values","ShowIDCols","EditIDCols");
    var $AlwaysReadData=array();
    var $DatasRead=array();
    var $DefaultItemData=array
    (
      "Name"              => "",
      "Name_UK"           => "",
      "ShortName"         => "",
      "ShortName_UK"      => "",
      "LongName"          => "",
      "LongName_UK"       => "",
      "Title"             => "",
      "Title_UK"          => "",
      "Sql"               => "",
      "Unique"            => FALSE,

      "CGIName"           => "",
      "Size"              => FALSE,
      "Type"              => "",
      "MD5"               => FALSE,
      "Hidden"            => FALSE,
      "Password"          => FALSE,
      "TimeType"          => FALSE,
      "Derived"           => FALSE,
      "DerivedFilter"     => "",
      "DerivedNamer"      => "",
      "ReadOnly"          => FALSE,
      "PublicReadOnly"    => FALSE,
      "PersonReadOnly"    => FALSE,
      "AdminReadOnly"     => FALSE,

      "SqlDerivedData"    => array(),
      "SqlData"           => NULL,
      "SqlDerivedNamer"   => "",
      "SqlDisabledMethod" => "",
      "SqlHRefIt"         => FALSE,
      "SqlTextSearch"     => FALSE,
      "SqlObject"         => NULL,
      "SqlClass"          => NULL,
      "SqlMethod"         => NULL,
      "SqlSortReverse"    => FALSE,

      "Admin"             => TRUE,
      "Public"            => FALSE,
      "Person"            => FALSE,

      "Search"            => FALSE,
      "SearchFieldMethod" => "",
      "SearchDefault"     => "",
      "SearchCompound"     => "",
      "SearchCheckBox"     => "",
      "SearchRadioSet"     => "",
      "GETSearchVarName"  => FALSE,
      "NoSearchRow"       => FALSE,
      "NoSearchEmpty"       => FALSE,

      "Default"           => FALSE,
      "Values"            => array(),
      "ShowIDCols"        => array(),
      "EditIDCols"        => array(),

      "ValuesMatrix"      => NULL,

      "SortAsDate"        => FALSE,
      "TrimCase"          => FALSE,
      "Iconify"           => FALSE,
      "Compulsory"        => FALSE,
      "FieldMethod"       => "",
      "NoAdd"            => FALSE,
      "NoSort"            => FALSE,
      "NoSelectSort"      => FALSE,
      "SelectOffset"      => 0,
      "EmptyName"         => "",
      "AltTable"          => FALSE,
      "NamerLink"         => FALSE,
      "MaxLength"         => 0,
      "IconColors"        => "",
      "BkIconColors"      => "",
      "CompulsoryText"    => "",
      "TableSize"         => "",
      "LatexCode"         => FALSE,
      "LatexWidth"        => "",
      "LatexFormat"        => FALSE,
      "HRef"              => "",
      "HRefIt"            => FALSE,
      "HRefIcon"          => "",
      "Iconed"            => "",
      "Format"            => FALSE,
      "IsDate"            => FALSE,
      "IsHour"            => FALSE,
      "ToDayIsDefault"    => FALSE,
    );

    //*
    //* function SetupDataPath, Parameter list: 
    //*
    //* Returns SetupDataPath.
    //*

    function SetupDataPath()
    {
        return $this->ApplicationObj->ModuleSetupDataPath($this->ModuleName);

        //return $this->ApplicationObj->SetupPath."/".$this->ModuleName."/";
    }

     //*
    //* function GetDefaultItemData, Parameter list: $data,&$datadef
    //*
    //* Makes sure ItemData entries has all necessary keys.
    //*

    function GetDefaultItemData($data,&$datadef)
    {
        foreach ($this->DefaultItemData as $key => $value)
        {
            if (!isset($datadef[ $key ]))
            {
                $datadef[ $key ]=$value;
            }
        }

        foreach ($this->ApplicationObj->ValidProfiles as $profile)
        {
            if (!isset($datadef[ $profile ]))
            {
                $datadef[ $profile ]=0;
            }
        }
    }

   //*
    //* function InitDefaultItemData, Parameter list:
    //*
    //* Makes sure ItemData entries has all necessary keys.
    //*

    function InitDefaultItemData()
    {
        foreach (array_keys($this->ItemData) as $data)
        {
            $this->GetDefaultItemData($data,$this->ItemData[ $data ]);
        }

        $this->InitDefaultItemGroups();
    }

     //*
    //* function UpdateTableStructure, Parameter list: 
    //*
    //* Does actual Table structure updating.
    //*

    function UpdateTableStructure()
    {
        $this->UpdateDBFields
        (
           $this->SqlTableName(),
           $this->DataKeys(),
           $this->ItemData
        );
    }

     //*
    //* function InitData, Parameter list: $updatetable=FALSE
    //*
    //* Initializes Item data; updates DB fields
    //* if $updatetable is set to TRUE in call.
    //*

    function InitData($updatetable=FALSE)
    {   
        $this->TabMovesDownKey=$this->ModuleName.$this->TabMovesDownKey;

        $this->InitTimeData();

        foreach ($this->ItemDataFiles as $file)
        {
            $this->ItemData=$this->ReadPHPArray
            (
               $this->SetupDataPath()."/".$file,
               $this->ItemData
            );
        }

        //Allows defining more data, before we call UpdateDBFields.
        if (method_exists($this,"PostProcessItemData"))
        {
            $this->PostProcessItemData();
        }

        $this->InitDefaultItemData();
        $this->InitDataGroups();


        $this->InitDataPermissions();
        if ($updatetable)
        {
            $this->UpdateTableStructure();
       }

        $this->InitItemData();
  }

    //*
    //* function InitDataPermissions, Parameter list:
    //*
    //* Updates data permissions from Profile.
    //*

    function InitDataPermissions()
    {
        $alldatas=array_keys($this->ItemData);

        //Take directly defined data permissions, via $this->ProfileHash[ "Data" ][ "Access" ]
        if (isset($this->ProfileHash[ "Data" ][ "Access" ]))
        {
            foreach ($this->ProfileHash[ "Data" ][ "Access" ] as $data => $value)
            {
                $rdatas=preg_grep('/^'.$data.'$/',$alldatas);
                foreach ($rdatas as $rdata)
                {
                    if (is_array($this->ItemData[ $rdata ]))
                    {
                        $this->ItemData[ $rdata ][ $this->LoginType ]=$value;
                        $this->ItemData[ $rdata ][ $this->Profile ]=$value;
                    }
                }

                if (!isset($this->ItemData[ $rdata ][ $this->Profile ]))
                {
                    $this->ItemData[ $rdata ][ $this->Profile ]=0;
                }
                if (!isset($this->ItemData[ $rdata ][ $this->LoginType ]))
                {
                    $this->ItemData[ $rdata ][ $this->LoginType ]=0;
                }
            }
        }

        //Take data read permissions (1) defined via $this->ProfileHash[ "Data" ][ "Read" ]
        if (
            isset($this->ProfileHash[ "Data" ][ "Read" ])
            &&
            is_array($this->ProfileHash[ "Data" ][ "Read" ])
           )
        {
            foreach ($this->ProfileHash[ "Data" ][ "Read" ] as $id => $data)
            {
                $rdatas=preg_grep('/^'.$data.'$/',$alldatas);
                foreach ($rdatas as $rdata)
                {
                    if (is_array($this->ItemData[ $rdata ]))
                    {
                        $this->ItemData[ $rdata ][ $this->LoginType ]=1;
                        $this->ItemData[ $rdata ][ $this->Profile ]=1;
                    }
                }
            }
        }

        //Take data write permissions (2) defined via $this->ProfileHash[ "Data" ][ "Write" ]

        if (
            isset($this->ProfileHash[ "Data" ][ "Write" ])
            &&
            is_array($this->ProfileHash[ "Data" ][ "Write" ])
           )
        {
            foreach ($this->ProfileHash[ "Data" ][ "Write" ] as $id => $data)
            {
                $rdatas=preg_grep('/^'.$data.'$/',$alldatas);
                foreach ($rdatas as $rdata)
                {
                    if (is_array($this->ItemData[ $rdata ]))
                    {
                        $this->ItemData[ $rdata ][ $this->LoginType ]=2;
                        $this->ItemData[ $rdata ][ $this->Profile ]=2;
                    }
                }
            }
        }
  }

    //*
    //* function InitTimeData, Parameter list: 
    //*
    //* Creates Time data in $this->ItemData:
    //* CTime,MTime,ATime.
    //*

  function InitTimeData()
  {
      $this->ItemData[ "CTime" ]=array
      (
         "LongName" => "Hora Criado",
         "Name"     => "Criado",
         "Name_UK"  => "Created",
         "Sql"      => "INT",
         "Public"   => 0,
         "Person"   => 0,
         "Admin"   => 1,
         "TimeType" => 1,
      );
      $this->ItemData[ "MTime" ]=array
      (
         "LongName" => "Hora Modificado",
         "Name"     => "Modificado",
         "Name_UK"  => "Modified",
         "Sql"      => "INT",
         "Public"   => 0,
         "Person"   => 0,
         "Admin"   => 1,
         "TimeType" => 1,
      );
      $this->ItemData[ "ATime" ]=array
      (
         "LongName" => "Hora Acessado",
         "Name"     => "Acessado",
         "Name_UK"  => "Accessed",
         "Sql"      => "INT",
         "Public"   => 0,
         "Person"   => 0,
         "Admin"   => 1,
         "TimeType" => 1,
      );
  }

    //*
    //* function InitLatexData, Parameter list: 
    //*
    //* Reads Latex setups for module.
    //*

    function InitLatexData()
    {
        if (is_array($this->LatexData) && count($this->LatexData)>0)
        {
            return;
        }

        //First global Latex defs
        $this->LatexData=$this->ReadPHPArray($this->ApplicationObj->SetupPath."/Defs/Latex.Data.php");

        //Add module specific Latex defs
        $this->LatexData=$this->ReadPHPArray
        (
           $this->ApplicationObj->ModuleLatexDataFile($this->ModuleName),
           $this->LatexData
        );

    }


  function RealFieldName($field)
  {
      $rfield=$field;
      if ($this->Reserved[ $field ]!="") { $rfield=$this->Reserved[ $field ]; }

      return $rfield;
  }

  function OriginalFieldName($field)
  {
      $hash=$this->Reserved;
      $rhash=array();
      foreach ($hash as $key => $value) { $rhash[ $value ]=$key; }

      $rfield=$field;
      if ($rhash[ $field ]!="") { $rfield=$rhash[ $field ]; }

      return $rfield;
  }

  function TransFieldNamesHash($hash,$type)
  {
      $specs=$this->DataSpecs[ $type ];

      $rhash=array();
      foreach ($hash as $field => $value)
      {
          $rfield=$this->RealFieldName($field);
          $res=preg_grep("/^$field$/",$specs);
          if (count($res)>0)
          {
              $rhash[ $rfield ]=$hash[ $field ];
              $rhash[ $rfield ]=preg_replace("/'/","''",$rhash[ $rfield ]);
          }
      }

      return $rhash;
  }

  function OriginalFieldNamesHash($hash)
  {
      $rhash=array();
      foreach ($hash as $field => $value)
      {
          $rfield=$this->OriginalFieldName($field);
          $rhash[ $rfield ]=$hash[ $field ];
      }

      return $rhash;
  }
  function AddDBFieldName($table,$field,$sqltype)
  {
      $query="ALTER TABLE ".$table." ADD COLUMN ".$field." ".$sqltype." DEFAULT ''";
      $this->QueryDB($query);
  }


  function InitItemNames($itemname="",$itemsname="")
  {
      $this->Masculine=0;

      if ($itemname=="") { $itemname=$this->ItemName; }
      $this->ItemName=$itemname;

      if ($itemsname=="") { $itemsname=$this->ItemsName; }
      if ($itemsname=="") { $itemsname=$itemname."s"; }
      $this->ItemsName=$itemsname;
  }

  function GetRealSqlWhereClause()
  {
      $this->DetectLoginType();

      $where="";
      if ($this->LoginType=="Admin")
      {
          if (isset($this->ItemData[ $data ][ "SqlAdminWhere" ]))
          {
              $where=$this->ItemData[ $data ][ "SqlAdminWhere" ];
          }
      }
      elseif ($this->LoginType=="Person")
      {
          if (isset($this->ItemData[ $data ][ "SqlPersonWhere" ]))
          {
              $where=$this->ItemData[ $data ][ "SqlPersonWhere" ];
          }
      }
      elseif ($this->LoginType=="Public")
      {
          if (isset($this->ItemData[ $data ][ "SqlPublicWhere" ]))
          {
              $where=$this->ItemData[ $data ][ "SqlPublicWhere" ];
          }
      }

      if ($where=="")
      {
          $where=$this->ItemData[ $data ][ "SqlWhere" ];
      }

      $loginid=(int)$this->LoginID;
      $where=preg_replace('/#LoginID/',$loginid,$where);

      return $where;
  }

  function SetSqlObjectDataDefs($data)
  {
      if ($this->ItemData[ $data ][ "SqlObject" ]!="")
      {
          $object=$this->ItemData[ $data ][ "SqlObject" ];
          foreach ($object->Object->ItemData as $id => $rdata)
          {
              $this->ItemData[ $data."_".$data ]=$object->ItemData[ $rdata ];
          }
      }
  }


  function InitSqlObject($data)
  {
      $class=$this->ItemData[ $data ][ "SqlClass" ];
      foreach ($this->ApplicationObj->SubModulesVars [ $class ] as $key => $value)
      {
          if (empty($this->ItemData[ $data ][ $key ]))
          {
              $this->ItemData[ $data ][ $key ]=$value;
          }
      }
      return;
  }


  function InitItemData($itemdata=array())
  {
      if (count($itemdata)==0) { $itemdata=$this->ItemData; }
      $this->ItemData=$itemdata;

      $this->ItemDerivedData=array();
      $this->ItemDerivers=array();
 
      foreach ($this->ItemData as $data => $hash)
      {
          if (isset($this->ItemData[ $data ][ "IsDate" ]) && $this->ItemData[ $data ][ "IsDate" ])
          {
              $this->ItemData[ $data ][ "TriggerFunction" ]="TrimDateData";
          }

          foreach ($this->ItemData[ $data ] as $key => $value)
          {
              if (is_array($value))
              {
                  foreach ($value as $id => $val)
                  {
                      if (is_string($val))
                      {
                          $value[ $id ]=preg_replace('/#LoginID/',$this->LoginID,$val);
                      }
                  }

                  $this->ItemData[ $data ][ $key ]=$value;
              }
              elseif (is_string($value))
              {
                  $this->ItemData[ $data ][ $key ]=
                      preg_replace('/#LoginID/',$this->LoginID,$value);
              }
          }

          $language=$this->GetCGIVarValue("Lang");
          if ($language!="")
          {
              if ($this->ItemData[ $data ][ "Name_".$language ]!="")
              {
                  $this->ItemData[ $data ][ "Name" ]=
                      $this->ItemData[ $data ][ "Name_".$language ];
              }
          }

          if ($this->ItemData[ $data ][ "SqlClass" ])
          {
              $this->InitSqlObject($data);
              array_push($this->ItemDerivers,$data);
          }

          if (
                isset($this->ItemData[ $data ][ "TriggerFunction" ])
                &&
                $this->ItemData[ $data ][ "TriggerFunction" ]!="")
          {
              $this->TriggerFunctions[ $data ]=$this->ItemData[ $data ][ "TriggerFunction" ];
          }
      }
 }


  function DataKeyHash($key)
  {
      $list=array();
      foreach ($this->ItemData as $data => $hash)
      {
          $list[ $data ]=$hash[ $key ];
      }

      return $list;
  }

  function DataKeys()
  {
      $list=array();
      foreach ($this->ItemData as $data => $hash)
      {
          array_push($list,$data);
      }

      return $list;
  }

  function GetDataTitle($data,$nohtml=0)
  {
      $title="";
      if ($data=="No")
      {
          $title="No";
      }
      elseif (isset($this->ItemDerivedData[ $data ]))
      {
          if ($this->ItemDerivedData[ $data ][ $this->TitleKeyName ]!="")
          {
              $title=$this->GetRealNameKey($this->ItemDerivedData[ $data ],$this->TitleKeyName);
          }
      }
      elseif (isset($this->ItemData[ $data ]))
      {
          if (!empty($this->ItemData[ $data ][ $this->TitleKeyShortName ]))
          {
              $title=$this->GetRealNameKey($this->ItemData[ $data ],$this->TitleKeyShortName);
          }
          elseif (!empty($this->ItemData[ $data ][ $this->TitleKeyName ]))
          {
              $title=$this->GetRealNameKey($this->ItemData[ $data ],$this->TitleKeyName);
          }
          elseif (!empty($this->ItemData[ $data ][ $this->TitleKeyTitle ]))
          {
              $title=$this->GetRealNameKey($this->ItemData[ $data ],$this->TitleKeyTitle);
          }
      }
      elseif (isset($this->Actions[ $data ]))
      {
          return "";
          if (!empty($this->Actions[ $data ][ $this->TitleKeyName ]))
          {
              $title=$this->GetRealNameKey($this->Actions[ $data ],$this->TitleKeyName);
          }
      }
      else
      {
          $comps=preg_split('/_/',$data);
          if (count($comps)>1)
          {
              $pridata=array_shift($comps);
              $secdata=join("_",$comps);

              if (isset($this->ItemData[ $pridata ]) && is_array($this->ItemData[ $pridata ]))
              {
                  if ($this->ItemData[ $pridata ][ "SqlObject" ]!="")
                  {
                      $object=$this->ItemData[ $pridata ][ "SqlObject" ];
                      $title=
                          $this->GetDataTitle($pridata,$nohtml).", ".
                          $this->$object->GetDataTitle($secdata,$nohtml);
                  }
              }
          }
      }

      if ($title=="") { $title=$data; }

      $title=preg_replace('/#ItemName/',$this->ItemName,$title);
      $title=preg_replace('/#ItemsName/',$this->ItemsName,$title);

      return $title;
  }

  function DecorateDataTitle($name,$title="")
  {
      return $this->Span($name,array("CLASS" => 'datatitlelink',"TITLE" => $title));
    
  }

  function DecoratedDataTitle($data,$includecolon=FALSE)
  {
      $title=$this->GetDataTitle($data);

      if ($includecolon) { $title.=":"; }

      return $this->DecorateDataTitle($title);
    
  }


  function GetDataTitles($datas,$nohtml=0)
  {
      $titles=array();
      for ($n=0;$n<count($datas);$n++)
      {
          $titles[$n]=$this->GetDataTitle($datas[$n],$nohtml);
      }

      return $titles;
  }


  function DataHash2File($itemdata,$file)
  {
      $keys=array();
      foreach ($itemdata as $data => $hash)
      {
          foreach ($hash as $key => $value)
          {
              if (!preg_grep("/^$key$/",$keys)) { array_push($keys,$key); }
          }
      }

      $lines=array();
      foreach ($itemdata as $data => $hash)
      {
          for ($n=0;$n<count($keys);$n++)
          {
              $value=$hash[ $keys[ $n ] ];
              $type="Scalar";
              if (is_array($value))
              {
                  $value='("'.join('","',$value).'")';
                  $type="List";
              }
              array_push($lines,$data."\t".$type."\t".$keys[ $n ]."\t".$value."\n");
          }
      }

      $this->MyWriteFile($file,$lines);
  }

  function WriteDataFiles()
  {
      $this->DataHash2File($this->ItemData,"Data/".$this->ModuleName.".Data.txt");
      $this->DataHash2File($this->ItemDataGroups,"Data/".$this->ModuleName.".Groups.txt");
  }

  function DataFile2Hash($file)
  {
      $lines=$this->MyReadFile($file);

      $itemdata=array();
      for ($n=0;$n<count($lines);$n++)
      {
          $lines[$n]=chop($lines[$n]);
          $comps=preg_split('/\t/',$lines[$n]);
          $data=array_shift($comps);
          $type=array_shift($comps);
          $key=array_shift($comps);
          $value=join("\t",$comps);


          if (!is_array($itemdata[ $data ]))
          {
              $itemdata[ $data ]=array();
          }

          if ($type=="List")
          {
              $value=preg_replace('/^\("/',"",$value);
              $value=preg_replace('/"\)$/',"",$value);
              $value=preg_split('/","/',$value);
          }

          $itemdata[ $data ][ $key ]=$value;
      }

      return $itemdata;
  }
                    
  function ReadDataFiles()
  {
      $this->ItemData=$this->DataFile2Hash("Data/".$this->ModuleName.".Data.txt");
      $this->ItemDataGroups=$this->DataFile2Hash("Data/".$this->ModuleName.".Groups.txt");
  }


  function DefineItemData($itemdata)
  {
      $table=array();
      $keys=array();
      foreach ($itemdata as $data => $hash)
      {
          if (!preg_match('/^[ACM]Time$/',$data))
          {
              array_push($table,array($this->H(3,$data)));
              if (count($keys)==0) { $keys=array_keys($hash); }
              for ($n=0;$n<count($keys);$n++)
              {
                  $fieldname=$data."_".$keys[$n];

                  $value=$hash[ $keys[$n] ];
                  if (preg_grep('/^'.$keys[$n].'$/',$this->StringVars))
                  {
                      $value=$this->MakeInput($fieldname,$value,strlen($value));
                  }
                  elseif (preg_grep('/^'.$keys[$n].'$/',$this->BoolVars))
                  {
                      $bools=array("0","1");
                      $value=$this->MakeSelectField($fieldname,$bools,$bools,$value);
                  }
                  elseif (is_array($value))
                  {
                      $value=join(",",$value);
                  }
                  array_push($table,array($n+1,"<B>".$keys[$n]."</B>",$value));
              }
          }
      }

      print
          $this->H(1,"Dados do ".$this->ItemName).
          $this->HtmlTable("",$table);
  }

  function DefineDataForm()
  {
      $this->DefineItemData($this->ItemData);
  }

    //*
    //* function NonDerivedData, Parameter list: $datas=array()
    //*
    //* Detects which data to actually read.
    //*

    function NonDerivedData($datas=array())
    {
        if (count($datas)==0)
        {
            $datas=array_keys($this->ItemData);
        }

        $rdatas=array();
        foreach ($datas as $id => $data)
        {
            if (
                isset($this->ItemData[ $data ])
                &&
                is_array($this->ItemData[ $data ])
                &&
                !$this->ItemData[ $data ][ "Derived" ]
               )
            {
                array_push($rdatas,$data);
            }
        }

        return $rdatas;
    }


    //*
    //* function FindDatasToRead, Parameter list: $datas=array(),$nosearches=FALSE,$sep=""
    //*
    //* Detects which data to actually read.
    //*

    function FindDatasToRead($datas=array(),$nosearches=FALSE,$sep="")
    {
        if (count($datas)==0)
        {
            $group=$this->GetActualDataGroup();
            $datas=$this->GetGroupDatas($group);

            if (
                isset($this->ItemDataGroups[ $group ])
                &&
                count($this->ItemDataGroups[ $group ][ "SubTable" ])>0
               )
            {
                $subdatas=$this->CheckHashKeysArray
                (
                   $this->ItemDataGroups[ $group ][ "SubTable" ],
                   array($this->Profile."_Data",$this->LoginType."_Data","Data")
                );

                $count=$this->ItemDataGroups[ $group ][ "SubTable" ][ "Max" ];
                for ($i=1;$i<=$count;$i++)
                {
                    $crow=array();
                    foreach ($subdatas as $data)
                    {
                        array_push($datas,$data.$sep.$i);
                    }
                }
            }
        }

        if (!$nosearches)
        {
            $datas=$this->AddSearchVarsToDataList($datas);
        }

        $this->SortVars2DataList($datas);

        //Always read IDs
        if (!preg_grep('/^ID$/',$datas))
        {
            array_unshift($datas,"ID");
        }

        //Other data that we sould always read - to be set by specific module
        foreach ($this->AlwaysReadData as $id => $data)
        {
            if (!preg_grep('/^'.$data.'$/',$datas)) { array_unshift($datas,$data); }
        }

        $rdatas=array();
        foreach ($datas as $id => $data)
        {
            if (
                isset($this->ItemData[ $data ])
                &&
                is_array($this->ItemData[ $data ])
                &&
                !$this->ItemData[ $data ][ "Derived" ]
               )
            {
                array_push($rdatas,$data);
            }
        }

        $this->DatasRead=$rdatas;

        return $rdatas;
    }

    //*
    //* function Hash2ItemData, Parameter list: $hashdata,$datakey
    //*
    //* Adds hash data in $hashdata to $this->ItemData with prekey $datakey.
    //* If $hashdata is a string, tries to read item hashes from this as a file.
    //*

    function Hash2ItemData($hashdata,$datakey,$filterhash=array())
    {
        if (!is_array($hashdata))
        {
            $hashdata=$this->ReadPHPArray($hashdata);
        }

        if (!preg_match('/_$/',$datakey)) { $datakey.="_"; }

        $rdatas=array();
        foreach ($hashdata as $data => $datadef)
        {
            $key=$datakey.$data;
            $this->ItemData[ $key ]=$this->FilterHashKeys($datadef,$filterhash);

            array_push($rdatas,$key);
        }

        return $rdatas;
    }

    //*
    //* function HashList2ItemData, Parameter list: $hashdata,$datakey,$ndata
    //*
    //* Adds $ndata copies of each of they data defined in $hashdata to 
    //* $this->ItemData.
    //* If $hashdata is a string, tries to read item hashes from this as a file.
    //*

    function HashList2ItemData($hashdata,$datakey,$ndata,$filterhash=array(),$newline=0)
    {
        if (!is_array($hashdata))
        {
            $hashdata=$this->ReadPHPArray($hashdata);
        }

        if (!preg_match('/_$/',$datakey)) { $datakey.="_"; }

        $rdatas=array();
        for ($n=1;$n<=$ndata;$n++)
        {
            $rfilterhash=$filterhash;
            foreach ($rfilterhash as $key => $value)
            {
                $rfilterhash[ $key ].=" ".$n;
            }
            $rfilterhash[ "N" ]=$n;

            foreach ($hashdata as $data => $datadef)
            {
                $key=$datakey.$n."_".$data;
                $this->ItemData[ $key ]=$this->FilterHashKeys($datadef,$rfilterhash);

                array_push($rdatas,$key);
            }

            if ($newline>0)
            {
                array_push($rdatas,"newline(".$newline.")");
            }
        }

        return $rdatas;
    }


    //*
    //* function FindAllowedData, Parameter list: $edit=0
    //*
    //* Returns list of data allowed to read ($edit=0) or write ($edit=1)..
    //*

    function FindAllowedData($edit=0)
    {
        $datas=array();
        $item=array();
        foreach (array_keys($this->ItemData) as $id => $data)
        {
            $access=$this->GetDataAccessType($data,$item);

            if ($access>$edit)
            {
                array_push($datas,$data);
            }
        }

        return $datas;
    }

    //*
    //* Returns data defined in $this->ItemData that are not ReadOnly.
    //*

    function GetNonReadOnlyData()
    {
        //27/01/2012 $this->PostProcessItemData();
        $datas=array_keys($this->ItemData);
        $rdatas=array();
        foreach ($datas as $id => $data)
        {
            if ($this->ItemData[ $data ][ "ReadOnly" ]) {}
            else
            {
                array_push($rdatas,$data);
            }
        }

        return $rdatas;
   }

    //*
    //* function ListOfItemDataWithKeysValues, Parameter list: $valueshash,$datas=NULL
    //*
    //* Returns list of item data keys, where the keys in $valueshash
    //* matches regex in $value. $valueshash is ass. array: $key => $value,...
    //* If $datas is NULL or non-array, $datas is set to all data:
    //* array_keys($this->ItemData).
    //*

    function ListOfItemDataWithKeysValues($valueshash,$datas=NULL,$revert=TRUE)
    {
        if (!$datas || !is_array($datas)) { $datas=array_keys($this->ItemData); }

        $rdatas=array();
        foreach ($datas as $id => $data)
        {
            $include=TRUE;
            foreach ($valueshash as $key => $regex)
            {
                if ($revert)
                {
                    if (!preg_match('/'.$regex.'/',$this->ItemData[ $data ][ $key ]))
                    {
                        $include=FALSE;
                        break;
                    }
                }
                else
                {
                    if (preg_match('/'.$regex.'/',$this->ItemData[ $data ][ $key ]))
                    {
                        $include=FALSE;
                        break;
                    }
                }
            }

            if ($include)
            {
                array_push($rdatas,$data);
            }
        }

        return $rdatas;
    }
}

?>