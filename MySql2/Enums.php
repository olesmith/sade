<?php

global $ClassList;
array_push($ClassList,"Enums");

class Enums extends DataGroups
{
    //*
    //* Variables of Enums class:
    //*

    //*
    //* function Enums, Parameter list: 
    //*
    //* Constructor.
    //*

    function Enums()
    {
    }

    //*
    //* function InitEnums, Parameter list: $hash=array()
    //*
    //* Initializer.
    //*

    function InitEnums($hash=array())
    {
    }

    //*
    //* function GetDependentEnumValues, Parameter list: $data,$item,$latex=FALSE
    //*
    //* Dtects dependent enum values. Add Latex alternative support.
    //*

    function GetDependentEnumValues($data,$item,$latex=FALSE)
    {
        $depvar=$this->ItemData[ $data ][ "ValuesDependencyVar" ];
        $val=$item[ $depvar ];
        if ($item[ $depvar."_Orig" ]!="")
        {
            $val=$item[ $depvar."_Orig" ];
        }

        $values=array();

        $rval=$val-1;
        if ($rval>=0)
        {
            if (!isset($this->ItemData[ $data ][ "ValuesMatrix" ][ $rval ]))
            {
                $rval=$this->GetEnumValue($depvar,$item);
            }

            if ($latex && is_array($this->ItemData[ $data ][ "ValuesMatrix_Latex" ][ $rval ]))
            {
                $values=$this->ItemData[ $data ][ "ValuesMatrix_Latex" ][ $rval ];
            }
            else
            {
                $values=$this->ItemData[ $data ][ "ValuesMatrix" ][ $rval ];
            }
        }

        return $values;
    }

    //*
    //* function GetEnumValue, Parameter list: $data,$item,$latex=FALSE
    //*
    //* Used $this->ItemData[ $data ][ "Values" ] to obtain
    //* enum value of $data in $item.
    //* Implements also a ValuesMatrix, so that dependent of another
    //* ENUM entry, the actual values may change. Ex:
    //* Bibliography Area and Subarea.
    //* Substitutes enum nos with text values in $item, for. One enum.
    //* If $latex is true and $this->ItemData[ $data ] has 
    //* "Values_Latex" key, these are used instead of the the "Values" key.
    //* Also tests if $data"_Orig" key is set in $item.
    //* If it is, this means that Enums value has been substituted in
    //* $item, and so, the value to regenerate should be $data"_Orig" and
    //* not $data. Routines that substitutes enums, should set $data"_Orig".
    //*
    //* Data may be of type ENUM, SqlTable/Object  and Derived.
    //*
    //* Returns enum value.
    //*

    function GetEnumValue($data,$item,$latex=FALSE)
    {
        $type=$this->ItemData[ $data ][ "Sql" ];

        $value="";        
        if (!isset($item[ $data ]))
        {
            //var_dump($item);
            return $value;
        }

        if ($this->LatexMode()) { $latex=TRUE; }


        $value=$item[ $data ];        
        if ($this->DataIsEnum($data))
        {
            $values=array();
            if (
                is_array($this->ItemData[ $data ][ "ValuesMatrix" ]) &&
                $this->ItemData[ $data ][ "ValuesDependencyVar" ]!=""
               )
            {
                $values=$this->GetDependentEnumValues($data,$item,$latex);
            }
            elseif ($latex && isset($this->ItemData[ $data ][ "Values_Latex" ]))
            {
                $values=$this->GetRealNameKey($this->ItemData[ $data ],"Values_Latex");
            }
            else
            {
                $values=$this->GetRealNameKey($this->ItemData[ $data ],"Values");
            }

            if (!isset($item[ $data."_Orig" ]) || $item[ $data."_Orig" ]=="")
            {
                if ($value && isset($values[ $value-1 ]))
                {
                    $value=$values[ $value-1 ];
                }
            }
            else
            {
                $value=$item[ $data ];
            }

            if (!empty($this->ItemData[ $data ][ "EmptyName" ]))
            {
                if ($value==0 || empty($value))
                {
                    $value=$this->ItemData[ $data ][ "EmptyName" ];
                }
            } 

            if (preg_match('/^\d+$/',$value) && intval($value)==0) { return ""; }

       }
        elseif ($this->DataIsDerived($data))
        {
            if ($this->ItemData[ $data ][ "SqlDerivedNamer" ]!="")
            {
                $ddata=$data."_".$this->ItemData[ $data ][ "SqlDerivedNamer" ];
                $value=$item[ $ddata ];
            }
            elseif ($this->ItemData[ $data ][ "Derived" ]!="" &&
                    preg_match('/#/',$this->ItemData[ $data ][ "DerivedFilter" ]))
            {
                $filter=$this->ItemData[ $data ][ "DerivedFilter" ];
                $value=$this->Filter($filter,$item);
            }
        }
        elseif ($this->ItemData[ $data ][ "SqlObject" ])
        {
            if (isset($item[ $data ]) && $item[ $data ]>0)
            {
                $object=$this->GetSubObject($data);

                $value=$item[ $data ];

                //Cargada? Room calls rooms
                if ($object->ModuleName==$this->ModuleName) { return $value; }

                $value=$object->GetItemName($value,$this->ItemData[ $data ][ "SqlDerivedData" ]);
            }
            else { $value=""; }
        }
        elseif ($this->DataIsSqlType($data))
        {
            $itemnamefilter=$this->ItemData[ $data ][ "SqlDerivedFilter" ];
            $value=$this->Filter($itemnamefilter,$item);
        }


        return $value;
    }

    //*
    //* function ApplyEnums, Parameter list: $item=array(),$latex=FALSE
    //*
    //* Apply enum values to all entries in $item. If updated, sets 
    //* the keys in $item, and for safety, stores the original value in
    //* $data_Orig.
    //*
    //* Parameter $latex is used when calling GetEnumValue above.
    //* 

    function ApplyEnums($item=array(),$latex=FALSE)
    {
        if (count($item)==0) { $item=$this->ItemHash; }
        if (count($item)==0) { return; }
        foreach ($this->DatasRead as $no => $data)
        {
            if ($this->ItemData[ $data ][ "Sql" ]=="ENUM" && isset($item[ $data."_Orig" ]))
            {
                $values=array();

                if ($item[ $data ]>0)
                {
                    $values=$this->GetDependentEnumValues($data,$item,$latex);
                    $value=$item[ $data ];
                    $item[ $data."_Orig" ]=$value;

                    if (is_array($values))
                    {
                        $value=$values[ $value-1 ];
                    }
                    $item[ $data ]=$value;
                }
            }
        }

        return $item;
    }


    //*
    //* function ApplyEnums, Parameter list: $item=array(),$latex=FALSE
    //*
    //* Apply enum values to all entries in $item. If updated, sets 
    //* the keys in $item, and for safety, stores the original value in
    //* $data_Orig.
    //*
    //* Parameter $latex is used when calling GetEnumValue above.
    //* 


    function ApplyAllEnums($item=array(),$latex=FALSE)
    {
        if (count($item)>0) {} else { $item=$this->ItemHash; }

        foreach ($this->DatasRead as $no => $data)
        {
            if ($this->DataIsEnum($data) || $this->DataIsDerived($data) || $this->DataIsSqlType($data))
            {
                if (!isset($item[ $data."_Orig" ]) || $item[ $data."_Orig" ]=="")
                {
                    $value="";
                    if (isset($item[ $data ])) { $value=$item[ $data ]; }
                    $item[ $data ]=$this->GetEnumValue($data,$item,$latex);
                    $item[ $data."_Orig" ]=$value;
                }
                elseif (preg_match('/^[AMC]Time$/',$data) && preg_match('/^\d+$/',$item[ $data ]))
                {
                    $item[ $data ]=$this->TimeStamp2Text($item[ $data ]);
                }
            }
            elseif (isset($this->ItemData[ $data ][ "IsDate" ]) && $this->ItemData[ $data ][ "IsDate" ])
            {
                $item[ $data ]=$this->CreateDateShowField($data,$item,$item[ $data ]);
            }
        }

        return $item;
    }
}
?>