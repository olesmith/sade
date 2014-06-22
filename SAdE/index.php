<?php

//set_include_path('../Application:../MySql2:../Base'.":..:.");
include_once("../Application/Application.php");


include_once("Common.php");
include_once("Units.php");
include_once("SAdE/Unit.php");
include_once("SAdE/School.php");
include_once("SAdE/Period.php");
include_once("SAdE/Class.php");
include_once("SAdE/InfoTable.php");
include_once("SAdE/LeftMenu.php");
include_once("SAdE/Accessors.php");

class SAdE extends SAdEAccessors
{
    var $SavePath="?Action=Start";
    var $ApplicationMessages="Application.php";

    var $MaxNAssessments=4;

    // 1: $this->ApplicationObj->OnlyTotals
    // 2: $this->ApplicationObj->AbsencesYes
    var $OnlyTotals=1;
    var $AbsencesYes=2;
    var $AbsencesNo=3;

    // 1: $this->ApplicationObj->Quantitative
    // 2: $this->ApplicationObj->Qualitative
    var $Quantitative=1;
    var $Qualitative=2;
    var $MarksNo=3;


    //var $PeriodicalModules=array();

    var $RealUnitsObject=NULL;

    var $LogsObject=NULL;
    var $PeopleObject=NULL;
    var $ClerksObject=NULL;
    var $UnitsObject=NULL;
    var $DepartmentsObject=NULL;
    var $ProtocolsObject=NULL;
    var $PlacesObject=NULL;
    var $ConsultsObject=NULL;
    var $DatesObject=NULL;
    var $PeriodsObject=NULL;

    var $GradeObject=NULL;
    var $GradePeriodsObject=NULL;
    var $GradeDiscsObject=NULL;
    var $GradeQuestionariesObject=NULL;
    var $GradeQuestionsObject=NULL;

    var $MatriculasObject=NULL;
    var $StudentsObject=NULL;
    var $UsersObject=NULL;
    var $SchoolsObject=NULL;

    var $ClassesObject=NULL;
    var $ClassStudentsObject=NULL;
    var $ClassDiscsObject=NULL;
    var $ClassMarksObject=NULL;
    var $ClassAbsencesObject=NULL;
    var $ClassStatusObject=NULL;
    var $ClassQuestionsObject=NULL;
    var $ClassObservationsObject=NULL;

    var $ClassDiscLessonsObject=NULL;
    var $ClassDiscNLessonsObject=NULL;
    var $ClassDiscWeightsObject=NULL;

    var $ClassDiscContentsObject=NULL;
    var $ClassDiscAbsencesObject=NULL;
    var $ClassDiscAssessmentsObject=NULL;
    var $ClassDiscMarksObject=NULL;

    var $Sigma="&Sigma;";
    var $Mu="&mu;";
    var $Percent="%";

    var $SqlVars=array();

    var $AppProfiles=array
    (
       "SAdE" => array
       (
          "Public",
          "Student",
          "Teacher",
          "Clerk",
          "Coordinator",
          "Secretary",
          "Admin",
       ),
    );

    var $DepartmentSearchField="Protocol";
    var $Department=array();

    var $ProtocolSearchField="Protocol";
    var $Protocols=array();
    var $Protocol=array();

    var $PlaceSearchField="Place";
    var $Places=array();
    var $Place=array();

    var $PersonSearchField="Person";
    var $Persons=array();
    var $Person=array();

    var $TargetSearchField="Target";
    var $Targets=array();
    var $Target=array();
    var $ClassLog=array();

    var $Student=NULL;
    var $StudentID=0;
    var $Students=NULL;

    var $Grades=array();
    var $Grade=array();
    var $GradePeriod=array();

    var $Disc=NULL;
    var $Discs=NULL;

    var $Teacher=NULL;
    var $Contents=array();

     //*
    //* function SAdE, Parameter list: $args=array()
    //*
    //* SAdE constructor.
    //*

    function SAdE($args=array())
    {
        if (isset($_POST[ "DiscID" ])) { $_GET[ "Disc" ]=$_POST[ "DiscID" ]; }
        if (isset($_POST[ "StudentID" ])) { $_GET[ "Student" ]=$_POST[ "StudentID" ]; }

        $this->SessionsTable="Sessions";
        $this->SqlVars=array("Unit" => $this->GetGET("Unit"));
        $this->SavePath="?Unit=".$this->GetGET("Unit")."&Action=Start";

        $args=$this->ReadPHPArray($this->SetupPath."/Setup.php",$args);
        $args[ "ValidProfiles" ]=$this->AppProfiles[ "SAdE" ];
        $this->MayCreateSessionTable=TRUE;

        $this->LogGETVars=array
        (
           "Unit","ID","School",
           "Grade","GradePeriod","GradeDisc",
           "Class","Disc","Student","Teacher"
        );

        $this->LogPOSTVars=array
        (
           "Edit","EditList","Save","Update","Generate","Transfer",
        );

        parent::Application($args);
        $this->LoadSubModule("Logs",TRUE);

        $this->LogsObject->LogEntry("SAdE");
    }


    //*
    //* function UpdateTablesStructure, Parameter list: $item
    //*
    //* Update table structures for $classes.
    //*

    function UpdateTablesStructure($classes)
    {
        $periodname=$this->GetPeriodName($this->Period);
        foreach ($classes as $class)
        {
            $obj=$class."Object";
            $this->$obj->SqlTable=$this->SchoolPeriodSqlTableName($class);

            if (!preg_match('/(__|#)/',$this->$obj->SqlTable))
            {
                $this->$obj->UpdateTableStructure();
            }
        }
    }

    //*
    //* function SetLoginData, Parameter list: $login=""
    //*
    //* Overrides SetLoginData, adding key Unit to $this->LoginData.
    //*

    function SetLoginData($logindata)
    {
        $unit=intval($this->GetGET("Unit"));
        if ($unit>0)
        {
            $this->DBHash[ "DB" ]=preg_replace('/#Unit/',$unit,$this->DBHash[ "DB" ]);
            $this->InitMySql($this->DBHash);

            if (!$this->MySqlIsTable("People"))
            {
                print "Invalid unit: ".$unit; exit();
            }

            parent::SetLoginData($logindata);
            $this->LoginData[ "Unit" ]=$unit;

            return;
        }

        print "Invalid unit..."; exit();
    }

    //*
    //* function SetLatexMode, Parameter list: 
    //*
    //* Changes some character constants to use with LatexMode=1.
    //*

    function SetLatexMode()
    {
        $this->Sigma   = '$'."\\Sigma".  '$';
        $this->Mu      = '$'."\\Mu".     '$';
        $this->Percent = "\\%";
    }

    //*
    //* function InitTInterfaceTitles, Parameter list: 
    //*
    //* Overrides InitTInterfaceTitles to put unit in titles.
    //*

    function InitTInterfaceTitles()
    {
        parent::InitTInterfaceTitles();

        if (empty($this->Unit)) { return; }

        for ($n=1;$n<=6;$n++)
        {
            $this->TInterfaceTitles[ $n-1 ]=$this->Unit[ "HtmlTitle".$n ];
        }

        for ($n=1;$n<=2;$n++)
        {
            $this->TInterfaceIcons[ $n ]=array
            (
               "Icon"   => $this->Unit[ "HtmlIcon".$n ],
               "Height" => $this->Unit[ "HtmlIconHeight" ],
               "Width"  => $this->Unit[ "HtmlIconWidth" ],
            );
        }
    }

    //*
    //* function InitApplication, Parameter list: 
    //*
    //* Application initializer.
    //*

    function InitApplication()
    {
        $this->ReadUnit();

        parent::InitApplication();
        if (empty($this->Unit))
        {
            $this->UnitList();
            exit();
        }

        touch("Logs/index.php");
        touch("tmp/index.php");

        $this->Unit2CompanyHash();
    }

    //*
    //* function PostInit, Parameter list: 
    //*
    //* Application post init, read school, class, ...
    //*

    function PostInit()
    {
        $school=$this->GetGET("School");
        if (!empty($school)) { $this->School(); }

        $period=$this->GetGET("Period");
        if (!empty($period)) { $this->Period(); }

        $class=$this->GetGET("Class");
        if (!empty($class)) { $this->GetClass(); }

        $this->LatexFilters=array
        (
           "Unit" => array
           (
              "PreKey" => "",
              "Object" => "UnitsObject",
           ),
           "School" => array
           (
              "PreKey" => "School_",
              "Object" => "SchoolsObject",
           ),
           /* "Period" => array */
           /* ( */
           /*    "PreKey" => "Period_", */
           /*    "Object" => "PeriodsObject", */
           /* ),  */
           "Class" => array
           (
              "PreKey" => "Class_",
              "Object" => "ClassesObject",
           ), 
           "Grade" => array
           (
              "PreKey" => "Grade_",
              "Object" => "GradeObject",
           ), 
           "GradePeriod" => array
           (
              "PreKey" => "GradePeriod_",
              "Object" => "GradePeriodsObject",
           ), 
        );
    }

    //*
    //* function PostInitSession, Parameter list: $logindata=array()
    //*
    //* Does nothing, avaliable to be overriden, for actions to do right after
    //* user session has been established.
    //*

    function PostInitSession($logindata=array())
    {
        if (count($logindata)==0) { $logindata=$this->LoginData; }
        $unit=$this->GetCookieOrGET("Unit");
        if (!empty($logindata[ "Unit" ]) && $this->GetCookie("Admin")!=1)
        {
            if ($unit!=$logindata[ "Unit" ])
            {
                print "Não permitido..."; exit();
            }
        }
    }

    //*
    //* sub ApplicationWindowTitle, Parameter list:
    //*
    //* Overwrite Application Window Title generator.
    //*
    //*

    function ApplicationWindowTitle()
    {
        $title="";
        if ($this->Module)
        {
            $title.=$this->Module->ApplicationWindowTitle();
        }

        if ($this->ModuleName=="Students")
        {
            $id=$this->GetGET("ID");
            if (preg_match('/^\d+$/',$id) && $id>0)
            {
                $this->StudentsObject->ReadStudent();
            }
        }

        $comps=array();
        if (!empty($this->Student))
        {
            array_push($comps,$this->Student[ "StudentHash" ][ "Name" ]);
        }
        if (!empty($this->Class))
        {
            array_push($comps,$this->Class[ "Name" ]);
        }
        if (!empty($this->Period))
        {
            array_push($comps,$this->Period[ "Name" ]);
        }

        if (!empty($this->Place))
        {
            array_push($comps,$this->Place[ "Name" ]);
        }

        if (!empty($this->School))
        {
            array_push($comps,$this->School[ "ShortName" ]);
        }

        $name=$this->HtmlSetupHash[ "ApplicationName"  ]."@";
        if (!empty($this->Unit[ "Name" ]))
        {
            $name.=$this->Unit[ "Name" ].": ";
        }

        return preg_replace
        (
           '/#ItemName/',
           "",
           preg_replace
           (
              '/#ItemsName/',
              "",
              $name.
              $title.
              join("::",$comps)
            )
        );
    }



    //*
    //* function HandleStart, Parameter list:
    //*
    //* Overrrides the Handlers Start Handler. Should display some basic info.
    //*

    function HandleStart()
    {
        $this->LoadModule("Units");
        $this->LoadSubModule("Schools");

        if ($this->GetCGIVarValue("Action")=="Start")
        {
            $this->ResetCookieVars();
        }

        $this->SetCookieVars();

        $this->HtmlHead();
        $this->HtmlDocHead();

        print
            $this->H(1,"SAdE - Sistema Administrativo Escolar").
            $this->HtmlTable
            (
               "",
               $this->UnitsObject->ItemTable
               (
                  0,
                  $this->Unit,
                  TRUE,
                  array
                  (
                     "Name","Title","Phone","Fax","Email","WWW",
                     "Address","ZIP","Area","City","State"
                  )
               ),
               array("BORDER" => 1,"ALIGN" => 'center')
            ).
            "<BR>\n";

    }

    //*
    //* function IsAnID, Parameter list: $id
    //*
    //* 
    //*

    function IsAnID($id)
    {
        if (preg_match('/^\d+$/',$id))
        {
            return TRUE;
        }

        return FALSE;
    }


    //*
    //* function CGI2StudentID, Parameter list:
    //*
    //* 
    //*

    function CGI2StudentID()
    {
        $id=$this->GetPOST("StudentID");
        if (empty($id)) { $id=$this->GetGET("Student"); }
        if (empty($id)) { $id=$this->GetGET("ID"); }

        return $id;
    }


    //*
    //* function ReadPlace, Parameter list:
    //*
    //* Reads Department from GET.
    //*

    function ReadPlace()
    {
        if ($this->Place) { return; }
        if (!$this->PlacesObject) { return; }

        $id=$this->GetGETOrPOST($this->PlaceSearchField);
        $id=preg_replace('/[^\d]/',"",$id);

        if ($this->IsAnID($id))
        {
            $this->Place=$this->PlacesObject->SelectUniqueHash
            (
               "",
               array("ID" => $id)
            );
        }
    }

    //*
    //* function ReadPerson, Parameter list:
    //*
    //* Reads person from GET PID.
    //*

    function ReadPerson()
    {
        if ($this->Person) { return; }
        if (!$this->PeopleObject) { return; }

        $id=$this->GetGET("PID");
        $id=preg_replace('/[^\d]/',"",$id);

        if ($this->IsAnID($id))
        {
            $this->Person=$this->PeopleObject->SelectUniqueHash
            (
               "",
               array("ID" => $id),
               TRUE,
               array()
            );
        }
    }

    //*
    //* function AddImportLogEntry, Parameter list: $file,$text
    //*
    //* Adds log entry $text to $this->ClassLog[ basename($file)
    //*

    function AddImportLogEntry($file,$text)
    {
        $key=basename($file);
        if (empty($this->ClassLog[ $key ]))
        {
            $this->ClassLog[ $key ]=array();
        }

        array_push
        (
           $this->ClassLog[ $key ],
           $text
        );
    }

    //*
    //* function SchoolPeriodSqlTableName, Parameter list: $class
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //* Overrides MySql2::SqlTableName.
    //*

    function SchoolPeriodSqlTableName($class)
    {
        return
            $this->School("ID").
            "_".
            $this->Period2SqlTable($this->Class).
            "_".
            $class;
    }

    //*
    //* function SchoolPeriodClassSqlTableName, Parameter list: $class
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //* Overrides MySql2::SqlTableName.
    //*

    function SchoolPeriodSqlClassTableName($class,$classid="")
    {
        if (empty($classid)) { $classid=$this->GetClass("ID"); }

        return
            $this->School("ID").
            "_".
            $this->Period2SqlTable($this->Class).
            "_".
            $classid.
            "_".
            $class;
    }

    //*
    //* function Period2SqlTable, Parameter list: $class=array()
    //*
    //* Returns entry to add to slq tablename for ClassDiscs and ClassStudents objects.
    //*

    function Period2SqlTable($class=array())
    {
        if (empty($class)) { $class=$this->Class; }

        $period=$this->Period();

        //if (empty($class[ "Period" ])) { var_dump($class); exit();}

        if (!empty($class[ "Period" ]))
        {
            foreach ($this->Periods() as $per)
            {
                if ($per[ "ID" ]==$class[ "Period" ])
                {
                    $period=$per;
                    break; //found!
                }
            }
        }

        $per=$period[ "Year" ];
        if ($period[ "Type" ]>1)
        {
            $per.="_".$period[ "Semester" ];
        }

        return $per;
    }


    //*
    //* function GetSqlTable, Parameter list: $item
    //*
    //* Returns entry to add to slq tablename.
    //*

    function GetSqlTable($classname,$school=FALSE,$period=FALSE,$class=FALSE,$item=array())
    {
        $comps=array();
        if ($school)
        {
            if (!empty($this->School[ "ID" ])) { $school=$this->School[ "ID" ]; }
            if (empty($school)) { $school=intval($this->GetGET("School")); }
            array_push($comps,$school);
        }

        if ($period)
        {
            array_push($comps,$this->Period2SqlTable($item));
        }
        if ($class)
        {
            $class=$this->Class[ "ID" ];
            if (empty($class)) { $class=intval($this->GetGET("Class")); }
            array_push($comps,$class);
        }

        array_push($comps,$classname);

        return join("_",$comps);
    }


}

$application=new SAdE();

?>
