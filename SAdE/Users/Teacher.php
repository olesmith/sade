<?php


class UsersTeacher extends UsersAdd
{
    var $TeacherScheduleTeacherData=array("Name","Email","Status");
    var $TeacherScheduleClassData=array("Name");
    var $TeacherScheduleDiscData=array("Name");
    var $TeacherScheduleDiscActions=array("Edit","Dayly");

    var $Grades=array();
    var $GradePeriods=array();

    //*
    //* function IsTeacher, Parameter list: $item
    //*
    //* Tests if $item is teacher, that is Profile_Teacher=2.
    //* Is access method for Teacher-like actions.
    //*

    function IsTeacher($item)
    {
        if (empty($item)) { return TRUE; }

        $item=$this->MakeSureWeHaveRead("",$item,array("Profile_Teacher"));

        if ($item[ "Profile_Teacher" ]==2) { return TRUE; }

        return FALSE;
    }

    //*
    //* function GetGradeName, Parameter list: $id
    //*
    //* Savely reads gradename from Grade.
    //*

    function GetGradeName($id)
    {
        if (!isset($this->Grades[ $id ]))
        {
            $this->Grades[ $id ]=$this->ApplicationObj->GradeObject->MySqlItemValue
            (
               "",
               "ID",
               $id,
               "Name"
            );
        }

        return $this->Grades[ $id ];
    }

    //*
    //* function GetGradePeriodName, Parameter list: $id
    //*
    //* Savely reads gradename from Grade.
    //*

    function GetGradePeriodName($id)
    {
        if (!isset($this->GradePeriods[$id  ]))
        {
            $this->GradePeriods[ $id ]=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue
            (
               "",
               "ID",
               $id,
               "Name"
            );
        }

        return $this->GradePeriods[ $id ];
    }

    //*
    //* function TeacherDiscsSqlWhere, Parameter list: $teacher
    //*
    //* Generates teacher disc sql where clause.
    //*

    function TeacherDiscsSqlWhere($sqltable,$teacher)
    {
        $teacherdata=array("Teacher","Teacher1","Teacher2");
        $where=array();
        foreach ($teacherdata as $data)
        {
            if ($this->ApplicationObj->ClassDiscsObject->DBFieldExists($sqltable,$data))
            {
                array_push($where,$data."='".$teacher."'");
            }
        }

        return join(" OR ",$where);
    }

    //*
    //* function TeacherPeriod2Schools, Parameter list: $teacher,$period
    //*
    //* Detects active scools, for $teacher $period.
    //*

    function TeacherPeriod2Schools($teacher,$period)
    {

        foreach ($this->ApplicationObj->Schools as $school)
        {
            $sqltable=
                $school[ "ID" ].
                "_".
                $this->ApplicationObj->PeriodsObject->GetPeriodName($period).
                "_".
                "ClassDiscs";

            if ($this->MySqlIsTable($sqltable))
            {
                $nentries=$this->MySqlNEntries
                (
                   $sqltable,
                   $this->TeacherDiscsSqlWhere($sqltable,$teacher)
                );

                if ($nentries>0)
                {
                    array_push($rperiods,$period);
                }
            }
        }
    }

    //*
    //* function TeachersOrClause, Parameter list: $teacher
    //*
    //* Generates Teachers SQL OR clause
    //*

    function TeachersOrClause($teacher)
    {
        return
            "(Teacher='".$teacher."'".
            " OR ".
            "Teacher1='".$teacher."'".
            " OR ".
            "Teacher2='".$teacher."')";
    }



    //*
    //* function ReadTeacherSchoolPeriods, Parameter list: $periodids
    //*
    //* Reads referenced teacher periods 
    //*

    function ReadTeacherPeriods($periodids)
    {
        $periods=array();
        foreach ($periodids as $periodid)
        {
            $this->ApplicationObj->PeriodsObject->ReadPeriod($periodid);
            $periods[ $periodid ]=$this->ApplicationObj->Period;
        }

        return $periods;
    }

    //*
    //* function TeacherPeriodsMenu, Parameter list: $periods
    //*
    //* Generate Teacher Periods menu.
    //*

    function TeacherPeriodsMenu($periods)
    {
        $cperiodid=$this->GetGET("Period");

        $urls=array();
        $urlnames=array();
        $urltitles=array();

        $args=$this->ScriptQueryHash();
        foreach ($periods as $period)
        {
            $args[ "Period" ]=$period[ "ID" ];

            $url=$period[ "Title" ];
            $urlname=$period[ "ID" ];
            if ($period[ "ID" ]!=$cperiodid)
            {
                $url=$this->HRef
                (
                   "?".$this->Hash2Query($args),
                   $period [ "Title" ],
                   $period[ "Name" ]
                );
                $urlname=$period[ "Title" ];
            }

            array_push($urls,$url);
            array_push($urlnames,$urlname);
            array_push($urltitles,$period[ "Title" ]);
        }

        return $this->HRefMenu
        (
           "",
           $urls,
           $urlnames,
           $urltitles,
           8,
           "menuitem",
           "menuinactive",
           "menutitle",
           $cperiodid
        );
    }
    //*
    //* function ReadTeacherPeriodSchools, Parameter list: $schoolids
    //*
    //* Reads referenced teacher schoold
    //*

    function ReadTeacherPeriodSchools($schoolids)
    {
        $schools=array();
        foreach ($schoolids as $schoolid)
        {
            $this->ApplicationObj->SchoolsObject->ReadSchool($schoolid);
            $schools[ $schoolid ]=$this->ApplicationObj->School;
        }

        return $schools;
    }

    //*
    //* function TeacherPeriodSchoolsClassDiscRow, Parameter list: $period,$school,$class,$disc,$n
    //*
    //* Generates table with all teacher period disciplines.
    //*

    function TeacherPeriodSchoolsClassDiscRow($period,$school,$class,$disc,$n)
    {
        $rdisc=$this->ApplicationObj->ClassDiscsObject->ReadItem($disc[ "Disc" ]);
        $row=array($n++);

        $actions=array();
        foreach ($this->TeacherScheduleDiscActions as $action)
        {
            array_push
            (
               $actions,
               $this->ApplicationObj->ClassesObject->ActionEntry($action,$class)
            );
        }

        array_push($row,join("",$actions));

        $gradename="";
        if (!isset($this->Grades[ $class[ "Grade" ] ]))
        {
            $gradename=$this->GetGradeName($class[ "Grade" ]);
        }

        $gradeperiodname="";
        if (!isset($this->GradePeriods[ $class[ "GradePeriod" ] ]))
        {
            $gradeperiodname=$this->GetGradePeriodName($class[ "GradePeriod" ]);
        }

        array_push($row,$gradename,$gradeperiodname);

        foreach ($this->TeacherScheduleClassData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassesObject->MakeShowField($data,$class)
            );
        }

        foreach ($this->TeacherScheduleDiscData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->MakeShowField($data,$rdisc)
            );
        }

        $this->ApplicationObj->ClassDiscLessonsObject->ReadClassDiscLessons($rdisc);
        array_push
        (
           $row,
           $this->Html_Table
           (
              "",
              $this->ApplicationObj->ClassDiscLessonsObject->ClassDiscLessonsTable(0,$rdisc,FALSE)
           )
        );

        return $row;
    }

    //*
    //* function TeacherPeriodSchoolsClassDiscsRows, Parameter list: $period,$school,$class,$discs,&$n
    //*
    //* Generates table with all teacher period disciplines.
    //*

    function TeacherPeriodSchoolsClassDiscsRows($period,$school,$class,$discs,&$n)
    {
        $this->ApplicationObj->ClassesObject->InitActions();
        $rows=array();
        foreach ($discs as $disc)
        {
            array_push
            (
               $rows,
               $this->TeacherPeriodSchoolsClassDiscRow($period,$school,$class,$disc,$n++)
            );
        }

        return $rows;
    }

    //*
    //* function TeacherPeriodSchoolsClassesRows, Parameter list: $period,$school,$discs,&$n
    //*
    //* Generates table with all teacher period disciplines.
    //*

    function TeacherPeriodSchoolsClassesRows($period,$school,$cdiscs,&$n)
    {
        $rows=array
        (
           array($this->H(5,$school[ "Name" ])),
           $this->B
           (
              array
              (
                 "No.",
                 "",
                 "Grade",
                 "Período",
                 "Turma",
                 "Temporal",
                 "Disciplina",
                 "Horários",
              )
           )
        );

        $this->ApplicationObj->ClassesObject->SqlTable=
            $school[ "ID" ]."_".
            "Classes";
        $this->ApplicationObj->ClassDiscsObject->SqlTable=
            $school[ "ID" ]."_".
            $this->ApplicationObj->PeriodsObject->GetPeriodName($period)."_".
            "ClassDiscs";

        $classes=array();
        foreach ($cdiscs as $classid => $discs)
        {
            $this->ApplicationObj->ClassesObject->ReadClass($classid).$classid;
            $class=$this->ApplicationObj->Class;
            if ($class[ "GradePeriod" ]>0)
            {
                $classname=$class[ "NameKey" ].$class[ "ID" ];
                $classes[ $classname ]=$class;
            }
        }

        $rclasses=array_keys($classes);
        sort($rclasses);

        foreach ($rclasses as $classname)
        {
            $class=$classes[ $classname ];

            $rows=array_merge
            (
               $rows,
               $this->TeacherPeriodSchoolsClassDiscsRows($period,$school,$class,$discs,$n)
            );
        }

        return $rows;
    }

    //*
    //* function TeacherPeriodSchoolsTable, Parameter list: $period,$pdiscs
    //*
    //* Generates table with all teacher period disciplines.
    //*

    function TeacherPeriodSchoolsTable($period,$pdiscs)
    {
        $schools=$this->ReadTeacherPeriodSchools(array_keys($pdiscs));

        $table=array();
        $n=1;
        foreach (array_keys($pdiscs) as $schoolid)
        {
            $table=array_merge
            (
               $table,
               $this->TeacherPeriodSchoolsClassesRows($period,$schools[ $schoolid ],$pdiscs[ $schoolid ],$n)
            );
        }

        return
            $this->H(1,"Período: ".$period[ "Title" ]).
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center',"BORDER" => 1),
               array(),
               array()
            );
    }


    //*
    //* function ShowTeacherInfoTable, Parameter list: $teacher
    //*
    //* Prints Teacher info table.
    //*

    function ShowTeacherInfoTable($teacher)
    {
        return
            $this->H(1,"Disciplinas do Professor(a)").
            $this->FrameIt
            (
               $this->Html_Table
               (
                  "",
                  $this->ItemTable
                  (
                     0,
                     $this->ReadItem($teacher),
                     FALSE,
                     $this->TeacherScheduleTeacherData
                  ),
                  array(),
                  array(),
                  array(),
                  FALSE,FALSE
               )
            );
    }

    //*
    //* function ShowTeacherSchedule, Parameter list: $teacher,$discs
    //*
    //* Shows teacher schedule and discs.
    //*

    function ShowTeacherSchedule($teacher,$discs)
    {
        $periods=$this->ReadTeacherPeriods(array_keys($discs));

        print 
            $this->TeacherPeriodsMenu($periods).
            $this->ShowTeacherInfoTable($teacher);

        foreach ($discs as $periodid => $pdiscs)
        {
            print
                $this->TeacherPeriodSchoolsTable($periods[ $periodid ],$pdiscs);
        }
    }


    //*
    //* function HandleTeacherSchedule, Parameter list: 
    //*
    //* Handles teacher schedule and discs.
    //*

    function HandleTeacherSchedule()
    {
        $discdata=array("Class","Name");
        $teacherdata=array("Name","Email","Status");

        $teacher=$this->GetGET("Teacher");
        if (empty($teacher)) { $teacher=$this->GetGET("ID"); }

        $wheres=array($this->TeachersOrClause($teacher));
        $periodids=$this->ApplicationObj->Teacher2DiscsObject->MySqlUniqueColValues
        (
           "",
           "Period",
           join(" AND ",$wheres),
           "",
           "School"
        );


        $discs=array();
        foreach ($periodids as $periodid)
        {
            $discs[ $periodid ]=array();

            $pwheres=$wheres;
            array_push($pwheres,"Period='".$periodid."'");

            $schoolids=$this->ApplicationObj->Teacher2DiscsObject->MySqlUniqueColValues
            (
               "",
               "School",
               join(" AND ",$pwheres),
               "",
               "Class"
            );

            foreach ($schoolids as $schoolid)
            {
                $discs[ $periodid ][ $schoolid ]=array();

                $swheres=$pwheres;
                array_push($swheres,"School='".$schoolid."'");

                $classids=$this->ApplicationObj->Teacher2DiscsObject->MySqlUniqueColValues
                (
                   "",
                   "Class",
                   join(" AND ",$swheres),
                   "",
                   ""
                 );

                foreach ($classids as $classid)
                {
                    $cwheres=$swheres;
                    array_push($cwheres,"Class='".$classid."'");

                    $discs[ $periodid ][ $schoolid ][ $classid ]=
                        $this->ApplicationObj->Teacher2DiscsObject->SelectHashesFromTable
                        (
                           "",
                           join(" AND ",$cwheres)
                        );

                }
            }
        }

        $this->ShowTeacherSchedule($teacher,$discs);

        /* return; */

        /* $this->ApplicationObj->ReadSchools(); */

        /* $periods=$this->ApplicationObj->PeriodsObject->GetAllPeriods(); */
        /* array_reverse($periods); */

 
        /* $rperiods=array(); */
        /* foreach ($periods as $period) */
        /* { */
        /*     foreach ($this->ApplicationObj->Schools as $school) */
        /*     { */
        /*         $sqltable= */
        /*             $school[ "ID" ]. */
        /*             "_". */
        /*             $this->ApplicationObj->PeriodsObject->GetPeriodName($period). */
        /*             "_". */
        /*             "ClassDiscs"; */

        /*         if ($this->MySqlIsTable($sqltable)) */
        /*         { */
        /*             $nentries=$this->MySqlNEntries */
        /*             ( */
        /*                $sqltable, */
        /*                $this->TeacherDiscsSqlWhere($sqltable,$teacher) */
        /*             ); */

        /*             if ($nentries>0) */
        /*             { */
        /*                 array_push($rperiods,$period); */
        /*                 break; */
        /*             } */
        /*         } */
        /*     } */
        /* } */

        /* if (empty($this->ApplicationObj->Period) && count($rperiods)>0) */
        /* { */
        /*     $this->ApplicationObj->Period=$rperiods[0]; */
        /* } */

        /* $args=$this->Query2Hash(); */
        /* $plinks=array(); */
        /* foreach ($rperiods as $period) */
        /* { */
        /*     $args[ "Period" ]=$period[ "ID" ]; */

        /*     $pname=$this->ApplicationObj->PeriodsObject->GetPeriodName($period); */

        /*     $plink=$this->SPAN($pname,array("CLASS" => 'inactivemenuitem')); */
        /*     if ($period[ "ID" ]!=$this->ApplicationObj->Period[ "ID" ]) */
        /*     { */
        /*         $plink=$this->SPAN */
        /*         ( */
        /*            $this->HRef("?".$this->Hash2Query($args),$pname), */
        /*            array("CLASS" => 'activemenuitem') */
        /*         ); */
        /*     } */

        /*     array_push($plinks,$plink); */
        /* } */

 
        /* print */
        /*     $this->H(1,"Disciplinas e Horários do Professor"). */
        /*     $this->FrameIt */
        /*     ( */
        /*        $this->Html_Table */
        /*        ( */
        /*           "", */
        /*           $this->ItemTable */
        /*           ( */
        /*              0, */
        /*              $this->ReadItem($teacher), */
        /*              FALSE, */
        /*              $this->TeacherScheduleTeacherData */
        /*           ), */
        /*           array(), */
        /*           array(), */
        /*           array(), */
        /*           FALSE,FALSE */
        /*        ) */
        /*     ). */
        /*     $this->HRefMenu("",$plinks). */
        /*     $this->H */
        /*     ( */
        /*        2, */
        /*        "Período: ". */
        /*         $this->ApplicationObj->PeriodsObject->GetPeriodTitle */
        /*         ( */
        /*            $this->ApplicationObj->Period */
        /*         ) */
        /*     ). */
        /*     ""; */

        /* $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles */
        /* ( */
        /*    $this->TeacherScheduleDiscData */
        /* ); */

        /* array_unshift($titles,"No."); */
        /* array_push($titles,"Horários"); */
        /* $titles=$this->B($titles); */

        /* $this->ApplicationObj->ClassesObject->InitProfile("Classes"); */
        /* $this->ApplicationObj->ClassesObject->InitActions(); */
        /* $this->ApplicationObj->ClassesObject->PostInit(); */

        /* foreach ($this->ApplicationObj->Schools as $school) */
        /* { */
        /*     $this->ApplicationObj->School=$school; */
        /*     $this->ApplicationObj->ClassesObject->SqlTable= */
        /*         $school[ "ID" ]."_Classes"; */

        /*     $sqltable= */
        /*         $school[ "ID" ]. */
        /*         "_". */
        /*         $this->ApplicationObj->PeriodsObject->GetPeriodName */
        /*         ( */
        /*            $this->ApplicationObj->Period */
        /*         ). */
        /*         "_". */
        /*         "ClassDiscs"; */

        /*     $discs=$this->SelectHashesFromTable */
        /*     ( */
        /*        $sqltable, */
        /*        $this->TeacherDiscsSqlWhere($sqltable,$teacher), */
        /*        array(), */
        /*        FALSE, */
        /*        "AbsencesType,Grade,GradePeriod,Name" */
        /*     ); */

        /*     if (count($discs)==0) { continue; } */

        /*     $table=array */
        /*     ( */
        /*        $this->H(4,$school[ "Name" ]), */
        /*        $titles */
        /*     ); */

        /*     $n=1; */
        /*     foreach ($discs as $disc) */
        /*     { */
        /*         $row=array($this->B($n++)); */
        /*         foreach ($this->TeacherScheduleDiscData as $data) */
        /*         { */
        /*             array_push */
        /*             ( */
        /*                $row, */
        /*                $this->ApplicationObj->ClassDiscsObject->MakeField */
        /*                ( */
        /*                   0, */
        /*                   $disc, */
        /*                   $data, */
        /*                   TRUE */
        /*                ) */
        /*             ); */
        /*         } */


        /*         $actions=array(); */
        /*         foreach ($this->TeacherScheduleDiscActions as $action) */
        /*         { */
        /*             $args=$this->Query2Hash($this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]); */

        /*             $args[ "School" ]=$school[ "ID" ]; */
        /*             $args[ "Period" ]=$this->ApplicationObj->Period[ "ID" ]; */
        /*             $args[ "Class" ]=$disc[ "Class" ]; */
        /*             $args[ "Disc" ]=$disc[ "ID" ]; */

        /*             $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]= */
        /*                 $this->Hash2Query($args); */
        /*             array_push */
        /*             ( */
        /*                $actions, */
        /*                $this->ApplicationObj->ClassesObject->ActionEntry */
        /*                ( */
        /*                   $action, */
        /*                   $disc */
        /*                ) */
        /*             ); */
        /*         } */

        /*         $this->ApplicationObj->ClassDiscLessonsObject->ReadClassDiscLessons($disc); */
        /*         array_push */
        /*         ( */
        /*            $row, */
        /*            $this->Html_Table */
        /*            ( */
        /*               "", */
        /*               $this->ApplicationObj->ClassDiscLessonsObject->ClassDiscLessonsTable(0,$disc,FALSE) */
        /*             ), */
        /*            join("",$actions) */
        /*         ); */
               
        /*         array_push($table,$row); */
        /*     } */

        /*     print  */
        /*         $this->Html_Table */
        /*         ( */
        /*            "", */
        /*            $table, */
        /*            array(), */
        /*            array(), */
        /*            array("STYLE" => 'border-style: solid;border-width: 1px;'), */
        /*            FALSE,FALSE */
        /*         ); */
        /* } */
        
    }
}

?>