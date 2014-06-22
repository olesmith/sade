<?php


include_once("Classes/Import.php");
include_once("Classes/Read.php");
include_once("Classes/Schedule.php");
include_once("Classes/Prints.php");
include_once("Classes/Dayly.php");
include_once("Classes/Handle.php");
include_once("Classes/Transfer.php");
include_once("Classes/Empties.php");
include_once("Classes/LeftMenu.php");
include_once("Classes/Selects.php");
include_once("Classes/Handlers.php");
include_once("Classes/Access.php");


class Classes extends ClassesAccess
{
    //*
    //* Variables of Classes class:
    //*

    var $Grade2ClassData=array
    (
       "MediaLimit","AbsencesLimit"
    );
    var $GradePeriod2ClassData=array
    (
       "AssessmentType","NAssessments","AssessmentsWeights",
       "NRecoveries","AbsencesType"
    );

    var $PeriodModules=array
    (
       "ClassDiscs","ClassDiscLessons","ClassDiscNLessons","ClassDiscWeights",
       "ClassStudents","ClassMarks","ClassAbsences","ClassStatus",
       "ClassQuestions","ClassObservations",
    );

    var $PeriodsSet=array();
    var $Disciplines=array();

    var $GradePeriodTransferData=array
    (
       "AssessmentType","NAssessments","AssessmentsWeights","NRecoveries","AbsencesType","AssessmentType"
    );


    //*
    //*
    //* Constructor.
    //*

    function Classes($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("Number","Key","Period","GradePeriod");
        $this->AlwaysReadData=array("Number","Period","NStudents","NInactive","LastStudentsLastDate","CHS","Grade","GradePeriod");

        $this->NoPaging=TRUE;
        $this->IncludeAll=TRUE;
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
        $this->ItemData=$this->ReadPHPArray("System/Classes/Data.Daylies.php",$this->ItemData);

        $school=$this->GetGET("School");
        $this->SqlTableName=intval($school)."_Classes";
    }

    //*
    //* function SqlTableName, Parameter list:
    //*
    //* Overrides SqlTableName.
    //*

    function SqlTableName($table="")
    {
        return $this->ApplicationObj->GetSqlTable("Classes",TRUE,FALSE,FALSE);
    }


    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
        $this->ApplicationObj->LoadSubModule("Periods");

        //$this->ApplicationObj->ReadSchoolPeriods();
        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            //$this->ItemData[ $data ][ $this->ApplicationObj->LoginType ]=1;
            //$this->ItemData[ $data ][ $this->ApplicationObj->Profile ]=1;
        }

        $this->ApplicationObj->ReadClass();
        $this->Actions[ "Edit" ][ "HrefArgs" ]="Class=#ID";
        $this->Actions[ "Delete" ][ "AccessMethod" ]="MayDelete";

        $this->SqlWhere="Period='".$this->ApplicationObj->Period("ID")."'";

        $this->ItemData[ "GradePeriod" ][ "SqlDerivedData" ]=array_merge
        (
           $this->ItemData[ "GradePeriod" ][ "SqlDerivedData" ],
           $this->ApplicationObj->GradePeriodsObject->Grade2PeriodTransferData
        );

        if ($this->ApplicationObj->Period[ "Daylies" ]==2)
        {
            foreach (array("Admin","Clerk","Secretary","Coordinator","Teacher") as $profile)
            {
                $this->Actions[ "Daylies" ][ $profile ]=1;
            }
        }

        //$this->Actions[ "Show" ][ "Name" ]="Mostrar Turma";
        //$this->Actions[ "Edit" ][ "Name" ]="Editar Turma";
        $this->Actions[ "Show" ][ "Title" ]="Mostrar Turma";
        $this->Actions[ "Edit" ][ "Title" ]="Editar Turma";
    }

    //*
    //* function ModuleProfileFile, Parameter list: $module=""
    //*
    //* Returns name of Module profile file (Profiles.php)
    //* to read. Calls $this->ApplicationObj->ModuleProfileFile($module)
    //* for the task. Supposed as a mean to redirect this behaviour, when
    //* needed.
    //*

    function ModuleProfileFile($module="")
    {
        $action=$this->GetGET("Action");

        if (preg_match('/^Dayly/',$action))
        {
            return "System/Daylies/Profiles.php";
        }

        return parent::ModuleProfileFile($module);
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Classes")
        {
            return $item;
        }

        $updatedatas=array();
        if (!empty($item[ "Period" ]) && empty($this->PeriodsSet[ $item[ "Period" ] ]))
        {
            $this->UpdateSubTablesStructure($item,"Classes");
        }

        if (!empty($item[ "Period" ]) && !empty($item[ "GradePeriod" ]))
        {
            $item[ "NameKey" ]=
                $item[ "GradePeriod_Name" ].
                " ".
                $item[ "Name" ].
                " ".
                $item[ "Period_Year" ];

            array_push($updatedatas,"NameKey");
        }

        $studentids=$this->ApplicationObj->ClassStudentsObject->MySqlUniqueColValues
        (
           $this->ApplicationObj->SchoolPeriodSqlTableName("ClassStudents"),
           "Student",
           array
           (
              "Class" => $item[ "ID" ],
              //"Status" => 1,
           )
        );

        $stats=$this->ApplicationObj->StudentsObject->MySqlItemsValue
        (
           "",
           "ID",
           $studentids,
           "Status"
        );

        $ninactive=0;
        foreach ($stats as $id => $status)
        {
            if ($status!=1) { $ninactive++; }
        }

        
        $nactive=count($studentids)-$ninactive;
        if (
              isset($item[ "NStudents" ])
              &&
              $item[ "NStudents" ]!=$nactive
           )
        {
            $item[ "NStudents" ]=$nactive;
            array_push($updatedatas,"NStudents");
        }

        if (
              isset($item[ "NInactive" ])
              &&
              (
                 !$item[ "NInactive" ]
                 ||
                 $item[ "NInactive" ]!=$ninactive
              )
           )
        {
            array_push($updatedatas,"NInactive");
        }
        $item[ "NInactive" ]=$ninactive;

        foreach ($this->GradePeriodTransferData as $data)
        {
            if (isset($item[ "GradePeriod_".$data ]))
            {
                if (empty($item[ $data ]) || $item[ $data ]!=$item[ "GradePeriod_".$data ])
                {
                    $item[ $data ]=$item[ "GradePeriod_".$data ];
                    array_push($updatedatas,$data);
                }
            }
        }

        if (!empty($item[ "Grade" ]) && !empty($item[ "GradePeriod" ]))
        {
            $where=array
            (
                "Grade" => $item[ "Grade" ],
                "GradePeriod" => $item[ "GradePeriod" ],
            );

            $this->DatasNeedUpdate
            (
               array
               (
                  "CHT" => $this->ApplicationObj->GradeDiscsObject->RowSum("",$where,"CHT"),
                  "CHS" => $this->ApplicationObj->GradeDiscsObject->RowSum("",$where,"CHS"),
               ),
               $item
            );
        }

        if (empty($item[ "LastStudentsLastDate" ]))
        {
            $item[ "LastStudentsLastDate" ]=$this->TimeStamp2DateSort();
            array_push($updatedatas,"LastStudentsLastDate");
        }

        if (count($updatedatas)>0)
        {
            $this->MySqlSetItemValues("",$updatedatas,$item);
        }
       
        return $item;
    }

    //*
    //* function DoTInterfaceMenu, Parameter list:
    //*
    //* Hack to make it possible to call TInterfaceMenu at later stage (Dayly).
    //*

    function DoTInterfaceMenu($plural=FALSE,$id="")
    {
        //Deactivate Absence related links, if absences off
        if (intval($this->ApplicationObj->Disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo)
        {
            foreach (array("DaylyContentsDates","DaylyContents","DaylyAbsences") as $action)
            {
                foreach (array($this->Profile,$this->LoginType) as $access)
                {
                    $this->Actions[ $action ][ $access ]=0;
                }
            }
        }

        //Deactivate Absence related links, if absences off
        if (intval($this->ApplicationObj->Disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo)
        {
            foreach (array("DaylyAssessments","DaylyMarks") as $action)
            {
                foreach (array($this->Profile,$this->LoginType) as $access)
                {
                    $this->Actions[ $action ][ $access ]=0;
                }
            }
        }

        if (intval($this->ApplicationObj->Disc[ "AssessmentType" ])==$this->ApplicationObj->Qualitative)
        {
            foreach (array("DaylyMarks") as $action)
            {
                foreach (array($this->Profile,$this->LoginType) as $access)
                {
                    $this->Actions[ $action ][ $access ]=0;
                }
            }
        }

        if (empty($id)) { $id=$this->ApplicationObj->Class[ "ID" ]; }
        parent::TinterfaceMenu($plural,$id);
    }

    //*
    //* function TInterfaceMenu, Parameter list:
    //*
    //* Overrides MySql2::TInterfaceMenu
    //*

    function TInterfaceMenu($plural=FALSE,$id="")
    {
        $this->ApplicationObj->InfoTable();

        if (!preg_match('/^Dayly/',$this->GetGET("Action")))
        {
            if (
                  empty($id)
                  &&
                  !empty($this->ApplicationObj->Class[ "ID" ])
               ) { $id=$this->ApplicationObj->Class[ "ID" ]; }

            parent::TinterfaceMenu($plural,$id);
        }

    }

    //*
    //* function GetSearchVarCGIValue, Parameter list: $data,$rdata=""
    //*
    //* Overrides MySql GetSearchVarCGIValue, providing school id if $data is School,
    //* otherwisae just calls parent.
    //*

    function GetSearchVarCGIValue($data,$rdata="")
    {
        $value=parent::GetSearchVarCGIValue($data,$rdata);
        if (empty($value) && $data=="School") { $value=$this->ApplicationObj->School[ "ID" ]; }

        return $value;
    }


    //*
    //* function TexFileName, Parameter list: $item,$typename
    //*
    //* Returns name of text file, considering class $item.
    //*

    function TexFileName($item,$typename)
    {
        $texfile=
            "Diarios.".
            $this->MTime2FName().".".
            $this->ApplicationObj->Period[ "Year" ].".".
            $this->ApplicationObj->Period[ "Semester" ].".".
            $this->ApplicationObj->GradePeriod[ "Name" ].".".
           $item[ "Name" ].".".
            ".tex";

        $texfile=preg_replace('/\s+/',"",$texfile);
        $texfile=preg_replace('/:/',".",$texfile);
        $texfile=preg_replace('/&ordm;/',".",$texfile);
        $texfile=preg_replace('/\.+/',".",$texfile);

        return $texfile;
    }
    

    //*
    //* function SearchVarsTable, Parameter list: $omitvars=array(),$title="",$action="",$addvars=array(),$fixedvalues=array()
    //*
    //* Overrides MySql2 SearchVarsTable. Unsets GETSearch vars fas search vars,
    //* and call parent. Restores values on return.
    //*

    function SearchVarsTable($omitvars=array(),$title="",$action="Search",$addvars=array(),$fixedvalues=array(),$module = '')
    {
        //No search table on Period class lists
        $period=$this->GetGET("Period");
        //?? 16/01/2014  if (preg_match('/^\d+$/',$period) && $period>0) { return ""; }

        $oldpvalue=$this->ItemData[ "Period" ][ "Search" ];
        $this->ItemData[ "Period" ][ "Search" ]=FALSE;

        $oldsvalue=$this->ItemData[ "School" ][ "Search" ];
        $this->ItemData[ "School" ][ "Search" ]=FALSE;

        $table=parent::SearchVarsTable
        (
           array
           (
            "Grade","GradePeriod",
              /* "Period","Class","GradeDisc","Name","NickName", */
            "Output","Paging","ShowAll","Edit",
           ),
           $title,
           $action,
           $addvars,
           $fixedvalues
        );


        $this->ItemData[ "Period" ][ "Search" ]=$oldpvalue;
        $this->ItemData[ "School" ][ "Search" ]=$oldsvalue;

        return $table;
    }


    //*
    //* function SchoolAndPeriod2SqlTable, Parameter list: $item,$module
    //*
    //* Returns entry to add to slq tablename for ClassDiscs and ClassStudents objects.
    //*

    function SchoolAndPeriod2SqlTable($item,$module)
    {
        return
            $this->ApplicationObj->School[ "ID" ].
            "_".
            $this->ApplicationObj->Period2SqlTable($item).
            "_".
            $module;
    }


    //*
    //* function SchoolAndPeriod2SqlTables, Parameter list: $class=array()
    //*
    //* Make sure that correct Discs and Students tables exists for $class.
    //*

    function SchoolAndPeriod2SqlTables($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        foreach ($this->PeriodModules as $module)
        {
            $obj=$module."Object";
            $this->ApplicationObj->$obj->SqlTable=$this->SchoolAndPeriod2SqlTable($class,$module);
        }

        if (!empty($class[ "Period" ]))
        {
            $this->PeriodsSet[ $class[ "Period" ] ]=TRUE;
        }
    }

    //*
    //* function UpdateDBFields, Parameter list: $table="",$datas=array(),$datadefs=array(),$maycreate=TRUE
    //*
    //* Overrides UpdateDBFields, checks if school id is valid,
    //* in case it is, calls parent. 
    //*

    function UpdateDBFields($table="",$datas=array(),$datadefs=array(),$maycreate=TRUE)
    {
        $table=$this->SqlTableName($table);
        if (preg_match('/^(\d+)_/',$table,$matches))
        {
            $school=$matches[1];
            if ($this->IsSchool($school))
            {
                parent::UpdateDBFields($table,$datas,$datadefs,$maycreate);
            }
        }
    }


    //*
    //* function UpdateSubTablesStructure, Parameter list: $class,$modulename=""
    //*
    //* Make sure that correct Discs and Students tables exists for$class .
    //*

    function UpdateSubTablesStructure($class=array(),$modulename="")
    {
        $this->SchoolAndPeriod2SqlTables($class);
        $this->ApplicationObj->UpdateTablesStructure($this->PeriodModules);

        if (!empty($class[ "Period" ]))
        {
            $this->PeriodsSet[ $class[ "Period" ] ]=TRUE;
        }
     }

    
}

?>