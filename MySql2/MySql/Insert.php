<?php

class MySqlInsert extends MySqlQuery
{
    //*
    //* function InsertItem, Parameter list: $table,$item
    //*
    //* Adds $item (assoc array) to DB table $table
    //* 
    //* 

    function MySqlInsertItem($table,&$item)
    {
        if ($table=="") { $table=$this->SqlTableName($table); }

        $query1="";
        $query2="";
        foreach ($item as $key => $value)
        {
            $query1.="$key, ";
            $query2.="'$value', ";
        }

        $query1=preg_replace('/,\s$/',"",$query1);
        $query2=preg_replace('/,\s$/',"",$query2);
        $query="INSERT INTO $table (".$query1.") VALUES (".$query2.")";

        $result = $this->QueryDB($query);

        $item[ "ID" ]=$this->GetInsertID();

        return $result;
    }

    //*
    //* function MySqlInsertUnique, Parameter list: $table,$where,&$item,$namekey="ID"
    //*
    //* Testt whether $item should be added or updated:
    //* If $this->SelectUniqueHash() returns an empty set, adds -
    //* Otherwise updates.
    //* 

    function MySqlInsertUnique($table,$where,&$item,$namekey="ID")
    {
        if ($table=="") { $table=$this->SqlTableName($table); }

        $ritem=$this->SelectUniqueHash
        (
           $table,
           $where,
           TRUE,
           array("ID")
        );

        if (empty($ritem))
        {
            foreach (array("ATime","CTime","MTime") as $key)
            {
                $item[ $key ]=time();
            }

            $res=$this->MySqlInsertItem($table,$item);

            return 1;
        }

        return -1;
    }
}

?>