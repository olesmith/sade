<?php

class Protocols extends Common
{

    //*
    //* Variables of Protocols class:

    //*
    //* function Protocols, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function Protocols($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Name");
        $this->Sort=array("Name");

        if ($this->ApplicationObj->DocHeadSend!=1)
        {
            //setcookie
            //(
            //   $this->ApplicationObj->PlaceSearchField,
            //   $this->GetCGIVarValue($this->ApplicationObj->PlaceSearchField),
            //   time()+3600
            //);
            setcookie
            (
               $this->ApplicationObj->ProtocolSearchField,
               $this->GetCGIVarValue($this->ApplicationObj->ProtocolSearchField),
               time()+3600
            );
        }
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";
        if (preg_match('/^(Secretary)$/',$this->Profile))
        {
            $this->AddDefaults[ "Department" ]=$this->LoginData[ "Department" ];
            $this->AddFixedValues[ "Department" ]=$this->LoginData[ "Department" ];
        }
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Postprocesses and returns $item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Protocols")
        {
            return $item;
        }

        return $item;
    }


    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for Units.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        if ($this->LoginType=="Person")
        {
        }

        return $where;
    }

    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited. Admin may -
    //* and Person, if LoginData[ "ID" ]==$item[ "ID" ]
    //*

    function CheckEditAccess($item)
    {
        $res=FALSE;
        if (preg_match('/^(Admin)$/',$this->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Secretary)$/',$this->Profile))
        {
            $secretary=$this->ApplicationObj->DepartmentsObject->MySqlItemValue("","ID",$item[ "Department" ],"Secretary");
            if ($secretary==$this->LoginData[ "ID" ])
            {            
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function ProtocolsSelect, Parameter list: 
    //*
    //* Generates Protocols select field.
    //*

    function ProtocolsSelect()
    {
        $this->ApplicationObj->Protocols=$this->SelectHashesFromTable
        (
           "",
           array("Department" => $this->LoginData[ "Department" ]),
           array(),
           FALSE,
           "Name"
        );

        $ids=array();
        $names=array();

        $protocolval=$this->GetCGIVarValue($this->ApplicationObj->ProtocolSearchField);
        if (count($this->ApplicationObj->Protocols)==1)
        {
            $protocolval=$this->ApplicationObj->Protocols[0][ "ID" ];
        }

        foreach ($this->ApplicationObj->Protocols as $id => $protocol)
        {
            array_push($ids,$protocol[ "ID" ]);
            array_push($names,$protocol[ "Name" ]);
            if ($protocolval==$protocol[ "ID" ])
            {
                $this->ItemHash=$protocol;
            }
        }

        return $this->MakeSelectField($this->ApplicationObj->ProtocolSearchField,$ids,$names,$protocolval);
    }

    //*
    //* function PlacesSelect, Parameter list: 
    //*
    //* Generates Places select field.
    //*

    function PlacesSelect()
    {
        $this->ApplicationObj->Places=$this->ApplicationObj->PlacesObject->SelectHashesFromTable
        (
           "",
           array("Department" => $this->LoginData[ "Department" ]),
           array(),
           FALSE,
           "Name"
        );

        $ids=array();
        $names=array();

        $placeval=$this->GetCGIVarValue($this->ApplicationObj->PlaceSearchField);
        if (count($this->ApplicationObj->Places)==1)
        {
            $placeval=$this->ApplicationObj->Places[0][ "ID" ];
        }

        foreach ($this->ApplicationObj->Places as $id => $place)
        {
            array_push($ids,$place[ "ID" ]);
            array_push($names,$place[ "Name" ]);
            if ($placeval==$place[ "ID" ])
            {
                $this->ApplicationObj->Place=$place;
            }
        }

        return $this->MakeSelectField($this->ApplicationObj->PlaceSearchField,$ids,$names,$placeval);
    }

    //*
    //* function GetTargetWhere, Parameter list: $protocol=NULL
    //*
    //* Returns target where clause assocviated with $protocol.
    //*

    function GetTargetWhere($protocol=NULL)
    {
        if (!$protocol) { $protocol=$this->ItemHash; }

        $tkey=$this->GetTargetKey($protocol);
        $where=array();
        if (!empty($tkey))
        {
            $where[ $tkey ]=2;
        }

        if ($protocol)
        {
            if (
                  isset($protocol[ "TargetPerson" ])
                  &&
                  $protocol[ "TargetPerson" ]>0
               )
            {
                $where=array();
                $where[ "ID" ]=$protocol[ "TargetPerson" ];
            }
            elseif (
                  isset($protocol[ "Department" ])
                  &&
                  $protocol[ "Department" ]>0
               )
            {
                $where[ "Department" ]=$protocol[ "Department" ];
            }
        }

        return $where;
    }

    //*
    //* function GetNumberOfTargets, Parameter list: $protocol=NULL
    //*
    //* Returns number of  possible targets, this with group privilege set.
    //*

    function GetNumberOfTargets($protocol=NULL)
    {
        return $this->ApplicationObj->PeopleObject->MySqlNEntries
        (
           "",
           $this->GetTargetWhere($protocol)
        );
    }

    //*
    //* function ReadTargetGroup, Parameter list: $protocol=NULL
    //*
    //* Reads possible targets, this with group privilege set.
    //*

    function ReadTargetGroup($protocol=NULL)
    {
        $this->ApplicationObj->Targets=$this->ApplicationObj->PeopleObject->SelectHashesFromTable
        (
           "",
           $this->GetTargetWhere($protocol),
           $this->ApplicationObj->ConsultsObject->PersonsReadData,
           FALSE,
           "Name"
        );
     }


    //*
    //* function TargetSelect, Parameter list: $data="",$protocol=NULL
    //*
    //* Generates Target select field.
    //*

    function TargetSelect($data="",$protocol=NULL,$targetval="")
    {
        $targetkey=$this->ReadTargetGroup($protocol);

        $ids=array(0);
        $names=array("");

        if (empty($targetval))
        {
            $targetval=$this->GetCGIVarValue($this->ApplicationObj->TargetSearchField);
        }

        $ntargets=0;
        foreach ($this->ApplicationObj->Targets as $id => $target)
        {
            array_push($ids,$target[ "ID" ]);
            array_push($names,$target[ "Name" ]);
            if ($targetval==$target[ "ID" ])
            {
                $this->ApplicationObj->Target=$target;
            }
            $ntargets++;
        }

        if ($ntargets==1)
        {
            array_shift($ids);
            array_shift($names);
            $targetname=array_shift($names);
            $targetval=array_shift($ids);
            $ids=array($targetval);
            $names=array($targetname);
        }

        if (empty($data))
        {
            $data=$this->ApplicationObj->TargetSearchField;
        }

        return $this->MakeSelectField($data,$ids,$names,$targetval);
    }

    //*
    //* function RetrieveTargetKey, Parameter list: $key,$protocol=NULL
    //*
    //* Returns the target key $key associated with protocol. Used to search
    //* possible targets, ie: medicals, etc.
    //*

    function RetrieveTargetKey($key,$protocol=NULL)
    {
        if (!$protocol) { $protocol=$this->ItemHash; }

        $rkey="";
        if ($protocol)
        {
            if (!empty($protocol[ "TargetProfile" ]))
            {
                $targetkey=$protocol[ "TargetProfile" ]-1;
                $rkey=$this->ItemData[ "TargetProfile" ][ "ValueOptions" ][ $targetkey ][ $key ];
            }
        }

        return $rkey;
    }

    //*
    //* function GetTargetKey, Parameter list: $protocol=NULL
    //*
    //* Returns the target key associated with protocol. Used to search
    //* possible targets, ie: medicals, etc.
    //*

    function GetTargetKey($protocol=NULL)
    {
        return $this->RetrieveTargetKey("Key",$protocol);
    }

    //*
    //* function GetTargetSearchData, Parameter list: $protocol=NULL
    //*
    //* Returns the target key associated with protocol. Used to search
    //* possible targets, ie: medicals, etc.
    //*

    function GetTargetSearchData($protocol=NULL)
    {
        return $this->RetrieveTargetKey("SearchData",$protocol);
    }


    //*
    //* function GetTargetName, Parameter list: $protocol=NULL
    //*
    //* Returns the target name associated with protocol (ie Medical).
    //*

    function GetTargetName($protocol=NULL)
    {
        return $this->RetrieveTargetKey("Name",$protocol);
    }

    //*
    //* function GetTargetConsultName, Parameter list: $protocol=NULL
    //*
    //* Returns the target name associated with protocol (ie Patient).
    //*

    function GetTargetConsultName($protocol=NULL)
    {
        return $this->RetrieveTargetKey("ConsultName",$protocol);
    }

    //*
    //* function TargetPersonSelect, Parameter list: $data,$protocol
    //*
    //* Creates target person select field. FieldMethod in data TargetPerson.
    //*

    function TargetPersonSelect($data,$protocol,$edit=1)
    {
        if (empty($protocol)) { return ""; }

        if ($edit==1)
        {
            $target="";
            if (!empty($protocol[ $data ])) { $target=$protocol[ $data ]; }

            return $this->TargetSelect($data,$protocol,$target);
        }
        elseif ($protocol[ $data ]>0)
        {
            return $this->ApplicationObj->PeopleObject->MySqlItemValue
            (
               "",
               "ID",
               $protocol[ $data ],
               "Name"
            );
        }

        return "";
    }

    //*
    //* function UpdateTargetPerson, Parameter list: $protocol=NULL
    //*
    //* Updates target person, checking if belongs to group..
    //*

    function UpdateTargetPerson($protocol,$data,$newvalue)
    {
        if ($newvalue==0)
        {
            $protocol[ $data ]=$newvalue;
            return $protocol;
        }

        $privilege=$this->ApplicationObj->PeopleObject->MySqlItemValue
        (
           "",
           "ID",
           $newvalue,
           $this->GetTargetKey($protocol)
        );

        if ($privilege==2)
        {
            $protocol[ $data ]=$newvalue;
        }

        return $protocol;
    }

    //*
    //* function HandleSelect, Parameter list: 
    //*
    //* Handle select of protocols and iniciates Consults.
    //*

    function HandleSelect()
    {
        $protocolselect=$this->ApplicationObj->ProtocolsSelect();
        $placesselect=$this->ApplicationObj->PlacesSelect();

        $table=array();

        array_push
        (
           $table,
           array($this->H(1,"Protocolos e Consultas")),
           array($this->H(2,"Selecionar")),
           array
           (
              $this->B("Protocolo:"),
              $protocolselect,
           ),
           array
           (
              $this->B("Local:"),
              $placesselect,
           ),
           array
           (
              $this->MakeHidden("Consult",1).
              $this->MakeHidden("Name",$this->GetPOST("Name")).
              $this->MakeHidden("PRN",$this->GetPOST("PRN")).
              $this->MakeHidden("Target",$this->GetPOST("Target")).
              $this->MakeHidden("Date",$this->GetPOST("Date")).
              $this->Button("submit","GO!")
           )
        );

        $tables=array
        (
            $this->StartForm().
            $this->HtmlTable("",$table,array("WIDTH" => "100%","BORDER" => 1,"ALIGN" => 'center')).
            $this->EndForm().
            ""
        );

        if (!empty($this->ItemHash) && !empty($this->ApplicationObj->Place))
        {
            $rtables=$this->ApplicationObj->ConsultsObject->ConsultsSearchForm($this->ItemHash,$this->ApplicationObj->Place);
            foreach ($rtables as $table) { array_push($tables,$table); }

            $status=$this->GetPOST("Status");
            if (empty($status))
            {
                $status=1;
            }

            $personids=array();
            foreach ($this->ApplicationObj->ConsultsObject->Persons as $id => $person)
            {
                array_push($personids,$person[ "ID" ]);
            }

            $pwhere=array();
            if (count($personids)>0)
            {
                $pwhere[ "Person" ]=array
                (
                   "Qualifier" => "IN",
                   "Values" => "('".join("','",$personids)."')"
                );
            }

            $this->ApplicationObj->ConsultsObject->Actions[ "Edit" ][ $this->Profile ]=1;
            $this->ApplicationObj->ConsultsObject->WDays=$this->WDays;
            array_push
            (
               $tables,
               $this->ApplicationObj->ConsultsObject->ConsultSearchTable
               (
                   array
                   (
                      "Protocol" => array
                      (
                         "Name"  => "Protocolo",
                         "Value" => $this->ItemHash[ "ID" ],
                         "Text"  => $this->ItemHash[ "Name" ],
                      ),
                      "Place" => array
                      (
                         "Name"  => "Local",
                         "Value" => $this->ApplicationObj->Place[ "ID" ],
                         "Text"  => $this->ApplicationObj->Place[ "Name" ],
                      ),
                   ),
                   array
                   (
                      "Target" => array
                      (
                         "Name" => "Consultado(a)",
                         "Field" => $this->TargetPersonSelect("Target",$this->ItemHash,1),
                         "Value" => $this->GetPOST("Target"),
                      ),
                      "Date" => array
                      (
                         "Name" => "Data",
                         "Method" => "HtmlDateInputField",
                         "Value" => $this->HtmlDateInputValue("Date",FALSE),
                      ),
                      "Status" => array
                      (
                         "Name" => "Status",
                         "Value" => $status,
                      ),
                   ),
                   array
                   (
                      "Name" => array(),
                      "PRN" => array(),
                   ),
                   "PersonList",
                   $pwhere
               )
            );
        }

        print $this->HtmlTable("",$tables);
    }


}


?>