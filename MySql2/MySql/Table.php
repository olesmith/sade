<?php


class MySqlTable extends MySqlSelect
{
    //*
    //* function SqlTableName, Parameter list: $table=""
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //*

    function SqlTableName($table="")
    {
        $module=$this->ModuleName;

        if ($table=="") { $table=$this->SqlTable; }
        if ($table=="" && isset($this->DBHash[ "Table" ])) { $table=$this->DBHash[ "Table" ]; }

        foreach ($this->SqlTableVars as $id => $key)
        {
            if (isset($this->$key))
            {
                $value=$this->$key;
                if (is_array($value))
                {
                    foreach ($this->$key as $rkey => $rvalue)
                    {
                        if (!is_array($rvalue))
                        {
                            $table=preg_replace('/#'.$rkey.'/',$rvalue,$table);
                        }
                    }
                }
                else
                {
                    $table=preg_replace('/#'.$key.'/',$value,$table);
                }
            }
        }

        foreach ($this->ExtraPathVars as $id => $key)
        {
            $value=$this->$key;
            $table=preg_replace('/#'.$key.'/',$value,$table);
        }

        $table=preg_replace('/\./',"_",$table);
        $table=preg_replace('/#Module/',$module,$table);

        return $table;
    }





    //*
    //* function MySqlIsTable, Parameter list: $table=""
    //*
    //* Checks whether table $table exists.
    //*
    //* 

    function MySqlIsTable($table="")
    {
      $table=$this->SqlTableName($table);

      $tablenames=$this->GetDBTableNames($table);
      if (preg_grep("/^$table$/",$tablenames))
      {
          return TRUE;
      }
      else
      {
          return FALSE;
      }
    }

    //*
    //* function CreateTable, Parameter list: $table=""
    //*
    //* Creates Table according to SQL specification in $vars,
    //* if it does not exist already.
    //*
    //* 

    function CreateTable($table="")
    {
        $table=$this->SqlTableName($table);

        $tablenames=$this->GetDBTableNames($table);
        $rtables=preg_grep("/^$table$/",$tablenames);
        if (count($rtables)>0)
        {
            return;
        }

        if ($table!="")
        {
            $this->LogMessage("Create Table","Table $table created");
            $query="CREATE TABLE `".$table."` (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT);";

            $this->AddMsg("Table $table created: ".$query);
            $this->QueryDB($query);
        }
    }

    //*
    //* function CreateTableWithVars, Parameter list: $table,$vars
    //*
    //* Creates Table, according to SQL specification in $vars,
    //* if it does not exist already.
    //*
    //* 

    function CreateTableWithVars($table,$vars)
    {
        if ($table=="") { return; }

        $table=$this->SqlTableName($table);
        $tablenames=$this->GetDBTableNames($table);
        $rtables=preg_grep("/^$table$/",$tablenames);

        if (count($rtables)>0)
        {
            $this->AddMsg("Table $table already exists");
            return;
        }

        $query="CREATE TABLE $table (";
        $first=0;
        foreach ($vars as $key => $sqltype)
        {
            if (preg_match('/^FILE$/',$sqltype)) { $sqltype="VARCHAR(255)"; }
        
            if (preg_match('/^ENUM/',$sqltype))
            {
                $sqltype=$this->GetEnumSpec($sqltype);
            }

            if ($first!=0) { $query.=","; }
            $query.=" $key $sqltype";
            $first=1;
        }
        $query.=");";
    
        $this->QueryDB($query);
    }

//*
//* function DropTable, Parameter list: $table
//*
//* Simply drops the table.
//*
//* 

function DropTable($table)
{
    if ($table=="") { return; }
    $tablenames=$this->GetDBTableNames($table);
    $rtables=preg_grep('/^'.$table.'$/',$tablenames);

    if (count($rtables)>0)
    {
        $query="DROP TABLE $table";
        $this->QueryDB($query);
        $this->AddMsg("Table $table dropped");
    }
}

//*
//* function TableHashField, Parameter list: $table
//*
//* Returns names of the data fields in table $table in current DB, as a list.
//* 
//* 

function TableHashField($table,$field)
{

    $table=$this->SqlTableName($table);
    $fields=$this->GetDBTableFieldNames($table);
    if (preg_grep('/^'.$field.'$/',$fields))
    {
        return TRUE;
    }

    return FALSE;
}


//*
//* function GetDBTableFieldNames, Parameter list: $table
//*
//* Returns names of the data fields in table $table in current DB, as a list.
//* 
//* 

function GetDBTableFieldNames($table)
{
    $table=$this->SqlTableName($table);
    if ($table=="") { return array(); }

    $result = $this->QueryDB('SHOW COLUMNS FROM '.$table);

    $count=$this->MySqlFetchNumRows($result);

    $fieldnames=array();

    $m=0;
    while ($row=$this->MySqlFetchAssoc($result))
    {
        $fieldnames[$m]=$row[ "Field" ];
        $m++;
    }  

    $this->MysqlFreeResult($result);

    return $fieldnames;
}



//*
//* function GetDBTableNames, Parameter list:
//*
//* Returns list with the names of the Tables in database $dbname.
//* 
//* 

function GetDBTableNames()
{
    $result = $this->QueryDB('SHOW TABLES');

    $names=array();
    $m=0;
    while ($row=$this->MySqlFetchAssoc($result))
    {
        foreach ($row as $key => $value)
	    {
            $table=$value;
        }

        $names[$m]=$value;
        $m++;
    }

    $this->MysqlFreeResult($result);

    return $names;
}

//*
//* function SqlDataFields, Parameter list: $table,$fieldnames
//*
//* Returns list of fields in $fieldnames, that are actually fields in $table. 
//*
//* 

function SqlDataFields($table,$fieldnames=array())
{
    $rfieldnames="*";
    $sqldata=$this->GetDBTableFieldNames($table);

    if (is_array($fieldnames) && count($fieldnames)>0)
    {
        $rfieldnames=array();
        foreach ($fieldnames as $data)
        {
            if (preg_grep('/^'.$data.'$/',$sqldata))
            {
                array_push($rfieldnames,$data);
            }
        }

        $rfieldnames=join(", ",$fieldnames);
    }

    return $rfieldnames;
}


}

?>