<?php


class DataGroups extends HashesData
{

    //*
    //* Variables of Items class:
    //*
    var $DataGroupsRead=FALSE;

    //var $Singular=TRUE;
    var $ExtraData=array();
    var $ItemDataGroupsCommon=array();
    var $ItemDataGroupNames=array();
    var $ItemDataGroups=array();

    var $ItemDataSGroupsCommon=array();
    var $ItemDataSGroupNames=array();
    var $ItemDataSGroups=array();

    var $ItemDataGroupsMessages="DataGroups.php";
    var $CurrentDataGroup="";
    var $ItemDataGroupFiles=array("Groups.php");
    var $ItemDataSGroupFiles=array("SGroups.php");
    var $DefaultItemDataGroup=array
    (
      "Name" => "",
      "Data" => array(),
      "Admin" => TRUE,
      "Person" => FALSE,
      "Public" => FALSE,
      "Single" => FALSE,
      "NoTitleRow" => FALSE,
      "SqlWhere" => "",
      "Sort" => "",
      "SubTable"  => NULL,
      "TitleData"  => NULL,
      "GenTableMethod" => "",
      "OtherClass"  => FALSE,
      "OtherGroup" => FALSE,
      "PreMethod" => FALSE,
    );

    //*
    //* Checks if data group is allowed based $this->LoginTyoe or $this->Profile
    //*

    function DataGroupIsAllowed($groupdef,$item=array())
    {
        $res=FALSE;
        if (isset($groupdef[ $this->LoginType ]) && $groupdef[ $this->LoginType ]>0)
        {
            $res=TRUE;
        }
        elseif (isset($groupdef[ $this->Profile ]) && $groupdef[ $this->Profile ]>0)
        {
            $res=TRUE;
        }
  
        if (count($item)>0)
        {
            if (!empty($groupdef[ "AccessMethod" ]))
            {
               $accessmethod=$groupdef[ "AccessMethod" ];
               if (method_exists($this,$accessmethod))
                {
                     return $this->$accessmethod($item);
                }
                else
                {
                    $this->Debug=1;
                    $this->AddMsg("Warning: Invalid group def access method: ".
                                  $accessmethod.", ignored");
                }
            }
        }

        return $res;
    }

    //*
    //* Skips not permitted data groups, based on $this->LoginTyoe or $this->Profile.
    //* Calls DataGroupIsAllowed forach item in 
    //*

    function SkipForbiddenDataGroups()
    {
        foreach (array_keys($this->ItemDataGroups) as $group)
        {
            if (!$this->DataGroupIsAllowed($this->ItemDataGroups[ $group ]))
            {
                unset($this->ItemDataGroups[ $group ]);
            }
        }
        foreach (array_keys($this->ItemDataSGroups) as $group)
        {
            if (!$this->DataGroupIsAllowed($this->ItemDataSGroups[ $group ]))
            {
                unset($this->ItemDataSGroups[ $group ]);
            }
        }
    }

    //*
    //* Make sure $groupdef has all necessary group keys set.
    //*

    function SetItemGroupDefaults(&$groupdef)
    {
        foreach ($this->DefaultItemDataGroup as $key => $value)
        {
            if (!isset($groupdef[ $key ]))
            {
                $groupdef[ $key ]=$value;
            }
        }

        foreach ($this->ApplicationObj->ValidProfiles as $profile)
        {
            if (!isset($groupdef[ $profile ]))
            {
                $groupdef[ $profile ]=0;
            }
        }
    }


    //*
    //* Initialize DataGroups
    //*

    function InitDefaultItemGroups()
    {
        foreach (array_keys($this->ItemDataGroups) as $group)
        {
            $this->SetItemGroupDefaults($this->ItemDataGroups[ $group ]);
        }

        foreach (array_keys($this->ItemDataSGroups) as $group)
        { 
            $this->SetItemGroupDefaults($this->ItemDataSGroups[ $group ]);
        }

        $this->SkipForbiddenDataGroups();
    }

    //*
    //* Add DataGroup
    //*

    function AddItemDataGroup($group,$groupdef,$plural=TRUE)
    {
        $this->SetItemGroupDefaults($groupdef);
        if ($plural)
        {
            $this->ItemDataGroups[ $group  ]=$groupdef;
        }
        else
        {
            $this->ItemDataSGroups[$group  ]=$groupdef;
        }
    }


    //*
    //* Initialize DataGroups
    //*

    function InitDataGroups($hash=array())
    {
        if ($this->DataGroupsRead) { return; }

        if (
              $this->Singular
              ||
              (
                 $this->ModuleName==$this->GetGET("ModuleName")
                 &&
                 $this->GetGETOrPOST("ID")>0
              )
           )
        {
            $this->Singular=TRUE;
            $this->Plural=FALSE;
        }
        else
        {
            $this->Singular=FALSE;
            $this->Plural=TRUE;
        }

        foreach ($this->ItemDataGroupFiles as $file)
        {
            if (file_exists($this->SetupDataPath()."/".$file))
            {
                $this->ItemDataGroups=$this->ReadPHPArray
                (
                   $this->SetupDataPath()."/".$file,
                   $this->ItemDataGroups
                );
            }
        }
        foreach ($this->ItemDataSGroupFiles as $file)
        {
            if (file_exists($this->SetupDataPath()."/".$file))
            {
                $this->ItemDataSGroups=$this->ReadPHPArray
                (
                   $this->SetupDataPath()."/".$file,
                   $this->ItemDataSGroups
                );
            }
        }

        $this->InitDataTimeGroups();
        $this->InitDefaultItemGroups();

        $this->DataGroupsRead=TRUE;
   }

    //*
    //* Initialize DataGroups time groups.
    //*

    function InitDataTimeGroups()
    {
        $timevardata=array("No","Edit","Name");
        foreach ($this->ItemData as $data => $hash)
        {
            if (isset($hash[ "TimeType" ]) && $hash[ "TimeType" ]==1)
            {
                array_push($timevardata,$data);
            }
        }

        $this->ItemDataGroups[ "Times" ]=array
        (
           "Name"    => "Tempos",
           "Name_UK" => "Timestamps",
           "Data"    => $timevardata,
           "Admin"   => 1,
           "Person"  => 0,
           "Public"  => 0,
        );
    }

    //*
    //* Return object data group CGI Var var.
    //*

    function GroupDataCGIVar()
    {
        return $this->ModuleName."_GroupName";
    }

    //*
    //* Return object data group CGI Var var.
    //*

    function GroupDataEditListVar()
    {
        return $this->ModuleName."_Edit";
    }

    //*
    //* Return object data group CGI Var var.
    //*

    function GroupDataPageVar()
    {
        return $this->ModuleName."_Page";
    }

    //*
    //* Return object data group var, that is:
    //* ItemDataSGroups if Singular, elsewise ItemDataGroups
    //*

    function GetDataGroups($single=FALSE)
    {
        $this->InitDataGroups();
        if (($this->Singular || $single) && count($this->ItemDataSGroups)>0)
        {
            return $this->ItemDataSGroups;
        }
        else
        {
            return $this->ItemDataGroups;
        }
    }

    //*
    //* Return object data group names var
    //*

    function GetDataGroupNames()
    {
        if ($this->Singular)
        {
            return $this->ItemDataSGroupNames;
        }
        else
        {
            return $this->ItemDataGroupNames;
        }
    }

    //*
    //* Return object data group common data var
    //*

    function GetDataGroupsCommon()
    {
        if ($this->Singular)
        {
            if (isset($this->ItemDataSGroupsCommon[ $this->LoginType ]))
            {
                return $this->ItemDataSGroupsCommon[ $this->LoginType ];
            }
            elseif (isset($this->ItemSDataSGroupsCommon[ $this->Profile ]))
            {
                return $this->ItemDataSGroupsCommon[ $this->Profile ];
            }
        }
        else
        {
            if (isset($this->ItemDataGroupsCommon[ $this->LoginType ]))
            {
                return $this->ItemDataGroupsCommon[ $this->LoginType ];
            }
            elseif (isset($this->ItemDataGroupsCommon[ $this->Profile ]))
            {
                return $this->ItemDataGroupsCommon[ $this->Profile ];
            }
        }

        return array();
    }

    //*
    //* Return current Data Group
    //*

    function GetActualDataGroup()
    {
        $this->PostInitItems();

        if ($this->CurrentDataGroup!="")
        {
            $group=$this->CurrentDataGroup;
        }
        else
        {
            $group=$this->GetCGIVarValue($this->GroupDataCGIVar());
        }

        $groups=$this->GetDataGroups();
        if (!preg_grep('/^'.$group.'$/',array_keys($groups)))
        {
            $group="";
        }

        if  (
               $group==""
               ||
               !$this->DataGroupIsAllowed($groups[ $group ])
            )
        {
            //No group found (or group found was not allowed)
            //Localize first allowed data group
            foreach ($groups as $rgroup => $groupdef)
            {
                if ($this->DataGroupIsAllowed($groups[ $rgroup ]))
                {
                    $group=$rgroup;
                    break;
                }
            }
        }

        return $group;
    }


    //*
    //* Return data to display in Data Group
    //*

    function GetGroupDatas($group,$single=FALSE)
    {
        if ($group=="") { return array(); }

        $groups=$this->GetDataGroups($single);

        $groupscommon=array();
        if (!$single) { $groupscommon=$this->GetDataGroupsCommon(); }

        $rdatas=array();
        if (count($groupscommon)>0)
        {
            foreach ($groupscommon as $id => $data)
            {
                array_push($rdatas,$data);
            }
        }

        if (!isset($groups[ $group ]) || !is_array($groups[ $group ]))
        {
            //$this->PrintCallStack();
            print $this->ModuleName." Warning: Group $group undefined";exit();
            $this->AddMsg("Warning: Group $group undefined");
            return;
        }

        if (!isset($groups[ $group ][ "Data" ]) || !is_array($groups[ $group ][ "Data" ]))
        {
            print $this->ModuleName." Warning: Group $group has no data defined";exit();
            $this->AddMsg("Warning: Group $group has no data defined");
            return;
        }

        $keys=array($this->Profile,$this->LoginType);
        foreach ($groups[ $group ][ "Data" ] as $id => $data)
        {
            if (
                (
                 isset($this->ItemData[ $data ])
                 &&
                 $this->CheckSubKeysPositiveOr($this->ItemData[ $data ],$keys)
                )
                ||
                (
                 isset($this->Actions[ $data ])
                 &&
                 $this->CheckSubKeysPositiveOr($this->Actions[ $data ],$keys)
                )
               )
            {
                array_push($rdatas,$data);
            }
            elseif (isset($this->Actions[ $data ]))
            {
                $action=$data;
                if ($this->ActionAllowed($data))
                {
                    array_push($rdatas,$data);
                }
                else
                {
                    if (
                        isset($this->Actions[ $data ][ "AltAction" ])
                       )
                    {
                        $altaction=$this->Actions[ $data ][ "AltAction" ];
                        if ($this->ActionAllowed($altaction))
                        {
                            array_push($rdatas,$altaction);
                        }
                    }
                }
            }
            elseif (
                      $data=="No"
                      ||
                      preg_match('/^newline/',$data)
                      ||
                      preg_match('/^text\_/',$data)
                   )
            {
                array_push($rdatas,$data);
            }
        }

        return $rdatas;
    }

    function GetGroupReadData($group)
    {
        $datas=$this->GetGroupDatas($group);
        $groups=$this->GetDataGroups();

        //Datas that we need to have read (for some reason)
        foreach ($this->ExtraData as $n => $data)
        {
             array_push($datas,$data);
        }

        if (is_array($groups[$group][ "ExtraData" ]))
        {
            foreach ($groups[$group][ "ExtraData" ] as $n => $data)
            {
                if (!preg_grep('/^'.$data.'$/',$datas))
                {
                    array_push($datas,$data);
                }
            }
        }

        $rdatas=array();
        foreach ($datas as $id => $data)
        {
            if (is_array($this->ItemData[ $data ]) && !$this->ItemData[ $data ][ "IsDerived" ])
            {
                if (!preg_grep('/^'.$data.'$/',$rdatas))
                {
                    array_push($rdatas,$data);
                }
            }
        }

        return $rdatas;
    }


    function GetActualDataGroupDatas()
    {
        $group=$this->GetActualDataGroup();
        return $this->GetGroupReadData($group);
    }


    function GetDefaultDataGroup()
    {
        $groupnames=$this->GetDataGroupNames();

        $logintype=$this->LoginType;

        $groupname="";
        foreach ($groupnames as $id => $group)
        {
            if (!$this->Singular)
            {
                if ($this->DataGroupIsAllowed($this->ItemDataGroups[$group ]))
                {
                    $groupname=$group;
                }
            }
            else
            {
                if ($this->DataGroupIsAllowed($this->ItemDataSGroups[$group ]))
                {
                    $groupname=$group;
                }
            }
        }

        $datas=$this->GetGroupDatas($groupname);

        return $datas;
    }

    function ItemGroupURL($groupname)
    {
        $groups=$this->GetDataGroups();

        $hash=array("Action" => $this->DetectAction());

        
        $hash[ $this->GroupDataCGIVar() ]=$groupname;
        if ($this->GetGETOrPOST("ID")>0)
        {
            $hash[ "ID" ]=$this->GetGETOrPOST("ID");
        }

        if ($this->GetGETOrPOST("EditList")>0)
        {
            //$hash[ "EditList" ]=1;
        }

        $link="?".$this->Hash2Query($hash);

        return $link;
    }


    function ItemGroupHidden($groupname)
    {
        return $this->MakeHidden($this->GroupDataCGIVar(),$groupname);
    }

    function ItemEditListHidden($edit)
    {
        return $this->MakeHidden($this->GroupDataEditListVar(),2);
    }

    function ItemPageHidden($edit)
    {
        return $this->MakeHidden
        (
           $this->GroupDataPageVar(),
           $this->GetPOST($this->GroupDataPageVar())
        );
    }

    //*
    //* function DataGroupsSearchField, Parameter list: $data
    //*
    //* Creates select fields do choose data group
    //*

    function DataGroupsSearchField()
    {
        $values=array();
        $names=array();
        $titles=array();
        foreach ($this->GetDataGroups() as $groupid => $group)
        {
            //Check if group allowed
            if ($this->DataGroupIsAllowed($group) && $this->GetRealNameKey($group,"Name")!="")
            {
                array_push($values,$groupid);
                array_push($names,$this->GetRealNameKey($group));
                array_push($titles,$this->GetRealNameKey($group,"Title"));
            }
        }

        if (count($values)==0) { return ""; }

        return
            $this->MakeSelectField
            (
               $this->ModuleName."_GroupName",
               $values,
               $names,
               $this->GetActualDataGroup(),//$this->GetCGIVarValue($this->ModuleName."_GroupName"),
               array(),//disableds
               $titles,
               $this->GetMessage($this->ItemDataGroupsMessages,"DataGroupsTitle","Title")
            );
    }


}
?>