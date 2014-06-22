<?php


class MySqlStructure extends MySqlDelete
{
    var $TableFields=array();

  function GetEnumSpec($datadef)
  {
      if (!is_array($datadef))
      {
          $datadef=$this->ItemData[ $datadef ];
      }

      $sqltype=$datadef[ "Sql" ];
      if (preg_match('/ENUM/',$sqltype))
      {
          $values=$sqltype;
          $defs=preg_replace('/^\s*ENUM\s*\(/',"",$sqltype);
          $defs=preg_replace('/\s*\)\s*/',"",$defs);
          $values=preg_split('/\s*,\s*/',$defs);
          $values=$datadef[ "Values" ];

          $rvalues=array();
          for ($nn=0;$nn<=count($values);$nn++)
          {
              $rvalues[$nn]=$nn;
          }

          return "ENUM ('".join("','",$rvalues)."')";
      }

      $this->AddMsg("$data is not of type ENUM");

      return "";
  }

  //*
  //* function SetColumnDefault, Parameter list: table,$column,$value
  //*
  //* Sets column default value.
  //* 
  //*

  function SetColumnDefault($table,$column,$value)
  {
      if (empty($value)) { return; }
      if ($value=="0 ") { return; }


      $table=$this->SqlTableName($table);
      $query="ALTER TABLE ".$table." ALTER COLUMN ".$column." SET DEFAULT '".$value."'";
      $this->QueryDB($query);

      print "Alter $column default => '$value'<BR>";
      $this->AddMsg("Altering Defaults $column: $query",1,TRUE);
      $this->LogMessage(5,"Altering Default $column: $query");
  }

  //*
  //* function DBTableFields, Parameter list: $table=""
  //*
  //* Returns the data fields in table $table in current DB,
  //* as a list of lists.
  //* 

  function DBTableFields($table,$meta)
  {
      $result=$this->QueryDB("SHOW COLUMNS FROM `".$table."` LIKE '".$meta->name."';");

      $res=$this->MySqlFetchAssoc($result);
      $this->MySqlFreeResult($result);

      return $res;
  }

  //*
  //* function DBTableNFields, Parameter list: $table=""
  //*
  //* Returns number of fields.
  //* 

  function DBTableNFields($table="")
  {
      //Read, but dont read anything
      $result=$this->QueryDB("SELECT * FROM ".$table." WHERE ID='0'");

      $nfields=$this->MySqlFetchNumFields($result);

      $this->MySqlFreeResult($result);

      return $nfields;
  }



//*
//* function GetDBTableFields, Parameter list: 
//*
//* Returns the data fields in table $table in current DB,
//* as a list of lists.
//* 

function GetDBTableFields($table="")
{
    if (count($this->TableFields)!=0) { return $this->TableFields; }

    $table=$this->SqlTableName($table);
    $nfields=$this->DBTableNFields($table);

    $result=$this->QueryDB("SELECT * FROM ".$table." WHERE ID='0'");
    $this->TableFields=array();
    for ($i=0;$i<$nfields;$i++)
    {
        $meta=$this->MySqlFetchField($result,$i);

        $this->TableFields[ $meta->name ]=array();
        foreach
            (
               array
               (
                'blob','max_length','multiple_key','name','not_null',
                'numeric','primary_key','table','type','unique_key','unsigned','zerofill'
               )
               as $key
              )
        {
            $this->TableFields[ $meta->name ][ $key ]=$meta->$key;
        }


        $row = $this->DBTableFields($table,$meta);
        foreach ($row as $key => $value)
        {
            $this->TableFields[ $meta->name ][ $key ]=$value;
        }

        if (preg_match('/^enum\(([^\)]*)\)/',$this->TableFields[ $meta->name ][ "Type" ],$matches))
        {
            $this->TableFields[ $meta->name ][ "Type" ]='enum';
            $vals=$matches[1];
            $vals=preg_replace('/\'/',"",$vals);
            $vals=preg_split('/,/',$vals);
            $this->TableFields[ $meta->name ][ "Values" ]=$vals;
        }
    }

    $this->MySqlFreeResult($result);

    return $this->TableFields;
}

  //*
  //* function DBFieldExists, Parameter list: $table,$data
  //*
  //* Checks whether column $data exists in table $table.
  //* 
  //*

  function DBFieldExists($table,$data)
  {
      $table=$this->SqlTableName($table);
      $result=$this->QueryDB("SHOW COLUMNS FROM ".$table." WHERE Field='".$data."'");

      $exists=FALSE;
      if ($this->MySqlFetchAssoc($result))
      {
          $exists=TRUE;
      }

      $this->MySqlFreeResult($result);

      return $exists;
  }

  //*
  //* function DBFieldsExists, Parameter list: $table,$datas
  //*
  //* Checks whether columns $data exists in table $table.
  //* Returns list of column names.
  //* 
  //*

  function DBFieldsExists($table,$datas)
  {
      $rdatas=array();
      foreach ($datas as $data)
      {
          if ($this->DBFieldExists($table,$data))
          {
              array_push($rdatas,$data);
          }
      }

      return $rdatas;
  }

  //*
  //* function AddDBField, Parameter list: $table,$data,$datadef=array()
  //*
  //* Adds column $data to $table.
  //* 
  //*

  function AddDBField($table,$data,$datadef=array())
  {
      $table=$this->SqlTableName($table);
      if ($this->DBFieldExists($table,$data)) { return; }
      if (count($datadef)==0) { $datadef=$this->ItemData [ $data ]; }

      $sqltype=$datadef[ "Sql" ];
      if (preg_match('/ENUM/',$sqltype))
      {
          $sqltype=$this->GetEnumSpec($datadef);
      }
      elseif (preg_match('/^FILE$/',$sqltype)) { $sqltype="VARCHAR(255)"; }


      if ($data=="Default") { die("Item data named Default unappropriate!"); }

      if (
          !empty($datadef[ "Default" ])
          &&
          !preg_match('/\s+DEFAULT\s+/',$sqltype)
          )
      {
          $sqltype.=" DEFAULT '".$datadef[ "Default" ]."'";
      }

      if ($sqltype!="")
      {
          $query="ALTER TABLE `".$table."` ADD COLUMN ".$data." ".$sqltype;

          $this->AddMsg("Add Column ".$data." in $table: $query<BR>");
          $this->QueryDB($query);
      }
      else
      {
          $this->AddMsg("Add Column ".$data." in $table, no SQL type: $sqltype<BR>");
          $this->AddMsg($datadef);
      }
   }


  //*
  //* function UpdateEnumField, Parameter list: $table,$data
  //*
  //* Updates enum values according to specs.
  //* 
  //*

  function UpdateEnumField($table,$data,$datadef=array())
  {
      if (
          $this->TableFields[ $data ][ "Type" ]=="enum"
          &&
          !empty($datadef)
         )
      {
          $nvalues=0;
          if (
                isset($datadef[ "ValuesMatrix" ])
                &&
                is_array($datadef[ "ValuesMatrix" ])
             )
          {
             foreach ($datadef[ "ValuesMatrix" ] as $id => $values)
             {
                 if (count($values)>$nvalues) { $nvalues=count($values); }
             }
          }
          else
          {
              $values=$datadef[ "Values" ];
              $nvalues=count($values);
          }

          //add values, if number of enums in table insufficient
          if (count($this->TableFields[ $data ][ "Values" ])<=$nvalues)
          {
              $def=array();
              for ($k=0;$k<=$nvalues;$k++) { array_push($def,"'".$k."'"); }
              $def=join(",",$def);

              $query="ALTER TABLE ".$table." MODIFY COLUMN ".$data." ENUM(".$def.")";
              $this->QueryDB($query);

              $this->AddMsg("Mod Column ".$data.": $query");
              $this->LogMessage(5,"Mod Column: ".$query);

              return TRUE;
          }
      }

      return FALSE;
  }


  //*
  //* function UpdateEnumFields, Parameter list: $table,$datas
  //*
  //* Updates enum values according to $this->TableFields specs.
  //* 
  //*

  function UpdateEnumFields($table,$datas)
  {
      $this->GetDBTableFields();

      $updateds=array();
      foreach ($datas as  $data)
      {
         if (!isset($this->TableFields[ $data ])) { continue; }

          $field=$this->TableFields[ $data ];
          if ($field[ "Type" ]=="enum")
          {
              if (!isset($datadef)) { continue; }
          }

          if ($this->UpdateEnumField($table,$data))
          {
              array_push($updateds,$data);
          }
      }

      return $updateds;
  }

  //*
  //* function MySqlFieldLength, Parameter list: $table,$data,$datadef
  //*
  //* Returns field length of field $data.
  //* 
  //*

  function MySqlAlterFieldLength($table,$data,$datadef)
  {
      $query="ALTER TABLE `".$table."` CHANGE $data $data ".$datadef[ "Sql" ];
      $this->QueryDB($query);

      return 0;
  }


  //*
  //* function UpdateDBField, Parameter list: $table,$data,$datadef
  //*
  //* Update data base field $data: modifies existing according to $datas and 
  //* $this->TableFields specifications.
  //* 
  //*

  function UpdateDBField($table,$data,$datadef)
  {
      $table=$this->SqlTableName($table);

      if (empty($datadef[ "Derived" ]) || !$datadef[ "Derived" ])
      {
          if (isset($this->TableFields[ $data ]))
          {
              $field=$this->TableFields[ $data ];

              $this->UpdateEnumField($table,$data,$datadef);

              if (
                  isset($datadef[ "Default" ])
                  &&
                  $field[ "Default" ]!=$datadef[ "Default" ]
                 )
              {
                  $this->SetColumnDefault($table,$data,$datadef[ "Default" ]);
              }
          }
      }

      if (preg_match('/(\(\d+\))/',$datadef[ "Sql" ],$matches))
      {
          $newlen=$matches[1];

          preg_match('/(\(\d+\))/',$this->TableFields[ $data ][ "Type" ],$matches);

          $oldlen=$matches[1];

          if ($oldlen!=$newlen)
          {
              $len=$this->MySqlAlterFieldLength($table,$data,$datadef);
          }
      }
  }

  //*
  //* function UpdateDBFields, Parameter list: $table="",$datas=array()
  //*
  //* Update data base fields: adding new fields
  //* and modifies existing according to $datas and 
  //* $sqltypes specifications.
  //* 
  //*

  function UpdateDBFields($table="",$datas=array(),$datadefs=array(),$maycreate=TRUE)
  {
      $this->TableFields=array();
      $table=$this->SqlTableName($table);
      if (count($datas)==0)
      {
          $datas=array_keys($this->ItemData);
      }

      if (count($datadefs)==0)
      {
          $datadefs=$this->ItemData;
      }


      if ($maycreate && !$this->MySqlIsTable($table))
      {
          $this->CreateTable($table);
      }

      if (!$this->MySqlIsTable($table))
      {
          die("Cannot create 'other table': '".$table."'");
      }

      $this->GetDBTableFields($table);

      $addlist=array();
      $enums=array();
      $addeds=array();
      foreach ($datas as $data)
      {
          if (empty($datadefs[ $data ][ "Derived" ]))
          {
              if (isset($this->TableFields[ $data ]))
              {
                  $this->UpdateDBField($table,$data,$datadefs[ $data ]);
              if ($data=="DayliesNMarkFields") { var_dump($table); }
              }
              else
              {
                  $this->AddDBField($table,$data,$datadefs[ $data ]);
                  array_push($addeds,$data);
              }

              if ($datadefs[ $data ][ "Sql" ]=="ENUM")
              {
                  array_push($enums,$data);
              }
          }
      }

      $updateds=$this->UpdateEnumFields($table,$enums);
      if (method_exists($this,"PostCreateTable"))
      {
          $this->PostCreateTable();
      }

      //$this->MysqlTableIndices($table);

      return array($addeds,$updateds);
  }

  //*
  //* function UnusedFields, Parameter list:
  //*
  //* Returns the list of fields unused in table.
  //* 
  //*

  function UnusedFields()
  {
      $unuseds=array();
      foreach (array_keys($this->TableFields) as $data)
      {
          if (!isset($this->ItemData[ $data ]))
          {
              array_push($unuseds,$data);
          }
      }

      return $unuseds;
  }
  //*
  //* function UnusedSql, Parameter list:
  //*
  //* Returns the list of sql commands eliminating all fields unused in table.
  //* 
  //*

  function UnusedSqls($table)
  {
      $unuseds=$this->UnusedFields();

      $runuseds=array();
      foreach ($unuseds as $unused)
      {
          $runuseds[ $unused ]="ALTER TABLE ".$table." DROP COLUMN ".$unused.";";
      }

      return $runuseds;
  }

  //*
  //* function DropUnusedFields, Parameter list: $table=""
  //*
  //* Drops unused fields in $table.
  //* 
  //*

  function DropUnusedFields($table)
  {
      if ($this->GetPOST("Clean")==1)
      {
          $queries=$this->UnusedSqls($table);

          $dropped=array();
          foreach ($queries as $data => $query)
          {
              if ($this->GetPOST("Drop_".$data)==1)
              {
                  $this->QueryDB($query);
                  array_push($dropped,$data);
              }
          }

          if (count($dropped)>0)
          {
              print $this->H(5,"Dropped: ".join(", ",$dropped));
          }

          //Force reread
          $this->TableFields=array();
          $this->GetDBTableFields($table);
      }
  }

  //*
  //* function HandleSysInfo, Parameter list:
  //*
  //* Shows Table structural Info.
  //* 
  //*

  function HandleSysInfo($table="")
  {
      $table=$this->SqlTableName($table);

      if ($this->GetPOST("Clean")==1)
      {
          $this->DropUnusedFields($table);
      }

      $unused=$this->UnusedSqls($table);

      $datas=array();
      foreach (array_keys($this->TableFields) as $data)
      {
          $datas[ $data ]=1;
      }

      foreach (array_keys($this->ItemData) as $data)
      {
          $datas[ $data ]=1;
      }

      $datas=array_keys($datas);
      sort($datas);

      $titles=$this->B(array("","Details","In DB:","Type","NULL","Default","In Object:","Name","Default","DROP"));
      $rtable=array($titles);
      foreach ($datas as $data)
      {
          $row=array
          (
             $this->B($data.":"),
             $this->Href
             (
                "?ModuleName=".$this->ModuleName."&Action=SysInfo&Data=".$data,
                $this->IMG("../icons/show.gif")
             )
          );

          if (isset($this->TableFields[ $data ]))
          {
              array_push
              (
                 $row,
                 "Y",
                 $this->TableFields[ $data ][ 'type' ],
                 $this->TableFields[ $data ][ 'Null' ],
                 $this->TableFields[ $data ][ 'Default' ]
              );
          }
          else
          {
              array_push($row,"N","","","");
          }

          if (isset($this->ItemData[ $data ]))
          {
              array_push
              (
                 $row,
                 "Y",
                 $this->ItemData[ $data ][ "Name" ],
                 $this->ItemData[ $data ][ "Default" ],
                 ""
              );
          }
          else
          {
              array_push
              (
                 $row,
                 "N",
                 "",
                 "",
                 $this->MakeCheckBox("Drop_".$data,1).$unused[ $data ]
              );
          }

          array_push($rtable,$row);
      }

      $this->SystemMenu();

      print
          $this->H(2,"Table Data Info:").
          $this->H(3,"DB vs. Object");

      if (count($unused)>0)
      {
          print 
              $this->StartForm().
              $this->H(4,"Operação Irreversível!!").
              $this->Center($this->Button("submit","Remover Colunas Selecionadas")).
              $this->MakeHidden("Clean",1);
      }

      $data=$this->GetGET("Data");
      if ($data!="")
      {
          $dtable="";
          $otable="";

          if (isset($this->TableFields[ $data ]))
          {
              $dtable=
                  $this->H(5,"DB").
                  $this->HtmlTable
                  (
                     "",
                     $this->Hash2Table($this->TableFields[ $data ])
                  );
          }
          if (isset($this->ItemData[ $data ]))
          {
              $otable=
                  $this->H(5,"Object").
                  $this->HtmlTable
                  (
                     "",
                     $this->Hash2Table($this->ItemData[ $data ])
                  );
          }

          array_push
          (
             $rtable,
             "<TABLE><TR>\n".
             "  <TD COLSPAN='2'>".$this->H(3,$data.":")."</TD>\n".
             "<TR></TR>\n".
             "  <TD WIDTH='50%'>".$dtable."</TD>\n".
             "  <TD WIDTH='50%'>".$otable."</TD>\n".
             "</TR></TABLE>\n"
          );
      }


      print $this->HtmlTable("",$rtable);

      if (count($unused)>0)
      {
          print 
              $this->EndForm();
      }



  }

    //*
    //* function MysqlTableIndices, Parameter list: $table="",$dbname=""
    //*
    //* Returns lists of Indexes in table.
    //* 
    //* 

    function MysqlTableIndices($table="",$dbname="")
    {
        $table=$this->SqlTableName($table);
        if (empty($dbname)) { $dbname=$this->DBHash[ "DB" ]; }

        $results=$this->QueryDB("SHOW INDEX FROM `".$table."`");
        $nfields=$this->FetchNumFields($results);

        $indices=array();
        for ($i=0;$i<$nfields;$i++)
        {
            $meta=$this->FetchField($results,$i);

            $name=$meta->Key_name;
            $indices[ $name ]=$meta;
        }

        return $indices;
    }
}

?>