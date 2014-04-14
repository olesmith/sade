<?php


class MySqlSelect extends Actions
{
//*
//* function GetDBTable, Parameter list: $table,$where,$data
//*
//* Returns list of items in Tables. One ass. array per item
//* 
//* 

function GetDBTable($table,$where,$datalist)
{
    $table=$this->SqlTableName($table);
    $items=array();
    if (! preg_match("/\S/",$table)) { return $items; }

    if (preg_match("/\S/",$where))
    {
        $where=" WHERE ".$where; 
    }

    $data="*";
    if (count($datalist)>0) { $data=join(",",$datalist); }
    else                    { $datalist=GetDBTableFields($table); }

    $query="SELECT ".$data." FROM `".$table."`;"; ##.$where;
    $result=$this->QueryDB($query);

    $res=$this->MySqlFetchResultAssocList($result);

    $this->MysqlFreeResult($result);

    return $res;
}



//*
//* function ShowDBTable, Parameter list: $table,$where,$data
//*
//* Shows list of items in Tables. One ass. array per item
//* 
//* 

function ShowDBTable($table,$wherespec,$datalist)
{
    $table=$this->SqlTableName($table);
    $rows=$this->GetDBTable($table,$wherespec,$datalist);

    if (count($datalist)==0) { $datalist=$this->GetDBTableFieldNames($table); }

    echo $this->HTMLTable($datalist,$rows);
}

//*
//* function SelectFromTable, Parameter list: $table,$where,$fieldnames
//*
//* Perform a select query on Table $table in the current DB.
//* Returns each match as a hash of the field names in
//* in $fieldnames or all data if $filednames is not an array.. 
//*
//* 

function SelectFromTable($table,$where,$fieldnames=array())
{
    $table=$this->SqlTableName($table);
    $rfieldnames=$this->SqlDataFields($table,$fieldnames);
    $fieldnames=array();
    if (count($fieldnames)==0)
    {
        $fieldnames=array_keys($this->ItemData);
        $rfieldnames="*";
    }
    else
    {
        $fieldnames=preg_grep('/^No$/',$rfieldnames,PREG_GREP_INVERT);
        $rfieldnames=join(",",$fieldnames);
    }

    $rquery='SELECT '.$rfieldnames.' FROM `'.$table."`";

    if (preg_match('/\S/',$where)) { $rquery.=' WHERE '.$where; }
    $result = $this->QueryDB($rquery);

    $res=$this->MySqlFetchResultAssocList($result,$fieldnames);
    $this->MysqlFreeResult($result);

    return $res;
}

//*
//* function Hash2SqlWhere, Parameter list: $hash
//*
//* Translates a hash to where clause, padded with AND's.
//* Reverse: SqlClause2Hash.
//*
//* 

function Hash2SqlWhere($hash)
{
    $where="";
    if (is_array($hash))
    {
        $wheres=array();
        foreach ($hash as $key => $value)
        {
            if (is_array($value))
            {
                if (isset($value[ "Qualifier" ]))
                {
                    array_push($wheres,$key." ".$value[ "Qualifier" ]." ".$value[ "Values" ]."");
                }
                else
                {
                    array_push($wheres,$key." IN ('".join("','",$value)."')");
                }
            }
            elseif (preg_match('/\s*IN\s/',$value))
            {
                array_push($wheres,$key." ".$value);
            }
            elseif (preg_match('/^\(.+\)$/',$value))
            {
                array_push($wheres," ".$value);
            }
            elseif (preg_match('/(>=?)\s/',$value,$matches))
            {
                array_push($wheres,$key.$matches[1].$value);
            }
            elseif (preg_match('/(<=?)\s/',$value))
            {
                array_push($wheres,$key.$matches[1].$value);
            }
            elseif (preg_match('/[%_]/',$value))
            {
                if (!preg_match('/\bLIKE\b/',$value))
                {
                    array_push($wheres,$key." LIKE '".$value."'");
                }
                else
                {
                    array_push($wheres,$key." ".$value);
                }
            }
            else
            {
                array_push($wheres,$key."='".$value."'");
            }
       }

        $where=join(" AND ",$wheres);
    }

    return $where;
}

//*
//* function SelectHashesFromTable, Parameter list: $table,$where,$fieldnames,$byid=FALSE,$orderby=""
//*
//* Perform a select query on Table $table in the current DB.
//* Returns each match as a hash of the field names in
//* in $fieldnames or all data if $fieldnames is not an array.
//*
//* 

function SelectHashesFromTable($table="",$where="",$fieldnames=array(),$byid=FALSE,$orderby="")
{
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    $table=$this->SqlTableName($table);
    $rfieldnames=$this->SqlDataFields($table,$fieldnames);

    $rquery='SELECT '.$rfieldnames.' FROM `'.$table.'`';
    if (preg_match('/\S/',$where)) { $rquery.=' WHERE '.$where; }
    if (preg_match('/\S/',$orderby)) { $rquery.=' ORDER BY '.$orderby; }

    $result = $this->QueryDB($rquery);

    $res=$this->MySqlFetchResultAssoc($result,$byid);

    $this->MysqlFreeResult($result);

    return $res;
}





//*
//* function SelectUniqueHash, Parameter list: $table,$where,$noecho
//*
//* Perform a select query on Table $table in the current DB.
//* Returns each match as a hash of the field names in
//* in $fieldnames or all data if $filednames is not an array.. 
//*
//* 

  function SelectUniqueHash($table,$where,$noecho=FALSE,$sqldata=array())
{
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    if ($table=="") { $table=$this->SqlTableName($table); }

    if (count($sqldata)==0) { $sqldata="*"; }

    $items=$this->SelectHashesFromTable($table,$where,$sqldata);

    $item=NULL;
    if (count($items)==0 && !$noecho)
    { 
        $this->AddMsg
        (
           $this->ModuleName.
           ": SelectUniqueHash: No such item in table $table: $where'",
           2
        );
    }
    elseif (count($items)>1 && !$noecho)
    {
        $this->AddMsg
        (
           $this->ModuleName.
           ": More than one item in table $table: '$where'",
           2
        );
    }


    if (count($items)>=1){ $item=$items[0]; }

    return $item;
}

//*
//* function MySqlItemValue, Parameter list: $table,$idvar,$id,$var,$noecho
//*
//* Returns value of var $var of item with key $idvar $id in table $table. 
//*
//* 

function MySqlItemValue($table,$idvar,$id,$var,$noecho=FALSE)
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    $items=$this->SelectHashesFromTable($table,$idvar."='".$id."'",array($var));

    if (count($items)==0)
    {
        if (!$noecho && $id!="")
        {
            print "MySqlItemValue: No such item in $table: $idvar='$id'";
            var_dump(debug_backtrace(FALSE));
        }
    }
    elseif (count($items)>1)
    {
        if (!$noecho && $id!="") { print "More than one item in $table: $idvar=$id"; }
        return $items[0][ $var ];
    }
    else
    {
        return $items[0][ $var ];
    }

    return "";
}

///*
//* function MySqlItemValues, Parameter list: $table,$idvar,$id,$vars,$noecho
//*
//* Returns values of vars $var of item with key $idvar $id in table $table. 
//*
//* 

function MySqlItemValues($table,$idvar,$id,$vars,$noecho=FALSE)
{
    if ($table=="") { $table=$this->SqlTableName($table); }

    $items=$this->SelectHashesFromTable($table,"$idvar='$id'",$vars);

    $item=NULL;
    if (count($items)==0)
    {
        if (!$noecho && $id!="")
        {
            print "MySqlItemValues: No such item in $table: $idvar='$id'";
            var_dump(debug_backtrace(FALSE));
        }
    }
    elseif (count($items)>1)
    {
        if (!$noecho && $id!="") { print "More than one item in $table: $where"; }
        $item=$items[0];
    }
    else
    {
        $item=$items[0];
    }

    return $item;
}

//*
//* function MySqlUniqueColValues, Parameter list: $table,$col,$where="",$groupby="",$orderby=""
//*
//* Returns a list of unique column values in table $table. 
//*
//* 

function MySqlUniqueColValues($table,$col,$where="",$groupby="",$orderby="")
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    $query="SELECT ".$col." FROM `".$table."`";
    if ($where!="") { $query.=" WHERE ".$where; }

    if (empty($groupby)) { $groupby=$col; }
    $query.=" GROUP BY ".$groupby;

    if (!empty($orderby))
    {
        $query.=" ORDER BY ".$orderby;
    }

    $result=$this->QueryDB($query);

    $vals=$this->MySqlFetchResultAssocColumns($result,$col);

    $rvals=array();
    foreach ($vals as $id => $val)
    {
        $rvals[ $val ]=$val;
    }

    $this->MysqlFreeResult($result);

    return array_keys($rvals);
}


//*
//* function MakeSureWeHaveRead, Parameter list: $table,$item,$datas
//*
//* Makes sure that datas in $datas has been read in item $item.
//*
//* 

function MakeSureWeHaveRead($table,$item,$datas)
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    if (!is_array($datas)) { $datas=array($datas); }

    $rdatas=array();
    foreach ($datas as $id => $data)
    {
        if (!isset($item[ $data ]) || $item[ $data ]=="")
        {
            array_push($rdatas,$data);
        }
    }

    $ritem=$this->MySqlItemValues($table,"ID",$item[ "ID" ],$rdatas);
    foreach ($rdatas as $id => $data)
    {
        $item[ $data ]=$ritem[ $data ];
    }

    return $item;
}

function MySqlNEntries($table,$where="")
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    $query="SELECT COUNT(*) FROM `".$table."`";
    if ($where!="")
    {
        $query.=" WHERE ".$where;
    }

    $result = $this->QueryDB($query);

    $res=$this->MySqlFetchFirstEntry($result);

    $this->MySqlFreeResult($result);

    return $res;
}

///*
//* function MySqlItemsValue, Parameter list: $table,$idvar,$ids,$var,$noecho
//*
//* Returns a list of hashes of vars $var of item with key $idvar in list $ids in table $table. 
//*
//* 

function MySqlItemsValue($table,$idvar,$ids,$var,$noecho=FALSE)
{
    if ($table=="") { $table=$this->SqlTableName($table); }

    $values=array();
    foreach ($ids as $id)
    {
        array_push($values,$this->MySqlItemValue($table,$idvar,$id,$var,$noecho));
    }

    return $values;
}

///*
//* function MySqlItemsValues, Parameter list: $table,$idvar,$ids,$vars,$noecho
//*
//* Returns a list of hashes of vars $var of item with key $idvar in list $ids in table $table. 
//*
//* 

function MySqlItemsValues($table,$idvar,$ids,$vars,$noecho=FALSE)
{
    if ($table=="") { $table=$this->SqlTableName($table); }

    $items=array();
    foreach ($ids as $id)
    {
        $items[ $id ]=$this->MySqlItemValues($table,$idvar,$id,$vars,$noecho);
    }

    return $items;
}

///*
//* function MySqlSumNEntries, Parameter list: $table,$where,$fields
//*
//* Sums entries $field value, conforming to $where, in table $table.
//*
//* 

function MySqlSumNEntries($table,$where,$fields)
{
    $data=$fields;
    if (!is_array($fields))
    {
        $fields=array($fields);
    }

    $items=$this->SelectHashesFromTable($table,$where,$fields);

    $counts=array();
    foreach ($fields as $field) { $counts[ $field ]=0; }

    foreach ($items as $item)
    {
        foreach ($fields as $field)
        {
            $counts[ $field ]+=$item[ $field ];
        }
    }

    if (is_array($data))
    {
        return $counts;
    }

    return $counts[ $data ];
}

///*
//* function RowAverage, Parameter list: $table,$where,$field
//*
//* Uses SQL to obtain average.
//*
//* 

function RowAverage($table,$where,$field)
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    $query="SELECT AVG(".$field.") FROM `".$table."` WHERE ".$where;
    $result = $this->QueryDB($query);

    $res=$this->MySqlFetchFirstEntry($result);

    $this->MySqlFreeResult($result);

    return $res;
}

///*
//* function RowSum, Parameter list: $table,$where,$field
//*
//* Uses SQL to obtain saum.
//*
//* 

function RowSum($table,$where,$field)
{
    if ($table=="") { $table=$this->SqlTableName($table); }
    if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

    $query="SELECT SUM(".$field.") FROM `".$table."` WHERE ".$where;
    $result = $this->QueryDB($query);

    $res=$this->MySqlFetchFirstEntry($result);

    $this->MySqlFreeResult($result);

    return $res;
}


}

?>