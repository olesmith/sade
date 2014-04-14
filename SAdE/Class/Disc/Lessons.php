<?php

include_once("../MySql2/Unique.php");


class ClassDiscLessons extends Unique
{

    //*
    //* Variables of ClassDiscLessons class:
    //*

    var $DiscsDataGroup="HoursDef";
    var $LessonsDataGroup="LessonsData";
    var $ClassActions=array
    (
       "Disc",
    );

    var $DiscsActions=array
    (
       "DiscMarks","DiscAbsences","Dayly",
    );
    var $DiscsData=array
    (
       "Name","CHS","CHT",
    );

    var $DiscsEditData=array
    (
       "AbsencesType","AssessmentType","Teacher","Teacher1","Teacher2",
    );




    //*
    //*
    //* Constructor.
    //*

    function ClassDiscLessons($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("TimeLoad");
        $this->Sort=array("Name");
        $this->UniqueKeys=array("Class","ClassDisc","Assessment");
    }


    //*
    //* function SqlTableName, Parameter list: $table=""
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //* Overrides MySql2::SqlTableName.
    //*

    function SqlTableName($table="")
    {
        return $this->ApplicationObj->SchoolPeriodSqlTableName($this->ModuleName);
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
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

        $timeload="";
        if (!empty($item[ "Start" ]) && !empty($item[ "End" ]))
        {
            $start=preg_replace('/:/',".",$item[ "Start" ]);
            $end=preg_replace('/:/',".",$item[ "End" ]);

            $tl=$end-$start;
            $timeload=intval($tl);
            if ($tl>$timeload) { $timeload++; }
        }


        if (empty($item[ "TimeLoad" ]) || $timeload!=$item[ "TimeLoad" ])
        {
            $this->MySqlSetItemValue("","ID",$item[ "ID" ],"TimeLoad",$timeload);
            $item[ "TimeLoad" ]=$timeload;
        }

        return $item;
    }


    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=FALSE;

        return $res;
    }

    //*
    //* function MakeWeekDaySelect, Parameter list: $data,$lesson,$edit
    //*
    //* Calls Schools object SchoolWeekDaysSelect.
    //*

    function MakeWeekDaySelect($data,$lesson,$edit)
    {
        if ($edit!=1)
        {
            if (!empty($this->WeekDays[ $lesson[ $data ]-1 ]))
            {
                return $this->WeekDays[ $lesson[ $data ]-1 ];
            }
        }
        else
        {
            return $this->ApplicationObj->SchoolsObject->SchoolWeekDaysSelect
            (
               $data,
               $lesson[ $data ]
            );
        }

        return "";
    }

    //*
    //* function ReadClassDiscLessons, Parameter list: &$disc
    //*
    //* Reads CHS ClassDiscLessons entries.
    //*

    function ReadClassDiscLessons(&$disc)
    {
        if (empty($disc)) { return; }
        if (empty($disc[ "CHS" ])) { return; }

        $disc[ "Lessons" ]=array();
        for ($n=1;$n<=$disc[ "CHS" ];$n++)
        {
            $lesson=array
            (
               "ClassDisc" => $disc[ "ID" ],
               "Class" => $disc[ "Class" ],
               "Assessment" => $n,       
            );

            $lesson=$this->ReadOrAdd
            (
               array
               (
                  "ClassDisc" => $disc[ "ID" ],
                  "Class" => $disc[ "Class" ],
                  "Assessment" => $n,       
               )
            );

            $lesson=$this->PostProcess($lesson);
            array_push($disc[ "Lessons" ],$lesson);
        }
    }

 

     //*
    //* function ClassDiscLessonsTable, Parameter list: $edit,$disc
    //*
    //* Generates class disc lessons table.
    //*

    function ClassDiscLessonsTable($edit,$disc,$addtitle=TRUE)
    {
        $lessons=array();
        if ($edit==1)
        {
            $lessons=$disc[ "Lessons" ];
        }
        else
        {
            foreach ($disc[ "Lessons" ] as $lesson)
            {
                if (!empty($lesson[ "Start" ]) && !empty($lesson[ "End" ]))
                {
                    array_push($lessons,$lesson);
                }
            }
        }

        $table=$this->ItemsTableDataGroup
        (
           "Disciplinas",
           $edit,
           $this->LessonsDataGroup,
           $lessons
        );

        array_shift($table);
        array_unshift
        (
           $table,
           $this->B($this->GetDataTitles($this->ItemDataGroups[ $this->LessonsDataGroup ][ "Data" ]))
        );

        if ($addtitle)
        {
            array_unshift($table,$this->H(3,"Horários da Disciplina: ".$disc[ "NickName" ]));
        }

        return $table;
    }

    //*
    //* function UpdateClassDisc, Parameter list: &$disc,$class=array()
    //*
    //* Updates Teachers data for $disc..
    //*

    function UpdateClassDisc(&$disc,$class=array())
    {
        $updatedatas=array();
        foreach ($this->DiscsEditData as $data)
        {
            $cgikey="Disc_".$disc[ "ID" ]."_".$data;
            $cgivalue=$this->GetPOST($cgikey);

            if ($disc[ $data ]!=$cgivalue)
            {
                $disc[ $data ]=$cgivalue;
                array_push($updatedatas,$data);
            }
        }

        if (count($updatedatas)>0)
        {
            $this->ApplicationObj->ClassDiscsObject->MySqlSetItemValues("",$updatedatas,$disc);
        }
    }

    //*
    //* function UpdateClassDiscs, Parameter list: &$discs=array(),$class=array()
    //*
    //* Updates Teachers data for discs in $this->ApplicationObj->Discs
    //*

    function UpdateClassDiscs(&$discs,$class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        foreach (array_keys($discs) as $id)
        {
            $this->UpdateClassDisc($discs[ $id ],$class);
        }
    }


    //*
    //* function ShowClassDiscLessons, Parameter list: $edit,$disc,$class,&$n,&$table
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscLessons($edit,$disc,$class,$n=NULL,&$table,$plural=TRUE)
    {
        $row=array();
        if ($n) { array_push($row,$this->B($n)); }

        foreach ($this->ClassActions as $data)
        {
            array_push
            (
               $row,
               preg_replace
               (
                  '/#Disc/',
                  $disc[ "ID" ],
                  $this->ApplicationObj->ClassesObject->ActionEntry($data,$class)
               )
            );
        }

        foreach ($this->DiscsActions as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->ActionEntry($data,$disc)
            );
        }

        foreach ($this->DiscsData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->MakeField(0,$disc,$data,TRUE)
            );
        }

       foreach ($this->DiscsEditData as $data)
        {
             array_push
            (
               $row,
               $this->PrependInputNameTag
               (
                  $this->ApplicationObj->ClassDiscsObject->MakeField($edit,$disc,$data),
                  "Disc_".$disc[ "ID" ]."_"
               )
            );
        }


        array_push($table,$row);

        if ($disc[ "AbsencesType" ]!=$this->ApplicationObj->AbsencesNo)
        {
            array_push
            (
               $table,
               array
               (
                  "",
                  $this->Html_Table
                  (
                     "",
                     $this->ClassDiscLessonsTable($edit,$disc),
                     array("ALIGN" => 'center')
                  )
               )
            );
        }
    }
    //*
    //* function ShowClassSchedule, Parameter list: $class=array()
    //*
    //* Displays weekly Schedule for class disciplines.
    //*

    function ShowClassSchedule($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        foreach (array_keys($this->ApplicationObj->Discs) as $id)
        {
            $this->ReadClassDiscLessons($this->ApplicationObj->Discs[ $id ]);
        }

       
        $this->ApplicationObj->ClassesObject->ClassSchedule();
    }

    //*
    //* function ShowClassDiscsLessons, Parameter list: $edit=0,$class=array()
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscsLessons($edit=0,$class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class,array(),TRUE);

        if ($this->GetPOST("Update")==1 && $edit==1)
        {
            $this->UpdateClassDiscs($this->ApplicationObj->Discs,$class);
        }

        $this->InitProfile("ClassDiscs");
        $this->ApplicationObj->ClassDiscsObject->InitActions();
        $this->PostInit();

        $titles=array_merge($this->DiscsActions,$this->DiscsData,$this->DiscsEditData);

        $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles($titles);
        array_unshift($titles,"","");


        $ltitles=$this->GetDataTitles
        (
           $this->ItemDataGroups[ $this->LessonsDataGroup ][ "Data" ]
        );
        array_unshift($ltitles,"","","");


        $table=array($this->B($titles));

        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $this->ShowClassDiscLessons($edit,$disc,$class,$n,$table);
            $n++;
        }

        $edit1="";
        $edit2="";
        if ($edit==1)
        {
            $edit1=
                $this->StartForm().
                $this->Buttons();

            $edit2=
                $this->MakeHidden("Update",1).
                $this->Buttons().
                $this->EndForm();
        }

        print 
            $this->H(2,"Horários da Turma  ".$this->ApplicationObj->Period[ "Name" ]).
            $edit1.
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE
            ).
            $edit2.
            "";
    }





    //*
    //* function ImportDiscLessons, Parameter list: $class,$disc,&$table
    //*
    //* Importa alunos. 
    //* 
    //*

    function ImportDiscLessons($class,$disc,&$table)
    {
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Assessment" => $n,
            );

            $hash=$where;
            //$hash[ "Teacher" ]=0;
            $hash[ "WeekDay" ]=0;
            $hash[ "Start" ]="";
            $hash[ "End" ]="";

            $msg=$this->AddOrUpdate("",$where,$hash);
            array_push
            (
               $table,
               array
               (
                  "",
                  "",
                  "Import Disc Lesson ".$disc[ "ID" ],
                  $msg,
                  $n
               )
            );
        }
    }
}

?>