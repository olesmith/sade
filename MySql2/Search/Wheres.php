<?php

class SearchWheres extends SearchTable
{
    //*
    //* function GetSearchVarWhere, Parameter list: $data,$datavalues=array()
    //*
    //* Generates pre sql search vars where, based on $data and 
    //*

    function GetSearchVarWhere($data,$datavalues=array())
    {
        $where="";
        if ($this->ItemData[ $data ][ "SqlMethod" ])
        {
            $method=$this->ItemData[ $data ][ "SqlMethod" ];
            $res=$this->$method($data);

            if ($res!="")
            {
                $where=$this->$method($data);
            }  
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="ENUM")
        {
            if ($this->ItemData[ $data ][ "SearchCheckBox" ])
            {
                if (count($datavalues)>0)
                {
                    $where="IN ('".join("','",$datavalues)."')";
                }
            }
            else
            {
                $where=$datavalues;
            }
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="INT")
        {
            $where=$datavalues;
            if (preg_match('/[_%]/',$datavalues))
            {
                $where=" LIKE '".$datavalues."'";         
            }
        }
        elseif ($this->ItemData[ $data ][ "SearchCompound" ])
        {
            $var=$this->ItemData[ $data ][ "Var" ];

            $ors=array();
            for ($i=1;$i<=$this->ItemData[ $data ][ "NVars" ];$i++)
            {
                array_push($ors,$var.$i."='".$datavalues."'");
            }

            $where="(".join(" OR ",$ors).")";
        }
        else
        {
            if (is_array($datavalues))
            {
                if (count($datavalues)>0)
                {
                    $ors=array();
                    foreach ($datavalues as $no => $val)
                    {
                        array_push($ors,$data."='".$val."'");
                    }

                    $orwhere=join(" OR ",$ors);
                    if (count($ors)>1)
                    {
                        $orwhere="(".$orwhere.")";
                    }
                    $where=$orwhere;
                }
            }
            elseif (preg_match('/[_%]/',$datavalues))
            {
                $where=" LIKE '".$datavalues."'";         
            }
            else
            {
                $where=" LIKE '%".$datavalues."%'";         
            }
        }

        return $where;
    }


   //*
    //* function GetSearchVarsWhere, Parameter list: $searchvars=array()
    //*
    //* Generates pre sql search vars where, that is for  all data in
    //* $searchvars (or all search data, if empty), $data=$value
    //* pairs for all ENUM and SQL types.
    //*

    function GetSearchVarsWhere($searchvars=array())
    {
        $values=$this->GetPreSearchVars();
        if (count($searchvars)==0)
        {
            $searchvars=array_keys($values);
        }

        $wheres=array();
        foreach ($searchvars as $id => $data)
        {
            $where=$this->GetSearchVarWhere($data,$values[ $data ]);

            if (!empty($where) && preg_match('/\S/',$where))
            {
                $where=preg_replace('/^'.$data.'=?\s+/',"",$where);
                $wheres[ $data ]=$where;
            }
        }

        return $wheres;
    }
}


?>