<?php


class ClassDiscsStatusTableCGI extends ClassDiscsAccess
{
    //*
    //* function IncludeSearchData, Parameter list: $data
    //*
    //* Chekcs wheter search data cell is to be included  -
    //* and, therefore checked in search rows.
    //* 
    //*

    function IncludeSearchData($data)
    {
        $val=$this->DiscStatusTrimesterData[ $data ][ "Checked" ];

        if ($this->SearchPressed())
        {
            $val=$this->GetPOST("Data_".$data);

            if ($val==1) { $val=TRUE; }
            else         { $val=FALSE; }
        }

        return $val;
    }
 
    //*
    //* function IncludeSearchExtended, Parameter list: $data
    //*
    //* Chekcs wheter search extended data cell is to be included  -
    //* and, therefore checked in search rows.
    //* 
    //*

    function IncludeSearchExtended($data)
    {
        $val=$this->DiscStatusTrimesterExtended[ $data ][ "Checked" ];

        if ($this->SearchPressed())
        {
            $val=$this->GetPOST("Extended_".$data);

            if ($val==1) { $val=TRUE; }
            else         { $val=FALSE; }
        }

        return $val;
    }
 
}

?>