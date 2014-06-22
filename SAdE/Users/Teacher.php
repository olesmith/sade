<?php


class UsersTeacher extends UsersAdd
{
    var $TeacherScheduleTeacherData=array("Name","Email","Status");
    var $TeacherScheduleDiscData=array("Class","Name");
    var $TeacherScheduleDiscActions=array("Edit","Discs","Students","Hours","Dayly");


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
    //* function HandleTeacherSchedule, Parameter list: 
    //*
    //* Handles teacher schedule and discs.
    //*

    function HandleTeacherSchedule()
    {
        $discdata=array("Class","Name");
        $teacherdata=array("Name","Email","Status");

        $this->ApplicationObj->ReadSchools();

        $periods=$this->ApplicationObj->PeriodsObject->GetAllPeriods();
        array_reverse($periods);

        $teacher=$this->GetGET("Teacher");
        if (empty($teacher)) { $teacher=$this->GetGET("ID"); }

        $rperiods=array();
        foreach ($periods as $period)
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
                        break;
                    }
                }
            }
        }

        if (empty($this->ApplicationObj->Period) && count($rperiods)>0)
        {
            $this->ApplicationObj->Period=$rperiods[0];
        }

        $args=$this->Query2Hash();
        $plinks=array();
        foreach ($rperiods as $period)
        {
            $args[ "Period" ]=$period[ "ID" ];

            $pname=$this->ApplicationObj->PeriodsObject->GetPeriodName($period);

            $plink=$this->SPAN($pname,array("CLASS" => 'inactivemenuitem'));
            if ($period[ "ID" ]!=$this->ApplicationObj->Period[ "ID" ])
            {
                $plink=$this->SPAN
                (
                   $this->HRef("?".$this->Hash2Query($args),$pname),
                   array("CLASS" => 'activemenuitem')
                );
            }

            array_push($plinks,$plink);
        }

 
        print
            $this->H(1,"Disciplinas e Horários do Professor").
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
            ).
            $this->HRefMenu("",$plinks).
            $this->H
            (
               2,
               "Período: ".
                $this->ApplicationObj->PeriodsObject->GetPeriodTitle
                (
                   $this->ApplicationObj->Period
                )
            ).
            "";

        $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles
        (
           $this->TeacherScheduleDiscData
        );

        array_unshift($titles,"No.");
        array_push($titles,"Horários");
        $titles=$this->B($titles);

        $this->ApplicationObj->ClassesObject->InitProfile("Classes");
        $this->ApplicationObj->ClassesObject->InitActions();
        $this->ApplicationObj->ClassesObject->PostInit();

        foreach ($this->ApplicationObj->Schools as $school)
        {
            $this->ApplicationObj->School=$school;
            $this->ApplicationObj->ClassesObject->SqlTable=
                $school[ "ID" ]."_Classes";

            $sqltable=
                $school[ "ID" ].
                "_".
                $this->ApplicationObj->PeriodsObject->GetPeriodName
                (
                   $this->ApplicationObj->Period
                ).
                "_".
                "ClassDiscs";

            $discs=$this->SelectHashesFromTable
            (
               $sqltable,
               $this->TeacherDiscsSqlWhere($sqltable,$teacher),
               array(),
               FALSE,
               "AbsencesType,Grade,GradePeriod,Name"
            );

            if (count($discs)==0) { continue; }

            $table=array
            (
               $this->H(4,$school[ "Name" ]),
               $titles
            );

            $n=1;
            foreach ($discs as $disc)
            {
                $row=array($this->B($n++));
                foreach ($this->TeacherScheduleDiscData as $data)
                {
                    array_push
                    (
                       $row,
                       $this->ApplicationObj->ClassDiscsObject->MakeField
                       (
                          0,
                          $disc,
                          $data,
                          TRUE
                       )
                    );
                }


                $actions=array();
                foreach ($this->TeacherScheduleDiscActions as $action)
                {
                    $args=$this->Query2Hash($this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]);

                    $args[ "School" ]=$school[ "ID" ];
                    $args[ "Period" ]=$this->ApplicationObj->Period[ "ID" ];
                    $args[ "Class" ]=$disc[ "Class" ];
                    $args[ "Disc" ]=$disc[ "ID" ];

                    $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]=
                        $this->Hash2Query($args);
                    array_push
                    (
                       $actions,
                       $this->ApplicationObj->ClassesObject->ActionEntry
                       (
                          $action,
                          $disc
                       )
                    );
                }

                $this->ApplicationObj->ClassDiscLessonsObject->ReadClassDiscLessons($disc);
                array_push
                (
                   $row,
                   $this->Html_Table
                   (
                      "",
                      $this->ApplicationObj->ClassDiscLessonsObject->ClassDiscLessonsTable(0,$disc,FALSE)
                    ),
                   join("",$actions)
                );
               
                array_push($table,$row);
            }

            print 
                $this->Html_Table
                (
                   "",
                   $table,
                   array(),
                   array(),
                   array("STYLE" => 'border-style: solid;border-width: 1px;'),
                   FALSE,FALSE
                );
        }
        
    }
}

?>