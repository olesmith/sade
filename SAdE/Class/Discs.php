<?php



include_once("../MySql2/Unique.php");
include_once("Class/Discs/Import.php");
include_once("Class/Discs/Update.php");
include_once("Class/Discs/Edit.php");
include_once("Class/Discs/Row.php");
include_once("Class/Discs/TitleRows.php");
include_once("Class/Discs/Questionaries.php");
include_once("Class/Discs/Tables.php");
include_once("Class/Discs/Dayly.php");
include_once("Class/Discs/InfoTable.php");
include_once("Class/Discs/Menu.php");
include_once("Class/Discs/SelectForm.php");
include_once("Class/Discs/Teachers.php");
include_once("Class/Discs/Access.php");


class ClassDiscs extends ClassDiscsAccess
{

    //*
    //* Variables of ClassDiscs class:
    //*

    var $ClassTransferData=array
    (
     //"Teacher","Teacher1","Teacher2"
    );
    var $GradeDiscTransferData=array
    (
       "Name","NickName","CHT","CHS",
       "NAssessments","AssessmentType","AssessmentsWeights","MediaLimit",
       "NRecoveries","FinalMedia",
       "AbsencesType","AbsencesLimit",
    );

    var $NAssessments=0;
    var $NRecoveries=0;

    var $DiscsData=array("Name",);
    var $DiscsActions=array
    (
       "DiscMarks","DiscAbsences","DiscPrint",
    );

    var $StudentsData=array("Name","Status");
    var $StudentsActions=array("StudentMarks","StudentAbsences","StudentTotals","StudentPrint");

    var $PerDisc=TRUE;
    var $TableType="Absences";

    var $ShowStatus=FALSE;

    var $ShowMarks=TRUE;
    var $ShowMarkSums=FALSE;

    var $ShowMarkWeights=FALSE;
    var $ShowMarkWeightsTotals=TRUE;
    var $ShowMarksTotals=TRUE;

    var $ShowRecoveries=TRUE;

    var $ShowMediaFinal=TRUE;

    var $ShowAbsences=TRUE;
    var $ShowAbsencesTotals=TRUE;
    var $ShowAbsencesPercent=TRUE;
    var $ShowAbsenceFinal=TRUE;

    var $ShowNLessons=FALSE;
    var $ShowNLessonsTotals=TRUE;

    var $ShowFinal=TRUE;
    var $HandleTitle=TRUE;

    var $EmptyTableColumns=FALSE;

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscs($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array
        (
           "Grade","Class","Period","GradePeriod","GradeDisc",
           "Name","CHT","CHS",
           "AbsencesType","AssessmentType","NAssessments",
           "Teacher","Daylies",
        );
        $this->Sort=array("AssessmentType","AbsencesType","Name");

    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Actions=$this->ReadPHPArray("System/Daylies/Actions.php",$this->Actions);
        $this->ItemData=$this->ReadPHPArray("System/Grade/Data.Modes.php",$this->ItemData);

        $trimesterdata=$this->ReadPHPArray("System/Class/Discs/Data.Trimester.php");

        $key="Daylies";
        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            foreach ($trimesterdata as $data => $datadef)
            {
                $rkey=$key.$data.$n;
                $this->ItemData[ $rkey ]=$datadef;
                foreach (array("ShortName","Name","Title") as $namekey)
                {
                    $this->ItemData[ $rkey ][ $namekey ]=preg_replace
                    (
                       '/#N\b/',
                       $n,
                       $this->ItemData[ $rkey ][ $namekey ]
                    );
                }
            }
       }

        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            foreach (array("Clerk","Secretary") as $profile)
            {
                $this->ItemData[ $data ][ $profile ]=2;
            }
        }

        //$this->ItemData[ "AbsencesType" ][ "Disableds" ]=array(1);
    }


    //*
    //* function MakeDateSelect, Parameter list: $data,$item,$edit
    //*
    //* Makes select date field for data type $data. 
    //*

    function MakeDayliesDateSelect($data,$item,$edit)
    {
        if ($edit!=1)
        {
            return $this->ApplicationObj->DatesObject->DateID2Name($item[ $data ]);
        }

        $period=$this->ApplicationObj->LocatePeriod($item[ "Period" ]);

        //To make selected value display
        $period[ $data ]=$item[ $data ];

        return $this->ApplicationObj->PeriodsObject->MakePeriodDaySelect($data,$period,$item[ $data ]);
    }

    //*
    //* function DayliesDatas, Parameter list: $dics=array(),$class=array()
    //*
    //* Returns list of DayliesData
    //*

    function DayliesDatas($dics=array(),$class=array())
    {
        $nassessments=$this->ApplicationObj->GetNAssessments($dics,$class);

        $datas=array("Teacher","Daylies");
        $keys=array("DayliesLimit","DayliesClosed","DayliesClosedTime");
        for ($n=1;$n<=$nassessments;$n++)
        {
            foreach ($keys as $key)
            {
                array_push($datas,$key.$n);
            }
        }

        return $datas;
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();

        $this->Actions[ "Edit" ][ "HrefArgs" ]="ModuleName=Classes&Action=Disc&Disc=#ID";

        $key="DayliesLimit";
        $ckey="DayliesClosed";
        $tckey="DayliesClosedTime";
        $group="Daylies";
        $groupdef=array
        (
            "Name" => "Diários Eletrônicos",
            "Admin" => 1,
            "Person" => 1,
            "Public" => 1,
            "Data" => array("Daylies"),
        );

        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            array_push($groupdef[ "Data" ],$key.$n,$ckey.$n,$tckey.$n);
        }

        //Single group
        $this->AddItemDataGroup($group,$groupdef,FALSE);

        array_unshift
        (
           $groupdef[ "Data" ],
           "No","Edit","Classes",
           "Name",
           "Type",
           "Year",
           "Semester",
           "StartDate",
           "EndDate"
        );



        //Plural group
        $this->AddItemDataGroup($group,$groupdef,TRUE);
    }

    //*
    //* function PostProcessDaylies, Parameter list: &$item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcessDaylies(&$item)
    {
        $key="DayliesLimit";
        $period=$this->ApplicationObj-> LocatePeriod($item[ "Period" ]);

        if (!isset($period[ "Daylies" ])) { return; }

        if ($period[ "Daylies" ]==2)
        {
            if (empty($item[ "Daylies" ]) || $item[ "Daylies" ]==1)
            {
                $this->SetAndUpdateDataValue("",$item,"Daylies",2);
            }
        }

        if (!empty($item[ "Daylies" ]) && $item[ "Daylies" ]==2)
        {
            $nassessments=$this->ApplicationObj->GetNAssessments($item);

            $ddatas=array();
            for ($n=1;$n<=$nassessments;$n++)
            {
                array_push($ddatas,$key.$n);
            }

            $item=$this->MakeSureWeHaveRead("",$item,$ddatas);
            for ($n=1;$n<=$nassessments;$n++)
            {
                if (empty($item[ $key.$n ]))
                {
                    $item[ $key.$n ]=$period[ $key.$n ];
                    $this->SetAndUpdateDataValue("",$item,$key.$n,$period[ $key.$n ]);
                }

                $value=$this->Max($item[ $key.$n ],$period[ $key.$n ]);
                if ($value!=$item[ $key.$n ])
                {                   
                    $item[ $key.$n ]=$value;
                    $this->SetAndUpdateDataValue("",$item,$key.$n,$value);
                }
            }
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
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }

        $gradediscdatas=array
        (
           "Name",
           "NickName",
           "CHT","CHS",
           "AssessmenhtsType","AbsencesType",
        );

        $updatedatas=array();

        $gradedisc=$this->ApplicationObj->GradeDiscsObject->SelectUniqueHash
        (
           "",
           array
           (
              "ID" => $item[ "GradeDisc" ],
           ),
           FALSE,
           $this->GradeDiscTransferData
        );

        $item=$this->MakeSureWeHaveRead("",$item,$this->GradeDiscTransferData);

        foreach ($this->GradeDiscTransferData as $data)
        {
            if (empty($item[ $data ]) && isset($gradedisc[ $data ]))
            {
                $item[ $data ]=$gradedisc[ $data ];
                array_push($updatedatas,$data);
            }
        }
        $item=$this->MakeSureWeHaveRead("",$item,$this->ClassTransferData);

        foreach ($this->ClassTransferData as $data)
        {
            if (isset($this->ApplicationObj->Class[ $data ]))
            {
                if (empty($item[ $data ]))
                {
                    $item[ $data ]=$this->ApplicationObj->Class[ $data ];
                    array_push($updatedatas,$data);
                }
            }
        }

        if (empty($item[ "FinalMedia" ]) && isset($this->ApplicationObj->Class[ "MediaLimit" ]))
        {
            $item[ "FinalMedia" ]=$this->ApplicationObj->Class[ "MediaLimit" ];
            array_push($updatedatas,$data);
        }

        $this->NAssessments=$this->Max($item[ "NAssessments" ],$this->NAssessments);
        $this->NRecoveries=$this->Max($item[ "NRecoveries" ],$this->NRecoveries);
        if (empty($item[ "Teacher" ])) { $item[ "Teacher" ]=0; }

        if (count($updatedatas)>0)
        {
            $this->MySqlSetItemValues("",$updatedatas,$item);
        }

        $this->ReadDiscData($item);

        $this->PostProcessDaylies($item);

        return $item;
    }


    //*
    //* function ReadDiscData, Parameter list: &$disc
    //*
    //* Reads disc Lessons, NLessons and Weights.
    //* 
    //*

    function ReadDiscData(&$disc)
    {
        $this->ApplicationObj->ClassDiscLessonsObject->ReadClassDiscLessons($disc);
        $this->ApplicationObj->ClassDiscNLessonsObject->ReadClassDiscNLessons
        (
           $this->ApplicationObj->Class,
           $disc
        );
        $this->ApplicationObj->ClassDiscWeightsObject->ReadClassDiscWeights($disc);
    }


    //*
    //* function ReadClassDiscs, Parameter list: $class=array(),$teacherid=0
    //*
    //* Reads discs pertaining to class - transfer grade discs if nonexistent.
    //* 
    //*

    function ReadClassDiscs($class=array(),$teacherid=0)
    {
        if (!empty($this->ApplicationObj->Discs)) { return; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        if (!empty($class[ "ID" ]) && $class[ "ID" ]>0)
        {
            $this->ApplicationObj->ClassDiscsObject->SqlTable=
                $this->ApplicationObj->ClassesObject->SchoolAndPeriod2SqlTable($class,"ClassDiscs");

            $this->ApplicationObj->Discs=$this->CreateDefaultDisciplines($class);

            $where=array
            (
               "Class" => $class[ "ID" ],
            );

            if ($teacherid>0)
            {
                $wheres=array
                (
                   "Teacher='".$teacherid."'",
                   "Teacher1='".$teacherid."'",
                   "Teacher2='".$teacherid."'",
                );

                $where="Class='".$class[ "ID" ]."' AND (".join(" OR ",$wheres).")";
            }

            $this->ApplicationObj->Discs=$this->ApplicationObj->ClassDiscsObject->SelectHashesFromTable
            (
               $this->ApplicationObj->ClassDiscsObject->SqlTable,
               $where,
               array(),
               FALSE,
               "AssessmentType,AbsencesType,Name"
            );

            foreach (array_keys($this->ApplicationObj->Discs) as $id)
            {
                $this->ApplicationObj->Discs[ $id ]=
                    $this->ApplicationObj->ClassDiscsObject->PostProcess
                    (
                       $this->ApplicationObj->Discs[ $id ]
                    );
            }
       }
        else
        {
            die("Unable to read Discs for class: ".$class[ "ID" ]);
        }
    }

    //*
    //* function CreateDefaultDisciplines, Parameter list: $class
    //*
    //* Creates disciplines according to GradePeriod disciplines.
    //*

    function CreateDefaultDisciplines($class)
    {
        $discs=$this->ApplicationObj->GradeDiscsObject->SelectHashesFromTable
        (
           "",
           array
           (
              "Grade" => $class[ "Grade" ],
              "GradePeriod" => $class[ "GradePeriod" ],
              "Status" => 1,
           ),
           array(),
           FALSE,
           "Name"
        );

        $keys=array_keys($this->ItemData);
        $keys=preg_grep('/^ID$/',$keys,PREG_GREP_INVERT);

        foreach ($discs as $disc)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "GradeDisc" => $disc[ "ID" ],
            );

            if ($this->MySqlNEntries("",$where)==0)
            {
                $newdisc=$where;
                $newdisc[ "School" ]=$this->ApplicationObj->School[ "ID" ];
                $newdisc[ "Grade" ]=$class[ "Grade" ];
                $newdisc[ "GradePeriod" ]=$class[ "GradePeriod" ];
                $newdisc[ "Period" ]=$class[ "Period" ];

                foreach ($keys as $key)
                {
                    if (isset($disc[ $key ]))
                    {
                        $newdisc[ $key ]=$disc[ $key ];
                    }
                }

                $msg=$this->AddOrUpdate("",$where,$newdisc);
                array_push($this->HtmlStatus,$msg);
           }
        }
    }

    //*
    //* function AbsencesOnlyTotalsDisc, Parameter list: $class
    //*
    //* Returns a virtual absences totals only disc.
    //*

    function AbsencesOnlyTotalsDisc($class)
    {

        print "AbsencesOnlyTotalsDisc, Deprecated";;

        $disc=array
        (
           "ID" => 0,
           "Name" => "Somente Totais",
           "NickName" => "Totais",
           "Class" => $class[ "ID" ],
           "Period" => $class[ "Period" ],
           "CHS" => 5,
        );

        foreach ($this->ClassTransferData as $data)
        {
            $disc[ $data ]=$class[ $data ];
        }

        if (isset($this->ApplicationObj->Discs[0]))
        {
            for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
            {
                
            }
        }

        return $disc;
    }

    //*
    //* function ReadClassDisciplines, Parameter list: $class,$datas=array(),$absences=FALSE
    //*
    //* Reads disciplines of class. 
    //*

    function ReadClassDisciplines($class,$datas=array(),$absences=FALSE)
    {
        $this->ReadItems
        (
           array("Class" => $class[ "ID" ]),
           array_keys($this->ItemData),
           TRUE,TRUE,0
        );

        $this->CreateDefaultDisciplines($class);
        $this->ApplicationObj->Discs=$this->ItemHashes;

        return $this->ItemHashes;
   }

   //*
    //* function ShowClassDiscs, Parameter list: $echo=TRUE
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscs()
    {
        $this->InitProfile("ClassDiscs");
        $this->InitActions();
        $this->PostInit();

        $action=$this->GetGET("Action");
        $edit=0;
        if (preg_match('/^EditDiscs$/',$action))
        {
            $this->DefaultAction="EditDiscs";
            $edit=1;
        }

        print $this->SearchVarsTable
        (
           array
           (
              "School","Period","Class","Grade","GradePeriod","GradeDisc","Name","NickName",
              "Output","Paging","ShowAll","Edit"
           ),
           "","",array(),array(),
           "Classes"
         ).$this->BR();

        $this->ItemsName="Disciplinas";

        $this->NoPaging=TRUE;
        $this->ItemDataSGroups[ "DiscList" ]=$this->ItemDataGroups[ "DiscList" ];

        $this->ItemHashes=$this->ApplicationObj->Discs;
        $this->HandleList("",FALSE,$edit);//No paging!
    }

    //*
    //* function ReadDisc, Parameter list: $discid=0,$die=TRUE
    //*
    //* Reads disc, id being GET Disc.
    //* 
    //*

    function ReadDisc($discid=0,$die=TRUE)
    {
        if (empty($discid))
        {
            $discid=$this->GetGET("Disc");
        }

        if (empty($discid))
        {
            $discid=$this->GetGET("ID");
        }

        if (!empty($discid) && preg_match('/^\d+$/',$discid) && $discid>0)
        {
            $this->ApplicationObj->Disc=$this->SelectUniqueHash
            (
                $this->ApplicationObj->ClassesObject->SchoolAndPeriod2SqlTable($this->ApplicationObj->Class,"ClassDiscs"),
                array("ID" => $discid)
            );

            if (!empty($this->ApplicationObj->Disc[ "Teacher" ]))
            {
                $this->ApplicationObj->Disc[ "TeacherHash" ]=$this->SelectUniqueHash
                (
                   "People",
                   array("ID" => $this->ApplicationObj->Disc[ "Teacher" ])
                );
            }

            $this->ReadDiscData($this->ApplicationObj->Disc);

        }
        elseif ($die)
        {
            die("Invalid disc: ".$discid);
        }
    }
}

?>