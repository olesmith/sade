<?php

class SubItems extends Enums
{
    //*
    //* function GetSubObject, Parameter list: $data
    //*
    //* Returns the sub table object.
    //*

    function GetSubObject($data)
    {
        $itemdata=$this->GetSubObjectItemData($data);
        if (isset($itemdata[ "SqlObject" ]))
        {
            $subobject=$itemdata[ "SqlObject" ];
            if (isset($this->$subobject))
            {
                return $this->$subobject;
            }
            elseif (isset($this->ApplicationObj->Modules[ $subobject ]))
            {
                return $this->ApplicationObj->Modules[ $subobject ];
            }
            elseif (isset($this->ModuleObj->$subobject))
            {
                return $this->ModuleObj->$subobject;
            }
        }
        else
        {
            var_dump($itemdata);
            exit();
        }

        return NULL;
    }

    //*
    //* function GetSubObjectItemData, Parameter list: $data
    //*
    //* Returns the sub table object.
    //*

    function GetSubObjectItemData($data)
    {
        //return $this->ItemData[ $data ];

        $class=$this->ItemData[ $data ][ "SqlClass" ];

        $itemdata=$this->ApplicationObj->SubModulesVars[ $class ];
        foreach ($this->ItemData[ $data ] as $key => $value)
        {
            if (is_array($value))
            {
                if (!isset($itemdata[ $key ]))
                {
                    $itemdata[ $key ]=array();                    
                }

                foreach ($value as $rkey => $rvalue)
                {
                    $itemdata[ $key ][ $rkey ]=$rvalue;
                }
            }
            else
            {
                $itemdata[ $key ]=$value;
            }
        }

        return $itemdata;
    }

    //*
    //* function ReadSubItem, Parameter list: $data,$id
    //*
    //* Reads and stores subitem, if not previously read.
    //*

    function ReadSubItem($data,$id,$item=array())
    {
        if ($id==0 || $id=="") { return array(); }

        $itemdata=$this->GetSubObjectItemData($data);

        if (isset($itemdata[ "SqlObject" ]))
        {
            $subobject=$this->GetSubObject($data);
            if ($subobject)
            {
                if (
                      !isset($subobject->ItemHashes[ $id ])
                      ||
                      (
                       isset($subobject->ItemHashes[ $id ][ "ID" ])
                       &&
                       $subobject->ItemHashes[ $id ][ "ID" ]!=$id
                      )
                   )
                {
                    $datas=$this->ItemData[ $data ][ "SqlDerivedData" ];

                    array_unshift($datas,"ID");
                    $subobject->ReadItem($id,$datas);
                    if (count($subobject->ItemHash)==0)
                    {
                        $this->AddMsg
                        (
                           $subobject->SqlWhere.
                           ", Empty object ID=$id, '$data' in ".
                           $this->ModuleName
                        );
                    }
                 }

                 if (isset($subobject->ItemHashes[ $id ]))
                 {
                     return $subobject->ItemHashes[ $id ];
                 }
            }
            else
            {
                $this->AddMsg($this->ModuleName.", Error!! No subobject '$data': ".$subobject." - ID: $id");
            }

            return array();
        }

        print $this->AddMsg($this->ModuleName.", Error!! No such '$data' subitem: $id");

        return array();
    }


     //*
    //* function ReadAllSubItems, Parameter list: $data,$itemid
    //*
    //* Reads all the subitems.
    //*

    function ReadAllSubItems($data,$item)
    {
        $itemdata=$this->GetSubObjectItemData($data);

        if ($itemdata[ "SqlObject" ] && !isset($itemdata[ "ItemsRead" ]))
        {
            $subobject=$itemdata[ "SqlObject" ];

            if (isset($this->$subobject))
            {
                $subobject=$this->$subobject;
            }
            else
            {
                $subobject=$this->ApplicationObj->$subobject;
            }

            if (!$subobject) { print $itemdata[ "SqlObject" ]." not loaded"; exit(); }

            $subobject->ItemHashes=array();
            $subobject->SqlWhere="";
            if (!empty($this->ItemData[ $data ][ "SqlWhere" ]))
            {
                $subobject->SqlWhere=$this->ItemData[ $data ][ "SqlWhere" ];
            }
            elseif (!empty($this->ItemData[ $data ][ "SqlWhere".$this->Profile ]))
            {
                $subobject->SqlWhere=$this->ItemData[ $data ][ "SqlWhere".$this->Profile ];
            }
            elseif (!empty($this->ItemData[ $data ][ "SqlWhere".$this->LoginType ]))
            {
                $subobject->SqlWhere=$this->ItemData[ $data ][ "SqlWhere".$this->LoginType ];
            }

            $subobject->SqlWhere=$this->FilterHash($subobject->SqlWhere,$this->LoginData);
            $subobject->SqlWhere=$this->FilterHash($subobject->SqlWhere,$item);
            $subobject->SqlWhere=$this->ApplicationObj->FilterObject($subobject->SqlWhere);
            $groupby="";

            if (!empty($this->ItemData[ $data ][ "SqlGroupBy" ]))
            {
                $groupby=$this->ItemData[ $data ][ "SqlGroupBy" ];
            }

            $orderby="";
            if (!empty($this->ItemData[ $data ][ "SqlOrderBy" ]))
            {
                $orderby=$this->ItemData[ $data ][ "SqlOrderBy" ];
            }

            $subobject->ItemData[ $data ]=$itemdata;
            $this->ItemData[ $data ][ "IDs" ]=$subobject->MySqlUniqueColValues
            (
               "",
               "ID",
               $subobject->GetRealWhereClause($subobject->SqlWhere,$data),
               $groupby,
               $orderby
            );

            $this->ItemData[ $data ][ "IDs" ]=preg_grep
            (
               '/^0$/',
               $this->ItemData[ $data ][ "IDs" ],
               PREG_GREP_INVERT
             );


 
            foreach ($this->ItemData[ $data ][ "IDs" ] as $n => $id)
            {
                $this->ReadSubItem($data,$id);
            }

            $this->ItemData[ $data ][ "ItemsRead" ]=1;
        }
    }


    //*
    //* function SubItem2Item, Parameter list: $item,$data
    //*
    //* Reads subitem pertaining to $data, using $item[ $data ] as id,
    //* and adds the data to $item, using $this->GetDataPrefix($data)
    //* as prefix.
    //*

    function SubItem2Item($item,$data)
    {
        if (!isset($item[ "ID" ])) { return $item; }
        if (!isset($item[ $data ])) { return $item; }

        $subitem=$this->ReadSubItem($data,$item[ $data ],$item);
        if (is_array($subitem) && count($subitem)>0)
        {
            $prefix=$this->GetDataPrefix($data);
            $datas=$this->ItemData[ $data ][ "SqlDerivedData" ];
            foreach ($datas as $rdata)
            {
                if (!isset($subitem[ $rdata ]))
                {
                    $this->AddMsg($this->ModuleName.": Derived data '$rdata' in '$data' undef");
                }
                else
                {
                    $item[ $prefix.$rdata ]=$subitem[ $rdata ];
                }
            }
        }

        return $item;
    }


     //*
    //* function SubItemName, Parameter list: $data,$id
    //*
    //* Reads name of subitem, if not already read.
    //*

    function SubItemName($data,$id)
    {
        if ($this->ItemData[ $data ][ "SqlObject" ])
        {
            $subitem=$this->ReadSubItem($data,$id);
            $subobject=$this->GetSubObject($data);

            $subitem=$subobject->ApplyAllEnums($subitem);

            $rsubitem=array();
            foreach ($subitem as $key => $value)
            {
                $rsubitem[ $data."_".$key ]=$value;
            }

            $name=$subobject->Filter($this->ItemData[ $data ][ "SqlFilter" ],$subitem);

            return $name;
        }

        return  $this->ModuleName.", No such '$data' item with ID '$id'"; 
    }

     //*
    //* function MatchSubItemName, Parameter list: $data,$value
    //*
    //* Tests 'name' of any subitem, mathces $value.
    //* If so, returns these items.
    //*

    function MatchSubItemName($data,$id,$search)
    {
        if ($this->ItemData[ $data ][ "SqlObject" ])
        {
            $this->ReadSubItem($data,$id);

            $name=$this->SubItemName($data,$id);
            $name=$this->TrimSearchValue($name);

            if (preg_match('/'.$search.'/',$name))
            {
                return TRUE;
            }
        }


        return FALSE;
    }

     //*
    //* function SubItemValue, Parameter list: $data,$id
    //*
    //* Returns subitem data value (name field), if not already read.
    //*

    function SubItemValue($data,$id)
    {
        if ($id==0) { return ""; }

        if ($this->ItemData[ $data ][ "SqlObject" ])
        {
            $subitem=$this->ReadSubItem($data,$id);
            $subobject=$this->GetSubObject($data);

            $field=$subobject->ItemNamer;
            $field=preg_replace('/#/',"",$field);

            if (isset( $subitem[ $field ]))
            {
                return $subitem[ $field ];
            }
        }

        return  $this->ModuleName.", No such '$data' item with ID '$id'"; 
    }

    //*
    //* function MatchSubItemValue, Parameter list: $data,$value
    //*
    //* Tests 'name' of any subitem, mathces $value.
    //* If so, returns these items.
    //*

    function MatchSubItemValue($data,$id,$search)
    {
        if ($this->ItemData[ $data ][ "SqlObject" ])
        {
            $this->ReadSubItem($data,$id);

            $name=$this->SubItemValue($data,$id);
            $name=$this->TrimSearchValue($name);

            if (preg_match('/'.$search.'/',$name))
            {
                return TRUE;
            }
        }


        return FALSE;
    }


    //*
    //* function ReadSubItemValues, Parameter list: $data
    //*
    //* Reads possible IDs for possible subitems.
    //*

    function ReadSubItemValues($data,$item)
    {
        $itemdata=$this->GetSubObjectItemData($data);
        if ($itemdata[ "SqlObject" ] && !isset($itemdata[ "ItemsRead" ]))
        {
            $subobject=$this->GetSubObject($data);

            $subobject->IncludeAll=TRUE;
            $this->ReadAllSubItems($data,$item);

            $names=array();
            foreach ($this->ItemData[ $data ][ "IDs" ] as $n => $id)
            {
                if (!isset($subobject->ItemHashes[ $id ])) { continue; }
                $subitem=$subobject->ItemHashes[ $id ];
                $subitem=$subobject->ApplyAllEnums($subitem);

                $names[ $id ]=
                    $subobject->Filter($itemdata[ "SqlFilter" ],$subitem).
                    "";
            }

            //Shorten item names if MaxLength set
            if ($subobject->ItemData[ $data ][ "MaxLength" ]>0)
            {
                foreach ($names as $rid => $val)
                {
                    if (strlen($names[ $rid ])>$sub->ItemData[ $data ][ "MaxLength" ])
                    {
                        $names[ $rid ]=
                            substr
                            (
                               $val,
                               0,
                               $subobject->ItemData[ $data ][ "MaxLength" ]
                            ).
                            "...";
                    }
                }
            }

            if (empty($itemdata[ "SqlOrderBy" ]))
            {
                $res=$this->OrderByValues($names);

                $this->ItemData[ $data ][ "IDs" ]=$res[0];
                $this->ItemData[ $data ][ "Names" ]=$res[1];
            }
            else
            {
                $this->ItemData[ $data ][ "Names" ]=$names;
            }

            $this->ItemData[ $data ][ "ItemsRead" ]=TRUE;
        }

    }

    //*
    //* function CreateSubItemSelects, Parameter list: &$item
    //*
    //* Generate Names and IDs for data $data.
    //*

    function CreateSubItemSelects($data,&$item)
    {
        $this->ReadSubItemValues($data,$item);

        $addempty=!$this->ItemData[ $data ][ "NoSearchEmpty" ];
        if (preg_grep('/^0$/',$this->ItemData[ $data ][ "IDs" ])) { $addempty=FALSE; }

        if ($addempty)
        {
             array_unshift($this->ItemData[ $data ][ "IDs" ],0);
             array_unshift($this->ItemData[ $data ][ "Names" ],$this->ItemData[ $data ][ "EmptyName" ]);
        }

        return array
        (
           $this->ItemData[ $data ][ "Names" ],
           $this->ItemData[ $data ][ "IDs" ]
        );

    }


     //*
    //* function CreateSubItemSelectField, Parameter list: $data,$id="",$ignoredefault=0,$rdata="",$fieldtitle="",$multiple=FALSE,$selectfield=FALSE
    //*
    //* Create select field for subitems. Set $this->ItemData[ $data ][ "Values" ].
    //*

    function CreateSubItemSelectField($data,$item,$id="",$ignoredefault=0,$rdata="",$fieldtitle="",$multiple=FALSE,$selectfield=FALSE)
    {
        if ($rdata=="") { $rdata=$data; }
        if ($this->ItemData[ $data ][ "SqlObject" ]=="") { return; }

        $subobject=$this->GetSubObject($data);

        $names=$this->CreateSubItemSelects($data,$item);

        $ids=$names[1];
        $names=$names[0];

        if ($id=="" && isset($item[ $data ])) { $id=$item[ $data ]; }
 
        if ($id==0 &&  $ignoredefault==0 && isset($subobject->ItemData[ $data ][ "Default" ]))
        {
            $id=$subobject->ItemData[ $data ][ "Default" ];
        }

        $dismethod=$subobject->ItemData[ $data ][ "SqlDisabledMethod" ];
        if ($dismethod!="")
        {
            if (method_exists($subobject,$dismethod))
            {
                foreach ($ids as $no => $rid)
                {
                    if ($no>0 && !$subobject->$dismethod($data,$subobject->ItemHashes[ $rid ],$item))
                    {
                        $ids[ $no ]="disabled";
                    }
                }
            }
            elseif (method_exists($this,$dismethod))
            {
                foreach ($ids as $no => $rid)
                {
                    if ($no>0 && !$this->$dismethod($data,$subobject->ItemHashes[ $rid ],$item))
                    {
                        $ids[ $no ]="disabled";
                    }
                }
            }
            else
            {
                $this->Debug=1;
                $this->AddMsg("Warning! No such Disabled Method: ".$dismethod);
            }
        }

        $values=$this->MySqlUniqueColValues
        (
           "",
           $data,
           $this->GetRealWhereClause()
        );

        $rvalues=array();
        foreach ($values as $value)
        {
            $rvalues[ $value ]=1;
        }

        $rids=array();
        $rnames=array();
        for ($n=0;$n<count($ids);$n++)
        {
            if (! $selectfield || $ids[$n]==0 || !empty($rvalues[ $ids[ $n ] ]))
            {
                array_push($rids,$ids[ $n ]);
                array_push($rnames,$names[ $n ]);
            }
        }

        $name="";
        foreach ($rids as $n => $rid)
        {
            if ($rid==$id) { $name=$rnames[$n]; }
        }

        if ($this->ItemData[ $data ][ "SqlSortReverse" ])
        {
            $rids=array_reverse($rids);
            $rnames=array_reverse($rnames);
        }


        $width=0;
        if (!empty($this->ItemData[ $data ][ "Size" ]))
        {
            $width=$this->ItemData[ $data ][ "Size" ];
        }

        $noincludedisableds=FALSE;
        if (!empty($this->ItemData[ $data ][ "NoIncludeDisableds" ]))
        {
            $noincludedisableds=$this->ItemData[ $data ][ "NoIncludeDisableds" ];
        }

        if (!empty($fieldtitle)) { $name=$fieldtitle; }

        return $this->MakeSelectField($rdata,$rids,$rnames,$id,array(),$rnames,$name,$width,$noincludedisableds,$multiple);
    }

    //*
    //* function CreateSubItemShowField, Parameter list: $id,$data
    //*
    //* Create show field for subitems (content). Set $this->ItemData[ $data ][ "Values" ].
    //*

    function CreateSubItemShowField($id,$data)
    {
        if ($this->ItemData[ $data ][ "SqlObject" ]=="") { return; }
        $subobject=$this->GetSubObject($data);

        if ($id>0)
        {
            $subitem=$this->ReadSubItem($data,$id);
            $subitem=$subobject->ApplyAllEnums($subitem);

            if (is_array($subitem) && count($subitem)>0)
            {
                $value=$this->Filter($this->ItemData[ $data ][ "SqlFilter" ],$subitem);
                if (!$this->LatexMode && $this->ItemData[ $data ][ "SqlHRefIt" ])
                {
                    $value=$this->Href
                    (
                       "?ModuleName=".$subobject->ModuleName."&".
                       preg_replace
                       (
                          '/#ID/',
                          $id,
                          $this->Filter
                          (
                             $this->ItemData[ $data ][ "SqlHRefIt" ],
                             $subitem
                          )
                       ).
                       "",
                       $value,
                       "title",
                       "_".$subobject->ModuleName,
                       "sqltarget"
                    );
                }
                $regex=
                    "(".
                    join("|",array_keys($subobject->ItemData)).
                    ")";

                $value=preg_replace('/#'.$regex.'/',"",$value);

                return $this->ApplicationObj->FilterObject($value);
            }
        }

        return "";
    }
}

?>