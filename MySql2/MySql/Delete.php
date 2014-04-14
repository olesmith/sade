<?php


class MySqlDelete extends MySqlUpdate
{

    //*
    //* function MySqlDeleteItem, Parameter list: $table,$id
    //*
    //* Deletes Item with id $id from table $table
    //* 
    //* 

    function MySqlDeleteItem($table,$id,$var="ID")
    {
        $table=$this->SqlTableName($table);
        $query="DELETE FROM $table WHERE ".$var."='".$id."'";

        return $this->QueryDB($query);
    }

    //*
    //* function MySqlDeleteItems, Parameter list: $table,$where
    //*
    //* Deletes Item with id $id from table $table
    //* 
    //* 

    function MySqlDeleteItems($table,$where)
    {
        $table=$this->SqlTableName($table);
        if (is_array($where)) { $where=$this->Hash2SqlWhere($where); }

        $query="DELETE FROM $table WHERE $where";

        return $this->QueryDB($query);
    }
}

?>