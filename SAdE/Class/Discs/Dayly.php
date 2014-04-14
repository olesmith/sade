<?php

include_once("Class/Discs/Dayly/CGI.php");
include_once("Class/Discs/Dayly/Students.php");
include_once("Class/Discs/Dayly/PrintForm.php");

class ClassDiscsDayly extends ClassDiscsDaylyStudents
{
    var $ClassDiscModules=array("Contents","Absences","Assessments","Marks",);


    //*
    //* function HandleDayly, Parameter list: $echo=TRUE
    //*
    //* Central Daylies Handler.
    //*

    function HandleDayly()
    {
        $this->ApplicationObj->ClassDiscsObject->ReadDisc($this->GetGET("Disc"));

        $this->CheckAccess2Dayly();
        $this->UpdateDayliesSqlTables();

        if ($this->GetGET("Latex")!=1)
        {
            print 
                $this->H(1,"Diários Eletrônicos").
                $this->FrameIt
                (
                $this->Html_Table
                (
                   "",
                   $this->DaylyInfoTable(),
                   array
                   (
                      "ALIGN" => 'center',
                   )
                )).
                "<BR>";

            $this->ApplicationObj->ClassesObject->DoTinterfaceMenu(FALSE,$this->ApplicationObj->Class[ "ID" ]);
        }

        $action=$this->GetGET("Action");
        if (preg_match('/^DaylyPrints$/',$action))
        {
            $this->DaylyPrint();
        }
        elseif (preg_match('/^DaylyStudents$/',$action))
        {
            $this->HandleDaylyStudents();
        }
        elseif (preg_match('/^DaylyCalendar$/',$action))
        {
            $this->ApplicationObj->ClassDiscContentsObject->HandleCalendar();
        }
        elseif (preg_match('/^DaylyContents/',$action))
        {
            if ($this->GetGET("Latex")!=1)
            {
                $this->ApplicationObj->ClassDiscContentsObject->MakeContentsMenu($this->ApplicationObj->Disc);
            }

            if (preg_match('/^DaylyContentsDates$/',$action))
            {
                $this->ApplicationObj->ClassDiscContentsObject->HandleDaylyContentsDates();
            }
            elseif (preg_match('/^DaylyContents$/',$action))
            {
                $this->ApplicationObj->ClassDiscContentsObject->HandleDaylyContents();
            }
            elseif (preg_match('/^DaylyContentsPrint$/',$action))
            {
                $this->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscContentsObject->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscContentsObject->HandleDaylyContents();
            }
        }
        elseif (preg_match('/^DaylyAbsences/',$action))
        {
            if ($this->GetGET("Latex")!=1)
            {
                $this->ApplicationObj->ClassDiscAbsencesObject->MakeAbsencesMenu($this->ApplicationObj->Disc);
            }

            if (preg_match('/^DaylyAbsences$/',$action))
            {
                $this->ApplicationObj->ClassDiscAbsencesObject->HandleDaylyAbsences();
            }
            elseif (preg_match('/^DaylyAbsences(Stats|Months|Semesters)$/',$action))
            {
                $this->ApplicationObj->ClassDiscAbsencesObject->HandleDaylyStatAbsences();
            }
            elseif (preg_match('/^DaylyAbsencesPrint$/',$action))
            {
                $this->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscContentsObject->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscAbsencesObject->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscAbsencesObject->HandleDaylyAbsences();
            }

        }
        elseif (preg_match('/^DaylyAssessments/',$action))
        {
            if (preg_match('/^DaylyAssessments$/',$action))
            {
                $this->ApplicationObj->ClassDiscAssessmentsObject->HandleDaylyAssessments();
            }
            elseif (preg_match('/^DaylyAssessmentsPrint$/',$action))
            {
                $this->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscMarksObject->LatexMode=TRUE;
                $this->ApplicationObj->ClassDiscAssessmentsObject->HandleDaylyAssessments();
            }
        }
        elseif (preg_match('/^DaylyMarks/',$action))
        {
            if ($this->GetGET("Latex")!=1)
            {
                $this->ApplicationObj->ClassDiscAssessmentsObject->MakeAssessmentsMenu();
                $this->ApplicationObj->ClassDiscMarksObject->MakeMarksMenu($this->ApplicationObj->Disc);
            }

            if (preg_match('/^DaylyMarks$/',$action))
            {
                $this->ApplicationObj->ClassDiscMarksObject->HandleDaylyMarks();
            }
            elseif (preg_match('/^DaylyMarksPrint$/',$action))
            {
               $this->ApplicationObj->ClassDiscMarksObject->HandleDaylyMarks(TRUE);
            }
        }
        else
        {
            $edit=$this->ApplicationObj->ClassDiscsObject->CheckAccessEdit2Dayly();
            
            print
                $this->Html_Table
                (
                   "",
                   $this->DailyPeriodAndLessonsInfo($edit),
                   array
                   (
                      "ALIGN" => 'center'
                   ),
                   array(),
                   array(),
                   FALSE,FALSE
                ).
                "";
        }
    }

    //* function Module2ClassSqlTable, Parameter list: $modulename
    //*
    //* Returns: $schoolid_$periodkey_$classid_$module
    //*

    function Module2ClassSqlTable($modulename)
    {
        return
            $this->ApplicationObj->School[ "ID" ]."_".
            $this->ApplicationObj->Period2SqlTable($this->ApplicationObj->Class)."_".
            $this->ApplicationObj->Class[ "ID" ]."_".
            $modulename;
    }

    //*
    //* function UpdateDayliesSqlTables, Parameter list:
    //*
    //* Runs through $this->ClassDiscModules, creating and updating
    //* SqlTables as necessary
    //*

    function UpdateDayliesSqlTables()
    {
        foreach ($this->ClassDiscModules as $module)
        {
            $modulename="ClassDisc".$module;
            $objectname=$modulename."Object";
            if (is_object($this->ApplicationObj->$objectname))
            {
                $obj=$this->ApplicationObj->$objectname;
                $obj->SqlTable=$this->Module2ClassSqlTable($modulename);

                if (!preg_match('/(__|#)/',$obj->SqlTable))
                {
                    $obj->UpdateTableStructure();
                }
            }
        }
    }

    //*
    //* function DaylyInfoTable, Parameter list: $disc=array()
    //*
    //* Commomn info table for Dayly handler.
    //*

    function DaylyInfoTable($disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $this->ApplicationObj->Teacher=array("Name" => "-");
        $cells=array
        (
           array("Name","Teacher","Daylies"),
           array("GradePeriod","Teacher1","AbsencesType"),
           array("Class","Teacher2","AssessmentType"),
        );

        $table=array();
        foreach ($cells as $crow)
        {
            $row=array();
            foreach ($crow as $data)
            {
                array_push
                (
                   $row,
                   $this->B($this->GetDataTitle($data).":"),
                   $this->MakeShowField($data,$disc)
                );
            }
            array_push($table,$row);
        }

        return $table;

    }

    //*
    //* function DailyPeriodAndLessonsInfo, Parameter list: $edit
    //*
    //* Generate Period and Lessons info tables on Daylies page.
    //*

    function DailyPeriodAndLessonsInfo($edit)
    {
        $table=$this->ApplicationObj->PeriodsObject->PeriodTrimesterTable($edit);

        $buttons=$this->Buttons();
        array_unshift
        (
           $table,
           $this->H(3,"Datas do Período")
        );
        array_push
        (
           $table,
           $this->H(3,"Aulas Programadas")
        );

        $table=array_merge
        (
           $table,
           $this->ApplicationObj->ClassDiscLessonsObject->ClassDiscLessonsTable
           (
              0,
              $this->ApplicationObj->Disc,
              FALSE //title
           )
        );

        $startform="";
        $endform="";
        if ($edit==1)
        {
            $startform=
                $this->StartForm().
                $buttons;

            $endform=
                $buttons.
                $this->MakeHidden("Save",1).
                $this->StartForm();
        }

        return array
        (
           array
           (
              $startform.
              $this->Html_Table
              (
                 "",
                 $table,
                 array
                 (
                    "ALIGN" => 'center',
                    "WIDTH" => '100%',
                 ),
                 array
                 (
                 ),
                 array
                 (
                   "STYLE" => 'border-style: solid;border-width: 1px;',
                 ),
                 FALSE,
                 FALSE
              ).
              $endform,
           ),
        );
    }

}

?>