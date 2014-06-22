<?php

//include_once("Users/Import.php");
include_once("Places.php");
include_once("Schools/Latex.Settings.php");
include_once("Schools/Handlers.php");


class Schools extends SchoolsHandlers
{
    //*
    //* Variables of Schools class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function Schools($args=array())
    {
        $this->IDGETVar="School";
        $this->Hash2Object($args);
        $this->Sort=array("Name");

        array_unshift($this->ItemDataFiles,"../Places/Data.php");
        $this->SqlWhere="Type='4'";

        $this->ItemData=$this->ReadPHPArray("System/Units/Data.Titles.php",$this->ItemData);

        $datas=array_keys($this->ItemData);
        $this->HtmlTitleVars=preg_grep('/^HtmlTitle/',$datas);
        $this->HtmlIconVars=preg_grep('/^HtmlIcon/',$datas);

        $this->LatexTitleVars=preg_grep('/^LatexTitle/',$datas);
        $this->LatexIconVars=preg_grep('/^LatexIcon/',$datas);

        $this->AlwaysReadData=$datas;
    }


    //*
    //* function GetUploadPath, Parameter list:
    //*
    //* Overrides MySql2::GetUploadPath. Returns:
    //*
    //* Uploads/#Unit/#School/Students
    //*

    function GetUploadPath($args=array())
    {
        $comps=array
        (
           "Uploads",
           $this->ApplicationObj->Unit[ "ID" ],
           "Schools"
        );

        $path=join("/",$comps);

        $this->CreateDirAllPaths($path);
        touch($path."/index.php");

        return $path;

    }
   //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        parent::PostProcessItemData();

        $this->ItemData[ "Type" ][ "SearchDefault" ]=4;
        $this->ItemData[ "Type" ][ "Search" ]=FALSE;

        $this->AddPrintTableData();
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ItemDataGroups=$this->ReadPHPArray("System/Units/Groups.Titles.php",$this->ItemDataGroups);
        $this->ItemDataSGroups=$this->ReadPHPArray("System/Units/SGroups.Titles.php",$this->ItemDataSGroups);

        parent::PostInit();
        $this->IncludeAll=TRUE;
   }


    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* 
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        $where[ "Type" ]=4;

        if (preg_match('/^(Clerk|Coordinator)$/',$this->ApplicationObj->Profile))
        {
            $schoolids=$this->ApplicationObj->ClerksObject->MySqlUniqueColValues
            (
               "",
               "School",
               array
               (
                  "Clerk" => $this->LoginData[ "ID" ],
               )
            );

            $where[ "ID" ]="IN ('".join("','",$schoolids)."')";
        }

        return $where;
    }


     //*
    //* function UpdateSubTablesStructure, Parameter list: $item
    //*
    //* Make sure that correct Discs and Students tables exists for $item.
    //*

    function UpdateSubTablesStructure($item,$classname)
    {
        $classes=array
        (
           "Classes","Students",
        );

        $this->ApplicationObj->UpdateTablesStructure($classes,$classname);
     }

    //*
    //* function Unit2Scool, Parameter list: &$item
    //*
    //* Transfers Unit data to Schools Data, as default values.
    //*

    function Unit2Scool(&$item)
    {
        $keys=array
        (
           1 => 1,
           2 => 2,
           3 => 4,
           4 => 5,
           5 => 6,
           6 => 0,
         );

        $updatedata=array();
        foreach (array("Html","Latex") as $stub)
        {
            for ($n=1;$n<=6;$n++)
            {
                $data=$stub."Title".$n;
                $key=$stub."Title".$keys[ $n ];
                if ($keys[ $n ]==0) { continue; }

                if (empty($item[ $key ]))
                {
                    $item[ $key ]=$this->ApplicationObj->Unit[ $data ];
                    array_push($updatedata,$data);
                }
            }

            $data=$stub."Title3";
            if (empty($item[ $data ]))
            {
                $item[ $data ]=$item[ "Name" ];
                array_push($updatedata,$data);
            }
        }

        $vars=array("HtmlIconVars","LatexIconVars");
        foreach (array("HtmlIconVars","LatexIconVars") as $var)
        {
            foreach ($this->$var as $data)
            {
                if (empty($item[ $data ]))
                {
                    $item[ $data ]=$this->ApplicationObj->Unit[ $data ];
                    array_push($updatedata,$data);
                }
            }
        }

        if (count($updatedata)>0)
        {
            $this->MySqlSetItemValues("",$updatedata,$item);
        }
    }


     //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Schools")
        {
            return $item;
        }

        $this->TakeUndefinedListOfKeys
        (
           $item,
           $this->ApplicationObj->Unit,
           $this->DefaultUnitsData,
           TRUE
        );

        $this->Unit2Scool($item);
        return $item;
    }

    //*
    //* function HandleClerks, Parameter list:
    //*
    //* 
    //*

    function TInterfaceMenu($plural=FALSE,$id="")
    {
        $this->ApplicationObj->InfoTable();
        parent::TinterfaceMenu($plural,$id);
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
        if (preg_match('/^(Admin|Secretary)$/',$this->Profile))
        {
            $res=TRUE;
        }

        return $res;
    }

    //*
    //* function SchoolWeekDays, Parameter list: $namekey=TRUE,$school=array()
    //*
    //* Generate active School weekdays.
    //*

    function SchoolWeekDays($namekey=TRUE,$school=array())
    {
        if (empty($school)) { $school=$this->ApplicationObj->School; }

        $allowed=array();
        $n=1;
        foreach ($this->WeekDays as $wday)
        {
            if ($school[ "WeekDay".$n ]==1)
            {
                $key=$wday;
                if (!$namekey) { $key=$n; }

                $allowed[ $key ]=TRUE;
            }

            $n++;
        }

        return array_keys($allowed);
    }


    //*
    //* function SchoolWeekDaysSelect, Parameter list: $fieldname,$value,$school=array()
    //*
    //* Generate active School weekdays.
    //*

    function SchoolWeekDaysSelect($fieldname,$value,$school=array())
    {
        if (empty($school)) { $school=$this->ApplicationObj->School; }

        $values=array(0);
        $names=array("");
        $n=1;
        foreach ($this->WeekDays as $wday)
        {
            //Allowed weekday - or current value
            if ($school[ "WeekDay".$n ]==1 || $value==$n)
            {
                array_push($values,$n);
                array_push($names,$wday);
            }

            $n++;
        }

        return $this->MakeSelectField
        (
           $fieldname,
           $values,
           $names,
           $value
        );
    }


    //*
    //* function ReadSchool, Parameter list: $die=TRUE,$school=0
    //*
    //* Reads School ID from CGI GET, exits if nonexistent or inadeqaute
    //*

    function ReadSchool($die=TRUE,$school=0)
    {
        if (!empty($this->ApplicationObj->School)) { return; }

        $mod=$this->ApplicationObj->Module;
        $modname=$this->ApplicationObj->ModuleName;

        if (empty($school))
        {
            $school=intval($this->GetGET("School"));
        }

        if (empty($school) && $this->ApplicationObj->ModuleName=="Schools")
        {
            $school=intval($this->GetGET("ID"));
        }

        if ($school>0)
        {
            $this->ApplicationObj->School=$this->SelectUniqueHash
            (
              "Places",
              array("ID" =>$school)
            );

            $this->ApplicationObj->SchoolsObject->ItemHash=$this->ApplicationObj->School;


            if (!empty($this->ApplicationObj->School[ "Department" ]))
            {
                $this->ApplicationObj->Department=$this->SelectUniqueHash
                (
                   "Departments",
                   array("ID" => $this->ApplicationObj->School[ "Department" ])
                );

                $this->ApplicationObj->School[ "Department_Name" ]=$this->ApplicationObj->Department[ "Name" ];
            }

            $key="HtmlTitle";
            for ($n=1;$n<=6;$n++)
            {
                $rkey=$key.$n;
                $this->ApplicationObj->TInterfaceTitles[ $n-1 ]=$this->ApplicationObj->School[ $rkey ];
            }

            $key="LatexTitle";
            for ($n=1;$n<=6;$n++)
            {
                $rkey=$key.$n;
                $this->ApplicationObj->TInterfaceLatexTitles[ $n-1 ]=$this->ApplicationObj->School[ $rkey ];
            }

            $key="Html";
            for ($n=1;$n<=2;$n++)
            {
                $this->ApplicationObj->TInterfaceIcons[ $n ]=array
                (
                   "Icon"   => $this->ApplicationObj->School[ $key."Icon".$n ],
                   "Height" => $this->ApplicationObj->School[ $key."IconHeight" ],
                   "Width"  => $this->ApplicationObj->School[ $key."IconWidth" ],
                );
            }

            $key="Latex";
            for ($n=1;$n<=2;$n++)
            {
                $this->ApplicationObj->TInterfaceLatexIcons[ $n ]=array
                (
                   "Icon"   => $this->ApplicationObj->School[ $key."Icon".$n ],
                   "Height" => $this->ApplicationObj->School[ $key."IconHeight" ],
                   "Width"  => $this->ApplicationObj->School[ $key."IconWidth" ],
                );
            }

            $this->School2CompanyHash();
        }
        elseif ($die && $this->ApplicationObj->Profile!="Admin")
        {
            if (!preg_match('/^(Users|Schools|Clerks)$/',$this->ApplicationObj->ModuleName))
            {
                die("Invalid School");
            }
        }
   }


    //*
    //* function ReadSchools, Parameter list: $schoolids=NULL
    //*
    //* Reads all permitted schools.
    //*

    function ReadSchools($schoolids=NULL)
    {
        if (!empty($this->ApplicationObj->Schools)) { return; }

        $schoolids=NULL;
        if (preg_match('/^(Clerk|Coordinator)$/',$this->ApplicationObj->Profile))
        {
            $schoolids=$this->GetClerkSchools();
        }
        elseif (preg_match('/^(Teacher)$/',$this->ApplicationObj->Profile))
        {
            $schoolids=$this->GetTeacherSchools();
        }
        elseif (preg_match('/^(Admin|Secretary)$/',$this->ApplicationObj->Profile))
        {
            $schoolids=array();
        }

        if (is_array($schoolids))
        {
            $where="Type='4'";
            if (count($schoolids)>0)
            {
                $where.=" AND ID IN ('".join("','",$schoolids)."')";
            }
            elseif (!preg_match('/^(Admin|Secretary)$/',$this->ApplicationObj->Profile))
            {
                $where.=" AND ID='0'";
            }

            $this->ApplicationObj->Schools=$this->SelectHashesFromTable
            (
               "Places",
               $where,
               array("ID","Name","ShortName"),
               TRUE, //byid!
               "ShortName"
            );
        }
   }

    //*
    //* Detects relevant schools for logged on clerk
    //*

    function GetClerkSchools()
    {
        $perms=$this->SelectHashesFromTable
        (
           "Clerks",
           array
           (
              "Clerk" => $this->ApplicationObj->LoginData[ "ID" ],
           ),
           array("ID","School")
        );

        $schoolids=array();

        //Permit Clerks school of origin 
        if (
              preg_match('/^(Clerk|Coordinator)$/',$this->ApplicationObj->Profile)
              &&
              !empty($this->ApplicationObj->LoginData[ "School" ])
           )
        {
            $schoolids[ $this->ApplicationObj->LoginData[ "School" ] ]=1;
        }

        foreach ($perms as $perm)
        {
            if ($perm[ "School" ]>0)
            {
                $schoolids[ $perm[ "School" ] ]=1;
            }
        }

        return array_keys($schoolids);
     }


    //*
    //* Detects relevant schools for logged on teacher
    //*

    function GetTeacherSchools()
    {
        $teacherid=$this->ApplicationObj->LoginData[ "ID" ];

        $schools=$this->SelectHashesFromTable
        (
           "Places",
           array("Type" => 4),
           array("ID")
        );

        $this->Periods=$this->SelectHashesFromTable
        (
           "Periods",
           array("Daylies" => 2)
        );
        $this->Periods=array_reverse($this->ApplicationObj->Periods);

        $schoolids=array();

        foreach ($schools as $school)
        {
            $classestable=$school[ "ID" ]."_Classes";

            if ($this->MySqlIsTable($classestable))
            {
                $wheres=array();
                foreach (array("Teacher","Teacher1","Teacher2") as $data)
                {
                    if ($this->DBFieldExists($classestable,$data))
                    {
                        array_push
                        (
                           $wheres,
                           $data."='".
                           $this->ApplicationObj->LoginData[ "ID" ]."'"
                        );
                    }
                }

                if (count($wheres)>0)
                {
                    $nclasses=$this->MySqlNEntries
                    (
                       $classestable,
                       join(" OR ",$wheres)
                    );

                    if ($nclasses>0)
                    {
                        $schoolids[ $school[ "ID" ] ]=1;
                    }
                }
            }
        }


        $this->ApplicationObj->LoadSubModule("Periods");
        foreach ($this->ApplicationObj->PeriodsObject->ReadAllPeriods() as $period)
        {
            $periodname=$this->ApplicationObj->GetPeriodName($period);
            foreach ($schools as $school)
            {
                $classdiscstable=$school[ "ID" ]."_".$periodname."_ClassDiscs";

                if ($this->MySqlIsTable($classdiscstable))
                {
                    $wheres=array();
                    foreach (array("Teacher","Teacher1","Teacher2") as $data)
                    {
                        if ($this->DBFieldExists($classdiscstable,$data))
                        {
                            array_push
                            (
                               $wheres,
                               $data."='".
                               $this->ApplicationObj->LoginData[ "ID" ]."'"
                            );
                        }
                    }


                    if (count($wheres)>0)
                    {
                        $ndiscs=$this->MySqlNEntries
                        (
                           $classdiscstable,
                           join(" OR ",$wheres)
                        );

                        if ($ndiscs>0)
                        {
                            $schoolids[ $school[ "ID" ] ]=1;
                        }
                    }
                }
            }
        }

        return array_keys($schoolids);
    }

    //*
    //* Transfers data read into $this->ApplicationObj->School, into $this->ApplicationObj->CompanyHash.
    //*

    function School2CompanyHash()
    {
        if (!empty($this->ApplicationObj->School))
        { 
            foreach (array("ZIP","Area","Phone","Fax","Email","WWW","Street","StreetNumber","StreetCompletion") as $key)
            {
                $this->ApplicationObj->CompanyHash[ $key ]=$this->ApplicationObj->School[ $key ];
            }

            $this->ApplicationObj->CompanyHash[ "Institution" ]=
                $this->ApplicationObj->CompanyHash[ "Institution" ].
                $this->NewLine().
                $this->ApplicationObj->School[ "Department_Name" ];

            $this->ApplicationObj->CompanyHash[ "Department" ]=$this->ApplicationObj->School[ "Name" ];

            foreach ($this->ApplicationObj->School as $data => $value)
            {
                $this->ApplicationObj->CompanyHash[ $data ]=$this->ApplicationObj->School[ $data ];
            }
        }
    }



    //*
    //* function DateIsLecturable, Parameter list: $date,$school=array()
    //*
    //* Calculates dates wek no.
    //*

    function DateIsLecturable($date,$school=array())
    {
        if (empty($school)) { $school=$this->ApplicationObj->School; }
        $wday=$date[ "WeekDay" ];
        if ($school[ "WeekDay".$wday]==1)
        {
            if ($date[ "Type" ]<=3)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

}

?>